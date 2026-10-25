<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\AppModules\Users\UserUpdater;
use App\Http\Controllers\Controller;
use App\Query\UserQuery;

class UserController extends Controller {
    private $userQuery;
    private $userUpdater;
        
    public function __construct(UserQuery $userQuery, UserUpdater $userUpdater) {
        $this->userQuery = $userQuery;
        $this->userUpdater = $userUpdater;
    }
    
    public function index(): array {
        return ['users' => $this->userQuery->findAll()];
    }
    
    public function store(Request $req): array {
        return $this->userUpdater->updateUser($req->all());
    }
    
    public function destroy(int $id): array {
        $user = $this->userQuery->findOrFail($id);
        $user->delete();
        return ['success' => true];
    }
}
