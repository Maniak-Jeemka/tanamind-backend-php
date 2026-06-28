<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanResult extends Model
{
    protected $fillable = [
        'user_id',
        'disease_id',
        'image_path',
        'disease_label',
        'disease_confidence',
        'severity_label',
        'severity_confidence',
        'notes',
        'is_shared',
    ];

    /**
     * Otomatis tambahkan image_url ke JSON output.
     */
    protected $appends = ['image_url'];

    /**
     * Cast tipe data kolom.
     */
    protected $casts = [
        'disease_confidence'  => 'float',
        'severity_confidence' => 'float',
        'is_shared'           => 'boolean',
    ];

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: URL absolut ke gambar scan.
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}