<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\TebakPertandingan;
use App\Models\Pertandingan;
use App\Models\Member;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;


use App\Models\ProdukStokVarian;
use App\Models\Produk;

class DashboardMember extends Controller
{
    public function index()
    {
        if (Auth::guard('member')->check()) {
            $memberId = Auth::guard('member')->id();

            // Hitung jumlah prediksi oleh member ini
            $totalPrediksi = TebakPertandingan::where('member_id', $memberId)->count();

            // Ambil data member, termasuk kolom poin_terkini
            $member = Member::find($memberId);

            return view('member.dashboard', [
                'totalPrediksi' => $totalPrediksi,
                'member' => $member,
            ]);
        }

        return redirect()->route('member.login')->with('error', 'Silakan login terlebih dahulu.');
    }


    // public function getVarians($id)
    // {
    //     $varians = ProdukStokVarian::where('produk_id', $id)->get(['id', 'varian', 'ukuran', 'stok']);
    //     return response()->json($varians);
    // }

    public function getVarians($id)
    {
        $cacheKey = 'produk_varians_' . $id;
        $expiration = now()->addMinutes(60); // atau sesuaikan durasi caching

        $varians = Cache::remember($cacheKey, $expiration, function () use ($id) {
            return ProdukStokVarian::where('produk_id', $id)
                ->get(['id', 'varian', 'ukuran', 'stok']);
        });

        return response()->json($varians);
    }
    

    public function cekPoin(Request $request)
    {
        $memberId = Auth::guard('member')->id();
        $member = Member::find($memberId);
        $produk = Produk::find($request->produk_id);

        if (!$produk || !$member) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        if ($member->poin_terkini >= $produk->poin) {
            // Simpan order
            // $order = Order::create([
            //     'member_id' => $memberId,
            //     'produk_id' => $request->produk_id,
            //     'produk_stok_varian_id' => $request->produk_stok_varian_id,
            //     'poin_dipakai' => $produk->poin,
            // ]);

            // Kurangi poin
            $member->decrement('poin_terkini', $produk->poin);

            return response()->json(['success' => true, 'message' => 'Berhasil tukar']);
        } else {
            return response()->json(['success' => false, 'message' => 'Maaf, poin Anda tidak cukup']);
        }
    }
    
    public function riwayatPrediksi()
    {
        if (Auth::guard('member')->check()) {
            $memberId = Auth::guard('member')->id();
            $tebakans = TebakPertandingan::with('pertandingan')
                        ->where('member_id', $memberId)
                        ->orderBy('id', 'desc')
                        ->limit(50)
                        ->get();

            return view('member.riwayat-prediksi', compact('tebakans'));
        }
    }


    public function riwayatTukarPoin(){
        if (Auth::guard('member')->check()) {
            $memberId = Auth::guard('member')->id();
            $orders = Order::where('member_id', $memberId)->orderBy('id', 'desc')->get();
            return view('member.riwayat-poin', compact('orders'));
        }
    }

    public function profilMember(){
        $expiration = env('REDIS_TIME', 86400);

        if (Auth::guard('member')->check()) {

            $memberId = Auth::guard('member')->id();

            $profil = Member::where('id', $memberId)->first();

            return view('member.profil', compact('profil'));
        }
    }

    public function tukarPoin(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|integer',
            'produk_stok_varian_id' => 'required|integer',
        ]);

        $member = Auth::guard('member')->user();

        if (!$member->alamat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alamat masih kosong, mohon isi terlebih dahulu di halaman profil Anda.'
            ]);
        }

        if (!$member->whatsapp ||$member->whatsapp == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Whatsapp masih kosong / 0, mohon isi terlebih dahulu di halaman profil Anda.'
            ]);
        }

        $produkVarian = ProdukStokVarian::with('produk')->findOrFail($request->produk_stok_varian_id);

        if ($produkVarian->stok < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok varian ini telah habis.'
            ]);
        }

        $jumlah = 1;
        $poinSatuan = $produkVarian->produk->poin ?? 0;
        $poinTotal = $poinSatuan * $jumlah;

        // Cek apakah poin cukup
        if ($member->poin_terkini < $poinTotal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Poin Anda tidak cukup untuk menukar produk ini.'
            ]);
        }

        $alamat = $member->alamat.' ('.$member->whatsapp.')';

        // Simpan Order
        Order::create([
            'member_id' => $member->id,
            'produk_stok_varians_id' => $produkVarian->id,
            'produk_id' => $produkVarian->produk_id,
            'varian' => $produkVarian->varian,
            'ukuran' => $produkVarian->ukuran,
            'jumlah' => $jumlah,
            'poin_satuan' => $poinSatuan,
            'poin_total' => $poinTotal,
            'alamat_pengiriman' => $alamat,
        ]);

        // Kurangi stok
        $produkVarian->decrement('stok', $jumlah);

        // Kurangi poin member
        $member->decrement('poin_terkini', $poinTotal);

        // Cache::forget('tipe_produk_data');
        // Cache::forget('kategori_produk_data');
        // Cache::forget('produk_data');

        Cache::forget('produk_varians_' . $produkVarian->produk_id);

       

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menukar poin.'
        ]);
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:5',
            'whatsapp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        $member_auth = Auth::guard('member')->user();
        $member = Member::findOrFail($request->id);

        // Update hanya jika user sesuai dengan yang login
        if ($member_auth->id != $request->id ) {
            abort(403, 'Akses tidak sah');
        }

        $member->name = $request->name;
        $member->whatsapp = $request->whatsapp;
        $member->alamat = $request->alamat;

        // Cek jika password input bukan bintang-bintang (masking UI)
        if ($request->password && $request->password !== '******') {
            $member->password = Hash::make($request->password);
        }

        $member->save();

        return redirect()->route('member.profil')->with('success', 'Profil berhasil diperbarui.');
    }
 }