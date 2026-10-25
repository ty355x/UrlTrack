<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    
    public function render($req, Throwable $e) {
        // If the request is not a JSON or AJAX request, let the framework handle the exception.
        if (!($req->wantsJson() || $req->ajax())) {
            return parent::render($req, $e);
        }
        
        if ($e instanceof NotFoundHttpException) {
            return parent::render($req, $e);
        } 
        
        if ($e instanceof HttpException) {
            $error = ['error_message' => $e->getMessage()];
            $statusCode = $e->getStatusCode();
        } else {
            $error = ['error_message' => 'An error occurred while processing your request'];
            $statusCode = 500;
            
            if (config('app.debug')) {
                $error['_error_message_debug'] = $e->getMessage();
            }
        }
        
        return response()->json($error, $statusCode);
    }
}
