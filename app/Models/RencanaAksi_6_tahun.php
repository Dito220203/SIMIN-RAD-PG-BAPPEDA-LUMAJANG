<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaAksi_6_tahun extends Model
{
    use HasFactory;
    protected $table = 'rencana_aksi_6_tahuns';
    protected $fillable = [
        'id_pengguna',
        'id_subprogram',
        'rencana_aksi',
        'sub_kegiatan',
        'kegiatan',
        'nama_program',
        'lokasi',
        'satuan',
        'volume',
        'anggaran',
        'sumberdana',
        'tahun',
        'id_opd',
        'keterangan',
        'delete_at'

    ];

    public function penggunas()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }
    public function subprogram()
    {
        return $this->belongsTo(Subprogram::class, 'id_subprogram', 'id');
    }
    public function opd()
    {
        return $this->belongsTo(Opd::class, 'id_opd', 'id');
    }

    public function rencana_kerja()
    {
        return $this->hasMany(RencanaKerja::class, 'rencana_aksi', 'id');
    }
    // public function monev()
    // {
    //     return $this->hasMany(RencanaKerja::class, 'rencana_aksi', 'id');
    // }
}

