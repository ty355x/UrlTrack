<?php

namespace App\AppModules\Users;

use Validator;
use Illuminate\Validation\Rule;

class UserValidator {
    private $isCreating = true;
    private $validRoles = ['admin', 'member'];
        
    public static function makeCreateValidator(): UserValidator {
        $validator = new UserValidator();
        $validator->isCreating = true;
        return $validator;
    }
    
    public static function makeUpdateValidator(): UserValidator {
        $validator = new UserValidator();
        $validator->isCreating = false;
        return $validator;
    }
    
    public function validate(array $data): array {
        $rules = $this->createRules($data);
        $validator = Validator::make($data, $rules);
        return $validator->fails() ? $validator->errors()->toArray() : [];
    }
    
    private function createRules(array $data): array {
        $rules = [
            'email' => 'required',
            'role' => ['required', Rule::in($this->validRoles)]
        ];
        
        if ($this->isCreating) {
            $rules['email'] = 'required|min:5|unique:users';
        } else {
            $rules['email'] = [
                'required',
                'min:5',
                Rule::unique('users')->ignore($data['id'])
            ];
        }
        
        if ($this->isCreating || isset($data['password'])) {
            $rules['password'] = 'required|between:6,24';
        }
        
        return $rules;
    }
}
