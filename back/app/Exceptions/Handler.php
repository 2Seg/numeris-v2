<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\AuthorizationException as CustomAuthorizationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'errors' => [trans('errors.403')]
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        if ($exception instanceof CustomAuthorizationException) {
            return response()->json([
                'errors' => [$exception->getMessage()]
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            return response()->json([
                'errors' => [trans('errors.404')]
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'errors' => [trans('errors.405')]
            ],JsonResponse::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'errors' => [trans('errors.429')]
            ],JsonResponse::HTTP_TOO_MANY_REQUESTS);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['errors' => [trans('errors.auth')]], JsonResponse::HTTP_FORBIDDEN);
    }
}
