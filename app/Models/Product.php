<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // jika primary key bukan 'id', tentukan di sini 
    protected $primaryKey = 'product_id'; 
//fields yang boleh diisi massal 
    protected $fillable = [ 
        'name', 
        'description', 
        'category_id', 
        'price', 
        'img', 
        'stock', 
        'slug' 
    ]; 
 
    public function category() 
    { 
        // relasi ke tabel categories 
        return $this->belongsTo(Category::class, 'category_id'); 
    } 
}
