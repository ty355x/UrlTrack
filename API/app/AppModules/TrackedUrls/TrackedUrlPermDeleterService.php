<?php

namespace App\AppModules\TrackedUrls;

use App\Model\TrackedUrl;
use App\Query\TrackedUrlImageQuery;

class TrackedUrlPermDeleterService {
    private $imageQuery;
    
    public function __construct(TrackedUrlImageQuery $imageQuery) {
        $this->imageQuery = $imageQuery;
    }
    
    
    public function permDeleteUrl(TrackedUrl $url): array {
        $images = $this->imageQuery->findForUrl($url->id);
        
        foreach ($images as $image) {
            $imagePath = $image->getImagePath();
            $tnPath = $image->getThumbnailPath();
            
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            if (file_exists($tnPath)) {
                unlink($tnPath);
            }
            
            $image->forceDelete();
        }
        
        $url->forceDelete();
        return ['success' => true];
    }
}
