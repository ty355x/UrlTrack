<?php

namespace App\AppModules\TrackedUrlUpdate;

use App\AppModules\Logging\Log;
use App\Exceptions\TrackedUrlScriptExecutionException;

class TrackedUrlScriptExecutor {
    public function runScript(): void {
        $output = [];
        $returnVal = 0;
        
        Log::debug('Executing update tracked URL script');
        chdir(realpath(app_path('..')));
        
        $env = config('app.env');
        
        if ($env === 'production') {
            $cmd = "sudo -u chromeuser node ./storage/scripts/update-tracked-urls.js 2>&1";
        } else {
            $cmd = "node ./storage/scripts/update-tracked-urls.js 2>&1";
        }
        
        exec($cmd, $output, $returnVal);
        $outputLog = '';
        
        foreach ($output as $outputLine) {
            $outputLog .= $outputLine . "\n";
            Log::debug($outputLine);
            file_put_contents(storage_path('logs/tracked-url-updater-script.log'), $outputLog);
        }
        
        if ($returnVal !== 0) {
            throw new TrackedUrlScriptExecutionException('Tracked URL script returned an invalid response');
        } 
    }
}
