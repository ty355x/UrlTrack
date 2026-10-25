<?php

namespace App\Query;

use DB;
use Illuminate\Support\Collection;
use App\Model\TrackedUrl;

class TrackedUrlQuery {
    public function findOrFail(int $id): TrackedUrl {
        return TrackedUrl::findOrFail($id);
    }
    
    public function findTrashedOrFail(int $id): TrackedUrl {
        return TrackedUrl::onlyTrashed()->where('id', $id)->firstOrFail();
    }
    
    public function findAllForUser(int $userId): Collection {
       return TrackedUrl::withCount('images')->where('created_by', $userId)->orderBy('domain')->orderBy('url')->orderBy('created_at')->get();
    }
    
    public function findAllTrashedForUser(int $userId): Collection {
       return TrackedUrl::onlyTrashed()->withCount('images')->where('created_by', $userId)->orderBy('domain')->orderBy('url')->orderBy('created_at')->get();
    }
    
    public function findMarkedUrls(): Collection {
        $sql = 'select tu.* from tracked_urls tu inner join users u on tu.created_by = u.id ' .
            'where u.is_disabled = 0 and u.deleted_at is null and tu.is_disabled = 0 and tu.deleted_at is null and tu.is_updating = 1';
        
        $result = DB::select($sql);
        return TrackedUrl::hydrate($result);
    }
    
    public function markAllUrls(): void {
        DB::statement('update tracked_urls set is_updating = 1');
    }
    
    public function markIntervalUrlsToUpdate(): void {
        $sql = 'update tracked_urls tu inner join users u on tu.created_by = u.id ' .
            'set tu.is_updating = 1, tu.update_attempts = 0 ' .
            'where u.is_disabled = 0 and u.deleted_at is null and tu.is_disabled = 0 and tu.deleted_at is null and tu.is_updating = 0 and ' .
            'tu.update_method = "interval" and tu.update_interval_mins is not null and tu.update_interval_mins > 0 and ' .
            '(tu.last_updated_at is null or adddate(tu.last_updated_at, interval tu.update_interval_mins minute) <= utc_timestamp())';
            
        DB::statement($sql);
    }
    
    public function markTimeUrlsToUpdate(string $startTime, string $endTime, string $cuttoffTime): void {
        $sql = 'update tracked_urls tu inner join users u on tu.created_by = u.id ' .
            'set tu.is_updating = 1, tu.update_attempts = 0 ' .
            'where u.is_disabled = 0 and u.deleted_at is null and tu.is_disabled = 0 and tu.deleted_at is null and tu.is_updating = 0 ' .
            'and tu.update_method = "time" and tu.update_time is not null ' .
            'and (tu.last_updated_at is null or tu.last_updated_at < :cuttoffTime) ' .
            'and tu.update_time between :startTime and :endTime';
           
        DB::statement($sql, ['startTime' => $startTime, 'endTime' => $endTime, 'cuttoffTime' => $cuttoffTime]);
    }
    
    public function markTimeUrlsToUpdateNearMidnight(string $startTime, string $endTime, string $cuttoffTime): void {
        $sql = 'update tracked_urls tu inner join users u on tu.created_by = u.id ' .
            'set tu.is_updating = 1, tu.update_attempts = 0 ' .
            'where u.is_disabled = 0 and u.deleted_at is null and tu.is_disabled = 0 and tu.deleted_at is null and tu.is_updating = 0 ' .
            'and tu.update_method = "time" and tu.update_time is not null ' .
            'and (tu.last_updated_at is null or tu.last_updated_at < :cuttoffTime) and ' .
            '(tu.update_time between :startTime and "23:59:00" or tu.update_time between "00:00:00" and :endTime)';
           
        DB::statement($sql, ['startTime' => $startTime, 'endTime' => $endTime, 'cuttoffTime' => $cuttoffTime]);
    }
}
