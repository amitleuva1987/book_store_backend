<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable; 

    protected $fillable = ['title','author','genre','description','image','isbn','published','publisher'];

    protected $casts = [
//        'published' => 'date:j F Y',
    ];
}
