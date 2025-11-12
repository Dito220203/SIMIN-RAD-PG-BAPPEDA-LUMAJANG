<?php
namespace App\Helpers;

use App\Models\Log;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function add($aktivitas)
    {
        LogAktivitas::create([
            'ip' => request()->ip(),
            'aktivitas' => $aktivitas,
            'id_pengguna' => Auth::guard('pengguna')->id(),
            'waktu' => now()
        ]);
    }
}
