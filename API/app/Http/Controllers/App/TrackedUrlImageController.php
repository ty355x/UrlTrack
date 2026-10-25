<?php

namespace App\Http\Controllers\App;

use App\AppModules\Auth\AppAuth;
use App\Http\Controllers\Controller;
use App\Query\TrackedUrlQuery;
use App\Query\TrackedUrlImageQuery;

class TrackedUrlImageController extends Controller {
    private $imageQuery;
    private $urlQuery;
    
    public function __construct(TrackedUrlQuery $urlQuery, TrackedUrlImageQuery $imageQuery) {
        $this->urlQuery = $urlQuery;
        $this->imageQuery = $imageQuery;
    }

    public function findForUrl(int $id): array {
        $url = $this->urlQuery->findOrFail($id);
        $user = AppAuth::getUser();
        abort_if($url->created_by !== $user->id, 403);
        return ['url' => $url->url, 'images' => $this->imageQuery->findForUrl($id)];
    }
    
    public function findForTrashedUrl(int $id): array {
        $url = $this->urlQuery->findTrashedOrFail($id);
        $user = AppAuth::getUser();
        abort_if($url->created_by !== $user->id, 403);
        return ['url' => $url->url, 'images' => $this->imageQuery->findForUrl($id)];
    }
}
