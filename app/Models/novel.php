<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class novel extends Model
{
    use HasFactory;
    protected $table = "novels";
    protected $primarykey = "id";
    protected $fillable = [
        'id',
        'penulis_id',
        'judul',
        'sinopsis',
        'harga',
        'status'
    ];
}
