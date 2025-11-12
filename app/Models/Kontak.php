<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Kontak extends Model
{
    use HasFactory;
    protected $table = 'kontaks';
    protected $fillable = ['id_pengguna','alamat','telepon','email','namafb','linkfb','namaig','linkig','namayt','linkyt'];

       public function penggunas()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }
}
