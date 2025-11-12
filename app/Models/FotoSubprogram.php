<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoSubprogram extends Model
{
    Use HasFactory;
    protected $table = 'foto_subprograms';
    protected $fillable = ['id_pengguna','id_subprogram','judul','keterangan', 'foto','delete_at'];

     public function subprogram()
    {
        return $this->belongsTo(Subprogram::class, 'id_subprogram','id');
    }

      public function penggunas()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }
}
