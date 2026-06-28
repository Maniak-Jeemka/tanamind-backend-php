<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = ['disease_id', 'severity', 'action', 'prevention'];

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}