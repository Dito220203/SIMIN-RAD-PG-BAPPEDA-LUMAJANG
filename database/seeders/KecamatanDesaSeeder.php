<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class KecamatanDesaSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatans = [
            ["code" => "35.08.03", "name" => "Candipuro"],
            ["code" => "35.08.13", "name" => "Gucialit"],
            ["code" => "35.08.17", "name" => "Jatiroto"],
            ["code" => "35.08.16", "name" => "Kedungjajang"],
            ["code" => "35.08.19", "name" => "Klakah"],
            ["code" => "35.08.06", "name" => "Kunir"],
            ["code" => "35.08.10", "name" => "Lumajang"],
            ["code" => "35.08.14", "name" => "Padang"],
            ["code" => "35.08.04", "name" => "Pasirian"],
            ["code" => "35.08.11", "name" => "Pasrujambe"],
            ["code" => "35.08.02", "name" => "Pronojiwo"],
            ["code" => "35.08.18", "name" => "Randuagung"],
            ["code" => "35.08.20", "name" => "Ranuyoso"],
            ["code" => "35.08.08", "name" => "Rowokangkung"],
            ["code" => "35.08.12", "name" => "Senduro"],
            ["code" => "35.08.15", "name" => "Sukodono"],
            ["code" => "35.08.21", "name" => "Sumbersuko"],
            ["code" => "35.08.09", "name" => "Tekung"],
            ["code" => "35.08.05", "name" => "Tempeh"],
            ["code" => "35.08.01", "name" => "Tempursari"],
            ["code" => "35.08.07", "name" => "Yosowilangun"],
        ];

        DB::table('kecamatans')->insert($kecamatans);

        foreach ($kecamatans as $kecamatan) {
            try {
                $this->command->info("⬇ Fetch desa untuk {$kecamatan['name']} ...");

                $response = Http::timeout(30)
                    ->withoutVerifying()
                    ->get("https://wilayah.id/api/villages/{$kecamatan['code']}.json");

                if ($response->failed()) {
                    $this->command->error("⚠ Gagal fetch {$kecamatan['name']}");
                    continue;
                }

                $villages = $response->json();

                if (!isset($villages['data']) || !is_array($villages['data'])) {
                    $this->command->error("⚠ Tidak ada data desa untuk {$kecamatan['name']}");
                    continue;
                }

                foreach ($villages['data'] as $desa) {
                    DB::table('desas')->updateOrInsert(
                        ['code' => $desa['code']],
                        [
                            'name' => $desa['name'],
                            'district_code' => $kecamatan['code'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                $this->command->info("✅ Berhasil insert " . count($villages['data']) . " desa untuk {$kecamatan['name']}");

            } catch (\Exception $e) {
                $this->command->error("⚠ Error fetch {$kecamatan['name']}: " . $e->getMessage());
            }
        }
    }
}
