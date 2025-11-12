<?php

namespace Database\Seeders;

use App\Models\Opd;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $opds = [
            ['nama' => 'Dinas Komunikasi dan Informatika', 'status' => 'Internal'],
            ['nama' => 'Badan Perencanaan Pembangunan Daerah', 'status' => 'Internal'],
            ['nama' => 'Dinas Pariwisata', 'status' => 'Internal'],
            ['nama' => 'Dinas Koperasi', 'status' => 'Internal'],
            ['nama' => 'Dinas Pemberdayaan Masyarakat dan Desa', 'status' => 'Internal'],
            ['nama' => 'Dinas Koperasi UKM Perindustrian dan Perdagangan', 'status' => 'Internal'],
            ['nama' => 'Dinas Ketahanan Pangan dan Pertanian', 'status' => 'Internal'],
            ['nama' => 'Dinas Pekerjaan Umum dan Tata Ruang', 'status' => 'Internal'],
            ['nama' => 'Dinas Perumahan dan Kawasan Permukiman', 'status' => 'Internal'],
            ['nama' => 'Dinas Lingkungan Hidup', 'status' => 'Internal'],
            ['nama' => 'Dinas Pariwisata', 'status' => 'Internal'],
            ['nama' => 'Kementerian Lingkungan Hidup', 'status' => 'Eksternal'],
            ['nama' => 'CDK Lumajang', 'status' => 'Internal'],
        ];

        Opd::insert($opds);

    }
}
