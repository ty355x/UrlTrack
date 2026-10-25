<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\AppModules\Auth\AppAuth;
use App\AppModules\TrackedUrls\TrackedUrlUpdaterService;
use App\Http\Controllers\Controller;
use App\Query\TrackedUrlQuery;

class TrackedUrlController extends Controller {
    private $trackedUrlQuery;
    private $trackedUrlUpdater;
        
    public function __construct(TrackedUrlQuery $trackedUrlQuery, TrackedUrlUpdaterService $trackedUrlUpdater) {
        $this->trackedUrlQuery = $trackedUrlQuery;
        $this->trackedUrlUpdater = $trackedUrlUpdater;
    }
    
    public function index(): array {
        $user = AppAuth::getUser();
        return ['tracked_urls' => $this->trackedUrlQuery->findAllForUser($user->id)];
    }
    
    public function store(Request $req): array {
        $data = $req->all();
        $user = AppAuth::getUser();
        return $this->trackedUrlUpdater->save($data, $user);
    }
    
    public function destroy(int $id): array {
        $url = $this->trackedUrlQuery->findOrFail($id);
        $user = AppAuth::getUser();
        
        abort_if($url->created_by !== $user->id, 403);
        
        $url->delete();
        return ['success' => true];
    }
}
