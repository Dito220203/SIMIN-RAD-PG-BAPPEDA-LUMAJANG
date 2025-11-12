<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\RencanaKerja;
use App\Models\ProgresKerja;
use App\Models\Monev;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Composer ini akan berjalan untuk view navbar dan layout utama
        // Pastikan nama layout utama Anda benar (contoh: 'layouts.app' atau 'layouts.admin')
        View::composer(['components.navbar', 'layouts.main'], function ($view) {

            // 1. Ambil SEMUA notifikasi yang statusnya bukan 'Valid'
            $rencana_all = RencanaKerja::where('status', '!=', 'Valid')->latest()->get();
            $progres_all = ProgresKerja::where('status', '!=', 'Valid')->latest()->get();
            $monev_all = Monev::where('status', '!=', 'Valid')->latest()->get();

            // 2. Gabungkan semua notifikasi menjadi satu koleksi dan urutkan berdasarkan yang terbaru
            $all_notifications = $rencana_all
                ->concat($progres_all)
                ->concat($monev_all)
                ->sortByDesc('created_at');

            // 3. Ambil 5 notifikasi teratas dari koleksi gabungan untuk ditampilkan di dropdown
            $notifikasi_dropdown = $all_notifications->take(5);

            // 4. Kirim kedua variabel ke view
            // '$notifikasi' untuk dropdown
            $view->with('notifikasi', $notifikasi_dropdown);
            // '$all_notifications' untuk modal "Lihat Semua"
            $view->with('all_notifications', $all_notifications);
        });
    }
}
