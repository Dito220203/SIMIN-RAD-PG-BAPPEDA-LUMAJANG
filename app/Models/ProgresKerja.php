<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class ProgresKerja extends Model
{
    use HasFactory;
    protected $table = 'progres_kerjas';
    protected $fillable = ['id_pengguna', 'id_monev'];

    public function maps()
    {
        return $this->hasMany(Map::class, 'id_monev', 'id');
    }

    public function subprogram()
    {
        return $this->belongsTo(Subprogram::class, 'id_monev');
    }
    public function fotoProgres()
    {
        return $this->hasMany(FotoProgres::class, 'id_monev', 'id');
    }


    public function monev()
    {
        return $this->belongsTo(Monev::class, 'id_monev', 'id');
    }

    public function penggunas()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }
}
