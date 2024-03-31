<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;

class Category extends Model
{
    use HasFactory, MediaAlly;

    protected $fillable = [
        'name',
        'image',
        'public_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
