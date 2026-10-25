<?php

namespace App\AppModules\TrackedUrlUpdate;

use DB;
use stdClass;
use App\AppModules\Core\AppStore;
use App\AppModules\Logging\Log;
use App\Query\TrackedUrlQuery;

class TrackedUrlUpdater {
    const URL_TO_UPDATE_FILE_PATH = 'temp/urls-to-update.json';
    
    private $imageDownloader;
    private $trackedUrlToUpdateMarker;
    private $urlQuery;
    
    public function __construct(TrackedUrlImageDownloader $imageDownloader, TrackedUrlToUpdateFinder $trackedUrlUpdateMarker, 
        TrackedUrlQuery $urlQuery) {
        $this->imageDownloader = $imageDownloader;
        $this->trackedUrlToUpdateMarker = $trackedUrlUpdateMarker;
        $this->urlQuery = $urlQuery;
    }
    
    public function updateTrackedUrls(): void {
        Log::debug('Updating tracked URLs');
        
        $shouldUpdate = DB::transaction(function() {
           $isUpdating = AppStore::getTrackedUrlUpdaterIsUpdating();
        
            if ($isUpdating === '1') {
                return false;
            }

            AppStore::setTrackedUrlUpdaterIsUpdating('1');
            return true;
        });
        
        if (!$shouldUpdate) {
            Log::debug('Aborting tracked URL update: an update is already in progress');
            return;
        }
        
        try {
            $this->updatePendingTrackedUrls();
        } catch (Exception $e){
            Log::debug($e->getMessage());
        } finally {
            AppStore::setTrackedUrlUpdaterIsUpdating('0');
            Log::debug('Tracked URL update completed');
        }
    }
    
    private function updatePendingTrackedUrls(): void {
        $urls = $this->trackedUrlToUpdateMarker->findUrlsToUpdate();
        Log::debug('Found ' . count($urls) . ' URLs to update');
        
        if (empty($urls)) {
            return;
        }
        
        try {
            $this->makeUrlToUpdateFile($urls);
            $this->imageDownloader->downloadImages($urls);
        } finally {
            $this->clearUrlToUpdateFile();
        }
    }
    
    private function makeUrlToUpdateFile(array $urls): void {
        $urlData = [];
        
        foreach ($urls as $url) {
            $urlEntry = new stdClass;
            $urlEntry->id = $url->id;
            $urlEntry->url = $url->url;
            $urlData[] = $urlEntry;
        }
        
        file_put_contents(storage_path(self::URL_TO_UPDATE_FILE_PATH), json_encode($urlData));
    }
    
    private function clearUrlToUpdateFile() {
        file_put_contents(storage_path(self::URL_TO_UPDATE_FILE_PATH), '[]');
    }
}
