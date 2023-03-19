<?php

namespace App\Exceptions;

use App\Exceptions\RestApiValidationErrorException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \App\Exceptions\RestApiValidationErrorException::class,
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
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
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($this->isApi($request)) {
            return $this->restRender($request, $exception);
        }

        return parent::render($request, $exception);
    }

    public function restRender($request, Throwable $exception)
    {
        if ($exception instanceof RestApiValidationErrorException) {
            $errorData = $exception->getErrors();

            return response()->json($errorData, 422);
        } elseif ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'Not Found',
                'errors' => ['Not Found'],
            ], 404);
        } elseif ($exception instanceof HttpException) {
            return response()->json([
                'status' => 'error',
                'status_code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
                'errors' => [$exception->getMessage()],
            ], $exception->getStatusCode());
        }

        if (!(bool) config('app.debug') && !$exception instanceof AuthenticationException) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => 'Server Error',
                'errors' => ['Server Error'],
            ], 500);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isApi($request)) {
            return response()->json([
                'status' => 'error',
                'status_code' => 401,
                'message' => 'Unauthenticated',
                'errors' => ['Unauthenticated'],
            ], 401);
        }

        return redirect()->guest(route('login'));
    }

    private function isApi($request)
    {
        return $request->expectsJson() || $request->is('api*');
    }
}
