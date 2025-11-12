<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoProgres extends Model
{
    use HasFactory;
    protected $table = 'foto_progres';
    protected $fillable = ['id_monev', 'id_pengguna', 'foto', 'deskripsi'];

    public function monev()
    {
        return $this->belongsTo(Monev::class, 'id_monev', 'id');
    }

    public function penggunas()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }

    public function map()
    {
        return $this->hasMany(Map::class, 'id_monev', 'id');
    }
}
