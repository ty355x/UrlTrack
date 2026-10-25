<?php

namespace App\AppModules\TrackedUrls;

use Carbon\Carbon;
use App\Model\TrackedUrl;
use App\Model\User;
use App\Query\TrackedUrlQuery;

class TrackedUrlUpdaterService {
    private $trackedUrlQuery;
    
    public function __construct(TrackedUrlQuery $trackedUrlQuery) {
        $this->trackedUrlQuery = $trackedUrlQuery;
    }
    
    public function save(array $data, User $user): array {
        $isCreating = !isset($data['id']);
        $data['url_hash'] = isset($data['url']) ? md5($data['url']) : null;
        $validationErrors = $this->validateTrackedUrl($data, $user->id, $isCreating);
        
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        } elseif ($isCreating) {
            $trackedUrlId = $this->createTrackedUrl($data, $user);
            return ['success' => true, 'id' => $trackedUrlId];
        } else {
            $trackedUrlId = $this->updateTrackedUrl($data['id'], $data, $user);
            return ['success' => true, 'id' => $trackedUrlId];
        }
    }
    
    private function validateTrackedUrl(array $data, int $userId, bool $isCreating): array {
        $validator = $isCreating ? TrackedUrlValidator::makeCreateValidator() : TrackedUrlValidator::makeUpdateValidator();
        return $validator->validate($data, $userId);
    }
    
    private function createTrackedUrl(array $data, User $user): int {
        return $this->saveTrackedUrl(new TrackedUrl(), $data, $user);
    }
    
    private function updateTrackedUrl(int $id, array $data, user $user): int {
        $url = $this->trackedUrlQuery->findOrFail($id);
        abort_if ($url->created_by !== $user->id, 403);
        return $this->saveTrackedUrl($url, $data, $user);
    }
    
    private function saveTrackedUrl(TrackedUrl $url, array $data, User $user): int {
        $url->url = $data['url'];
        $url->url_hash = $data['url_hash'];
        $url->domain = $this->determineDomain($data['url']);
        $url->created_by = $user->id;
        $url->is_disabled = $data['is_disabled'] ?? false;
        $url->update_method = $data['update_method'];
        $url->update_time = null;
        $url->update_interval_mins = null;
        
        if ($data['update_method'] === UpdateMethod::SPECIFIC_TIME) {
            $url->update_time = Carbon::create($data['update_time']);
        } elseif ($data['update_method'] === UpdateMethod::INTERVAL) {
            $hours = isset($data['update_interval_hours']) ? intval($data['update_interval_hours']) : 0;
            $mins = isset($data['update_interval_mins']) ? intval($data['update_interval_mins']) : 0;
            $url->update_interval_mins = ($hours * 60) + $mins;
        }  
        
        $url->save();
        return $url->id;
    }
    
    private function determineDomain(string $url): string {
        return parse_url($url)['host'];
    }
}
