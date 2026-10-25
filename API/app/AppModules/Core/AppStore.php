<?php

namespace App\AppModules\Core;

use DB;

class AppStore {
    public static function getTrackedUrlUpdaterIsUpdating(): ?string {
        return self::readValue('tracked_url_updater.is_updating'); 
    }
    
    public static function setTrackedUrlUpdaterIsUpdating(string $isUpdating): void {
        self::saveValue('tracked_url_updater.is_updating', $isUpdating);
    }
    
    private static function readValue(string $key, string $account = null): ?string {
        $result = DB::select('select entry_value from app_store where entry_key=:key', ['key' => $key]);
        return !empty($result) ? $result[0]->entry_value : null;
    }
    
    private static function saveValue(string $key, string $value): void {
        $result = DB::select('select id from app_store where entry_key=:key', ['key' => $key]);
        $id = !empty($result) ? $result[0]->id : null;
        
        if ($id !== null) {
            DB::statement('update app_store set entry_value=:value where id=:id', ['value' => $value, 'id' => $id]);
        } else {
            DB::statement('insert into app_store (entry_key, entry_value) values (:key, :value)', ['key' => $key, 'value' => $value]);
        }
    }
}
