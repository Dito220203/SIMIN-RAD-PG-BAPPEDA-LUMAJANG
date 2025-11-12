<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;
    protected $table = 'maps'; // pastikan sama dengan nama tabel di database
    protected $fillable = [
        'id_monev',
        'id_pengguna',
        'latitude',
        'longitude',
    ];

     public function fotoProgres()
    {
        return $this->belongsTo(Monev::class, 'id_monev','id');
    }
    public function penggunas()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }
     public function monev()
    {
        return $this->belongsTo(Monev::class, 'id_monev');
    }

}
