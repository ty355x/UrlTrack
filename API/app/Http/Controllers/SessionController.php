<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppModules\Auth\LoginService;
use App\AppModules\Auth\SessionService;

class SessionController extends Controller {
    private $loginService;
    private $sessionService;
    
    public function __construct(LoginService $loginService, SessionService $sessionService) {
        $this->loginService = $loginService;
        $this->sessionService = $sessionService;
    }
    
    public function getSession(): array {
        return $this->sessionService->getCurrentSession();
    }
    
    public function login(Request $req): array {
        $email = $req->get('email');
        $password = $req->get('password');
        return $this->loginService->attemptLogin($email, $password);
    }
    
    public function logout(): array {
        return $this->loginService->logout();
    }
}
