<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\AppModules\Auth\AppAuth;
use App\AppModules\TrackedUrls\TrackedUrlPermDeleterService;
use App\AppModules\TrackedUrls\TrackedUrlUpdaterService;
use App\Http\Controllers\Controller;
use App\Query\TrackedUrlQuery;

class TrashedTrackedUrlController extends Controller {
    private $permDeleter;
    private $trackedUrlQuery;
    private $trackedUrlUpdater;
        
    public function __construct(TrackedUrlPermDeleterService $permDeleter, TrackedUrlQuery $trackedUrlQuery, TrackedUrlUpdaterService $trackedUrlUpdater) {
        $this->permDeleter = $permDeleter;
        $this->trackedUrlQuery = $trackedUrlQuery;
        $this->trackedUrlUpdater = $trackedUrlUpdater;
    }
    
    public function index(): array {
        $user = AppAuth::getUser();
        return ['tracked_urls' => $this->trackedUrlQuery->findAllTrashedForUser($user->id)];
    }
    
    public function destroy(int $id): array {
        $url = $this->trackedUrlQuery->findTrashedOrFail($id);
        $user = AppAuth::getUser();
        abort_if($url->created_by !== $user->id, 403);
        return $this->permDeleter->permDeleteUrl($url);
    }
    
    public function restore(Request $req): array {
        $id = $req->input('id');
        $url = $this->trackedUrlQuery->findTrashedOrFail($id);
        $user = AppAuth::getUser();
        abort_if($url->created_by !== $user->id, 403);
        $url->restore();
        return ['success' => true];
    }
}
