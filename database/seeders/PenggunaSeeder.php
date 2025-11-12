<?php

namespace Database\Seeders;

use App\Models\Opd;
use App\Models\Pengguna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opd1 = Opd::first();
        Pengguna::create([
            'nama' => 'Dito Febriansyah',
            'username' => 'dito',
            'password' => bcrypt('123'),
            'level' => 'Super Admin',       // jika ada kolom level
            'id_opd' => $opd1->id,   // atau sesuai kebutuhan
        ]);
        $opd2 = Opd::first();
        Pengguna::create([
            'nama' => 'Mohammad Farhan Syaikowi',
            'username' => 'fasy',
            'password' => bcrypt('123'),
            'level' => 'Admin',       // jika ada kolom level
            'id_opd' => $opd2->id,   // atau sesuai kebutuhan
        ]);
    }
}
