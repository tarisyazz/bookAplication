<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategori extends Model
{
    use HasFactory;
    protected $table = 'kategoris';
    protected $primaryKey = 'id';
    protected $fillable = [
        'namaKategori'
    ];
    
    public function buku()
    {
        return $this->hasMany(book::class);
    }
}
