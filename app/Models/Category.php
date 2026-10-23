<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // jika primary key bukan 'id', tentukan di sini 
    protected $primaryKey = 'category_id'; 
    //fields yang boleh diisi massal 
    protected $fillable = ['category']; 
 
    public function products() 
    { 
        // relasi ke tabel products 
        return $this->hasMany(Product::class, 'category_id'); 
    }
}
