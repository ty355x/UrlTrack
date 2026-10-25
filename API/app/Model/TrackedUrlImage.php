<?php

namespace App\Model;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackedUrlImage extends Model {
    use SoftDeletes;
    
    protected $casts = [
        'id' => 'integer',
        'tracked_url_id' => 'integer',
    ];    
    
    protected $hidden = [
        'path',
        'path_tn',
        'filename_hash',
    ];
    
    protected $appends = [
        'image_url',
        'image_url_tn',
    ];
    
    public function trackedUrl() {
        return $this->belongsTo(TrackedUrl::class)->withTrashed();
    }
    
    public function getImageUrlAttribute(): string {
        $appUrl = config('app.url');
        
        if (!$appUrl || !$this->trackedUrl) {
            return '';
        } else {
            return $appUrl . '/assets/tracked-url-images/' . $this->image_path . '/' . $this->image_filename . '.jpg';
        }
    }
    
    public function getImageUrlTnAttribute(): string {
       $appUrl = config('app.url');
        
        if (!$appUrl  || !$this->trackedUrl) {
            return '';
        } else {
            return $appUrl . '/assets/tracked-url-images/' . $this->image_path . '/' . $this->image_filename . '-tn.jpg';
        }
    }
    
    public function getImagePath() {
        if (!$this->image_path || !$this->image_filename) {
            throw new Exception('Unable to determine image path');
        }
        
        return public_path('assets/tracked-url-images/' . $this->image_path . '/' . $this->image_filename . '.jpg');
    }
    
    public function getThumbnailPath() {
        if (!$this->image_path || !$this->image_filename) {
            throw new Exception('Unable to determine thumbnail path');
        }
        
        return public_path('assets/tracked-url-images/' . $this->image_path . '/' . $this->image_filename . '-tn.jpg');
    }
}
