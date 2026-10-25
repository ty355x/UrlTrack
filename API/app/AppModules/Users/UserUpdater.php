<?php

namespace App\AppModules\Users;

use Hash;
use App\Model\User;
use App\Query\UserQuery;

class UserUpdater {
    private $userQuery;
    
    public function __construct(UserQuery $userQuery) {
        $this->userQuery = $userQuery;
    }

    public function updateUser(array $data): array {
        $isCreating = !isset($data['id']);
        
        if ($isCreating) {
            $errors = UserValidator::makeCreateValidator()->validate($data);
        } else {
            $errors = UserValidator::makeUpdateValidator()->validate($data);
        }
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        } 
        
        $this->save($data, $isCreating);
        return ['success' => true];
    }
    
    private function save(array $data, bool $isCreating): void {
        $user = $isCreating ? new User() : $this->userQuery->findOrFail($data['id']);
        
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->is_disabled = $data['is_disabled'] ?? false;
        
        if (isset($data['password'])) {
            $user->password_hash = Hash::make($data['password']);
        }
        
        $user->save();
    }
}
