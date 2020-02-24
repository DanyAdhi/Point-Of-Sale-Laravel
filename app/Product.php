<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;

class Product extends Model
{
    protected $fillable = ['code','name', 'description','stok', 'price', 'category_id', 'photo'];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
