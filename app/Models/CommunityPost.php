<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    protected $fillable = [
        'user_id',
        'scan_result_id',
        'caption',
        'likes_count',
    ];

    protected $casts = [
        'likes_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scanResult()
    {
        return $this->belongsTo(ScanResult::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
}
