<?php

namespace App\Query;

use App\Model\User;

class UserQuery {
    public function findOrFail(int $id): ?User {
        return User::findOrFail($id);
    }
    
    public function findAll() {
        return User::query()->orderBy('email')->get();
    }
    
    public function findByEmail(string $email): ?User {
        return User::where('email', $email)->first();
    }
    
    public function findByApiToken(string $token): ?User {
        return User::where('api_token', $token)->first();
    }
}
