<?php

namespace App\Providers;

use App\Models\Kecamatan;
use App\Models\Potensi;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot()
{
    // ❌ Hapus baris path.public

    View::composer('componentsclient.navbar', function ($view) {
        $kecamatanIds = Potensi::distinct('id_kecamatan')->pluck('id_kecamatan');
        $kecamatan = Kecamatan::whereIn('id', $kecamatanIds)->get();

        $potensi = Potensi::latest()->first();

        $view->with([
            'kecamatan' => $kecamatan,
            'selectedKecamatan' => $potensi->id_kecamatan ?? null,
            'selectedDesa' => $potensi->id_desa ?? null,
        ]);
    });

    Paginator::useBootstrapFive();
}

}
