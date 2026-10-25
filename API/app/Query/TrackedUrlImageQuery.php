<?php

namespace App\Query;

use Illuminate\Support\Collection;
use App\Model\TrackedUrlImage;

class TrackedUrlImageQuery {
    public function findWithUrlOrFail(int $id): TrackedUrlImage {
        return TrackedUrlImage::with('trackedUrl')->with('id', $id)->firstOrFail();
    }
    
    public function findForUrl(int $urlId): Collection {
        return TrackedUrlImage::where('tracked_url_id', $urlId)->orderBy('created_at', 'desc')->get();
    }
}
