<?php

namespace App\Api\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use App\Api\Exceptions\ErrorException;
use App\Api\Exceptions\SuccessException;
use \Illuminate\Auth\AuthenticationException;
use App\Api\Exceptions\LoginException;
use App\Api\Exceptions\TokenException;

class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e) {
        // parent::report($e);
    }

    /**
     * Все исплючения выброшенные перехватываются, и рендерится json клиенту, со
     * статусом ошибки, и статус сообщением переданных из места выброса исключения
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e) {

        $response = [
            'message' => (string) $e->getMessage(),
            'status' => 400
        ];

        if ($e instanceof HttpException) {
            $response['message'] = Response::$statusTexts[$e->getStatusCode()];
            $response['status'] = $e->getStatusCode();
        } else if ($e instanceof ModelNotFoundException) {
            $response['message'] = Response::$statusTexts[Response::HTTP_NOT_FOUND];
            $response['status'] = Response::HTTP_NOT_FOUND;
        } else if ($e instanceof AuthenticationException) {
            $response['message'] = Response::$statusTexts[Response::HTTP_UNAUTHORIZED];
            $response['status'] = Response::HTTP_UNAUTHORIZED;
        } else if ($e instanceof ErrorException) {
            return $e->render($request);
        } else if ($e instanceof SuccessException) {
            return $e->render($request);
        } else if ($e instanceof LoginException) {
            return $e->render($request);
        } else if ($e instanceof TokenException) {
            return $e->render($request);
        }
        return response()->json(['error' => $response], $response['status']);
    }

}
