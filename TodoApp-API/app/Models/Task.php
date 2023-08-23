<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name','tags','status','order']; 
    protected $casts = [
        'tags' => 'array'
    ];

    public function tags()
    {
        return $this->hasMany('App\Models\Tag');
    }
    
    // use HasFactory;
}