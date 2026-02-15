<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\TebakPertandingan;
use App\Models\Pertandingan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PrediksiMember extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'pemenang' => 'required',
            'metode' => 'required',
            'ronde' => 'required',
        ]);

        Cache::forget("tonton_data_member:{$memberId}");

        TebakPertandingan::create([
            'member_id' => Auth::guard('member')->id(),
            'pertandingan_id' => $id,
            'tebak_pemenang_id' => $request->pemenang,
            'tebak_pemenang' => 1, // 1 artinya user sudah menebak pemenang
            'tebak_metode' => $request->metode,
            'tebak_ronde' => $request->ronde,
        ]);

        return response()->json(['message' => 'Prediksi berhasil disimpan.']);
    }

  public function store_server(Request $request, $id)
    {
        $request->validate([
            'pemenang' => 'required',
            'metode' => 'required',
            'ronde' => 'required',
        ]);

        $memberId = Auth::guard('member')->id();

        Cache::forget("tonton_data_member:{$memberId}");

        // Ambil data pertandingan
        $pertandingan = Pertandingan::findOrFail($id); // Gunakan findOrFail untuk memastikan data ditemukan

        // Cek dan update jago pemain
        if ($request->pemenang == 1) {
            $pertandingan->increment('pemain_1_jago');
            $nama = $pertandingan->pemain_1_nama;
        } elseif ($request->pemenang == 2) {
            $pertandingan->increment('pemain_2_jago');
            $nama = $pertandingan->pemain_2_nama;
        } else {
            return redirect()->back()->withErrors(['pemenang' => 'Pilihan pemenang tidak valid.']);
        }

      

        // Simpan prediksi
        TebakPertandingan::create([
            'member_id' => $memberId,
            'pertandingan_id' => $id,
            'tebak_pemenang_id' => $request->pemenang,
            'tebak_pemenang' => $nama,
            'tebak_metode' => $request->metode,
            'tebak_ronde' => $request->ronde,
        ]);

        return redirect()->to(route('publik') . '#match-' . $id)
            ->with("success_match_$id", 'Prediksi berhasil disimpan.');
    }

   public function delete_prediksi_server(Request $request, $id)
    {
        $memberId = Auth::guard('member')->id();
        

        Cache::forget("tonton_data_member:{$memberId}");

        // Ambil data prediksi yang akan dihapus
        $prediksi = TebakPertandingan::where('member_id', $memberId)
            ->where('pertandingan_id', $id)
            ->first();

        if (!$prediksi) {
            return redirect()->route('publik')->with("success_match_$id", 'Tidak ada prediksi yang ditemukan.');
        }

        // dd($prediksi->tebak_pemenang_id);

        // Ambil data pertandingan
        $pertandingan = Pertandingan::findOrFail($id);

        // Kurangi jago pemain sesuai prediksi
        if ($prediksi->tebak_pemenang_id == 1) {
            $pertandingan->decrement('pemain_1_jago');
        } elseif ($prediksi->tebak_pemenang_id == 2) {
            $pertandingan->decrement('pemain_2_jago');
        }

        // Hapus prediksi
        $prediksi->forceDelete();

        return redirect()->to(route('publik') . '#match-' . $id)
            ->with("success_match_$id", 'Prediksi berhasil dibatalkan.');
    }
}