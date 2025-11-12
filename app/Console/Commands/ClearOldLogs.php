<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class ClearOldLogs extends Command
{
    /**
     * Nama command
     */
    protected $signature = 'logs:clear';

    /**
     * Deskripsi command
     */
    protected $description = 'Hapus log aktivitas yang lebih dari 1 bulan';

    /**
     * Jalankan command
     */
    public function handle()
    {
        $deleted = LogAktivitas::where('created_at', '<', now()->subMonth())->delete();

        $this->info("Berhasil menghapus {$deleted} log aktivitas lebih dari 1 bulan.");
    }
}
