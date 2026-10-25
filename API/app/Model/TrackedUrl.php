<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AppModules\TrackedUrls\UpdateTimeDescription;
use App\Model\TrackedUrlImage;

class TrackedUrl extends Model {
    use SoftDeletes;
    
    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'is_disabled' => 'boolean',
        'update_interval' => 'integer',
        'is_updating' => 'boolean',
        'update_attempts' => 'integer',
    ];
    
    protected $appends = [
        'update_time_formatted',
        'update_time_description',
    ];
    
    public function images() {
        return $this->hasMany(TrackedUrlImage::class);
    }
    
    public function getUpdateTimeFormattedAttribute(): ?string {
        $updateTime = $this->attributes['update_time'];
        return $updateTime ? Carbon::create($updateTime)->format('g:i A') : null;
    }
    
    public function getUpdateTimeDescriptionAttribute(): string {
        return UpdateTimeDescription::makeUpdateTimeDescription(
            $this->attributes['update_method'], $this->attributes['update_time'], $this->attributes['update_interval_mins']);
    }
}
