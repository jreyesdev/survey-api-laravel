<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        $this->renderable(function (HttpException $e, $request) {
            // $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('/*') || $e->getStatusCode() === 404) {
                return response()->json([
                    'message' => 'Not found.'
                ], 404);
            }

            if ($e->getStatusCode() >= 400) {
                $msg = '';
                switch ($e->getStatusCode()) {
                    case 405:
                        $msg = $e->getMessage();
                        break;

                    default:
                        $msg = 'Bad request.';
                        break;
                }
                return response()->json([
                    'message' => $msg
                ], $e->getStatusCode());
            }
        });
    }
}
