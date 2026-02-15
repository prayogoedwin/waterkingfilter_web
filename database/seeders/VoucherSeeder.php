<?php

namespace Database\Seeders;

use App\Models\VoucherJenis;
use App\Models\VoucherPartner;
use App\Models\VoucherPenggunaan;
use App\Models\VoucherTipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $voucherTipe = ['terbuka', 'khusus'];

        foreach ($voucherTipe as $tipe) {
            VoucherTipe::create([
                'tipe' => $tipe
            ]);
        }

        $voucherJenis = ['gratis', 'cashback', 'potongan_nominal', 'potongan_persentase'];

        foreach ($voucherJenis as $jenis) {
            VoucherJenis::create([
                'jenis' => $jenis
            ]);
        }

        $voucherPenggunan = ['tak_terbatas', 'terbatas', 'terbatas_per_user'];

        foreach ($voucherPenggunan as $penggunaan) {
            VoucherPenggunaan::create([
                'penggunaan' => $penggunaan,
            ]);
        }

        $voucherPartner = ['semua_partner', 'satu_partner', 'beberapa_partner'];

        foreach ($voucherPartner as $partner) {
            VoucherPartner::create([
                'name' => $partner
            ]);
        }
    }
}
