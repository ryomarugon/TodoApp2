<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name']; 
    public function tasks()
    {
        return $this->belongsTo('App\Models\Task');
    }
    // use HasFactory;
}
