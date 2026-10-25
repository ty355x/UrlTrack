<?php

namespace App\Http\Middleware;

use Closure;
use App\AppModules\Logging\Log;

class AddDebugLog {
    public function handle($req, Closure $next) {
        if (!config('app.debug')) {
            return $next($req);
        }

        $res = $next($req);
        $content = $res->getOriginalContent();

        if (gettype($content) === 'array') {
            $log = Log::getLog();

            if ($log) {
                $content['_log'] = $log;
                $res->setContent(json_encode($content));
            }
        }

        return $res;
    }
}
