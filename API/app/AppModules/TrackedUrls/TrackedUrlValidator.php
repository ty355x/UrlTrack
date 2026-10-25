<?php

namespace App\AppModules\TrackedUrls;

use Validator;
use Illuminate\Validation\Rule;

class TrackedUrlValidator {
    private $isCreating = false;
    
    public static function makeCreateValidator(): TrackedUrlValidator {
        $validator = new TrackedUrlValidator();
        $validator->isCreating = true;
        return $validator;
    }
    
    public static function makeUpdateValidator(): TrackedUrlValidator {
        $validator = new TrackedUrlValidator();
        $validator->isCreating = false;
        return $validator;
    }
    
    public function validate(array $data, int $userId): array {
        $rules = $this->createRules($data, $userId);
        $customErrorMessages = [
            'unique' => 'The specified :attribute already exists in the database.',
        ];
        $validator = Validator::make($data, $rules, $customErrorMessages);
        return $validator->fails() ? $validator->errors()->toArray() : [];
    }
    
    private function createRules(array $data, int $userId): array {
        $rules = [
            'update_method' => ['required', Rule::in(UpdateMethod::$all)],
        ];
            
        if ($this->isCreating) {
            $rules['url'] = ['required', 'active_url', 'max:1024', 
                Rule::unique('tracked_urls')->where(function($query) use ($data, $userId) { return $this->validateUniqueUrlHash($query, $data, $userId); })];
        } else {
            $rules['url'] = ['required', 'active_url', 'max:1024', 
                Rule::unique('tracked_urls')->where(function($query) use ($data, $userId) { return $this->validateUniqueUrlHash($query, $data, $userId); })];
        }
        
        if (isset($data['update_method'])) {
            if ($data['update_method'] === UpdateMethod::INTERVAL) {
                $this->addIntervalRules($data, $rules);
            } elseif ($data['update_method'] === UpdateMethod::SPECIFIC_TIME) {
                $rules['update_time'] = 'required';
            }
        }
        
        return $rules;
    }
    
    private function addIntervalRules(array $data, array &$rules) {
        if (!isset($data['update_interval_hours']) && !isset($data['update_interval_mins'])) {
            $rules['update_interval_mins'] = 'required|integer|min:0';
        }
        
        if (isset($data['update_interval_hours'])) {
            $rules['update_interval_hours'] = 'integer|min:0';
        }
        
        if (isset($data['update_interval_mins'])) {
            $rules['update_interval_mins'] = 'integer|min:0';
        }                
    }
    
    private static function validateUniqueUrlHash($query, array $data, int $userId) {
        $query->where('url_hash', $data['url_hash'])
            ->where('created_by', $userId)
            ->whereNull('deleted_at');
        
        if (isset($data['id'])) {
            $query->where('id', '!=', $data['id']);
        }
        
        return $query;
    }
}
