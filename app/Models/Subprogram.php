<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Subprogram extends Model
{
    use HasFactory;
    protected $table = 'subprograms';
    protected $fillable = ['id_pengguna', 'program', 'subprogram', 'uraian','delete_at'];

    public function penggunas()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }
    public function rencana_aksi()
    {
        return $this->hasMany(RencanaAksi_6_tahun::class, 'id_subprogram', 'id');
    }
    public function rencanaKerjas()
    {
        return $this->hasMany(RencanaKerja::class, 'id_subprogram', 'id');
    }

    public function progresKerjas()
    {
        return $this->hasMany(ProgresKerja::class, 'id_subprogram', 'id');
    }
    public function foto_subprograms()
    {
        return $this->hasMany(FotoSubprogram::class, 'id_subprogram', 'id');
    }

    public function monev()
    {
        return $this->hasMany(Monev::class, 'id_subprogram', 'id');
    }
}
