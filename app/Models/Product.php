<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'is_trendy',
        'is_available',
        'price',
        'amount',
        'discount',
        'images',
        'public_id',
        'brand_id',
        'category_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class,'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'user_id');
    }
}
