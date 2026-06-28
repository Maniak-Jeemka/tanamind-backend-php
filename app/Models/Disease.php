<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'thumbnail'];

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}