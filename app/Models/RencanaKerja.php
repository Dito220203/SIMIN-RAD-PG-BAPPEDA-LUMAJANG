<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaKerja extends Model
{
    use HasFactory;

    protected $table = 'rencana_kerjas';

    protected $fillable = [
        'id_pengguna',
        'id_subprogram',
        'rencana_aksi',
        'sub_kegiatan',
        'kegiatan',
        'nama_program',
        'lokasi',
        'volume',
        'satuan',
        'anggaran',
        'sumberdana',
        'tahun',
        'id_opd',
        'status',
        'keterangan',
        'input',
        'delete_at',
        'is_locked',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }

    public function subprogram()
    {
        return $this->belongsTo(Subprogram::class, 'id_subprogram', 'id');
    }
    public function rencanaAksi()
    {
        return $this->belongsTo(RencanaAksi_6_tahun::class, 'rencana_aksi', 'id');
    }

    public function opd()
    {
        return $this->belongsTo(Opd::class, 'id_opd', 'id');
    }

    // public function monev()
    // {
    //     return $this->hasMany(Monev::class, 'rencana_aksi', 'id');
    // }
    public function monev()
    {
        return $this->hasMany(Monev::class, 'id_renja', 'id');
    }
    // di dalam model RencanaKerja.php
    public function scopeActive($query)
    {
        return $query->where('delete_at', '0');
    }
}
