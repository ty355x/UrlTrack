<?php

namespace App\AppModules\TrackedUrlUpdate;

use Carbon\Carbon;
use App\Query\TrackedUrlQuery;

class TrackedUrlToUpdateFinder {
    const UPDATE_ALL_URLS = false;
    private $urlQuery;
    
    public function __construct(TrackedUrlQuery $urlQuery) {
        $this->urlQuery = $urlQuery;
    }
    
    public function findUrlsToUpdate(): array {
        $this->markUrlsToUpdate();
        return $this->urlQuery->findMarkedUrls()->all();
    }
    
    private function markUrlsToUpdate(): void {
        if (self::UPDATE_ALL_URLS) {
            $this->urlQuery->markAllUrls();
         } else {
            $this->markIntervalUrlsToUpdate();
            $this->markTimeUrlsToUpdate();
        }
    }
    
    private function markIntervalUrlsToUpdate(): void {
        $this->urlQuery->markIntervalUrlsToUpdate();
    }
    
    private function markTimeUrlsToUpdate(): void {
        $cuttoffTime = Carbon::now()->subMinutes(6);
        $now = Carbon::now('America/New_York');
        $endTime = $now->format('H:i:00');
        $startTime = $now->subMinutes(5)->format('H:i:00');
        
        if ($startTime < $endTime) {
            $this->urlQuery->markTimeUrlsToUpdate($startTime, $endTime, $cuttoffTime);
        } else {
            $this->urlQuery->markTimeUrlsToUpdateNearMidnight($startTime, $endTime, $cuttoffTime);
        }
    }
}
