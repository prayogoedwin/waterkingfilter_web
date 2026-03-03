<?php

namespace Database\Seeders;

use App\Models\WaktuVoucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WaktuVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['tanggal_fix', 'periode_tanggal', 'tanggal_tertentu', 'hari_tertentu', 'setiap_hari'];

        foreach ($data as $waktu) {
            WaktuVoucher::create([
                'waktu' => $waktu
            ]);
        }
    }
}
