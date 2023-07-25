<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class book extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected $primarykey = 'id';
    protected $fillable = [
        'judul',
        'sinopsis',
        'harga',
        'cover',
        'tahunTerbit',
        'user_id',
        'kategori_id'
    ];

    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'kategori_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(user::class,'user_id','id');
    }
}
