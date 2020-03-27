<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
//use Laravel\Passport\Exceptions\OAuthServerException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Laravel\Passport\Exceptions\OAuthServerException as OAuthServerException2;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        OAuthServerException::class,
        NotFoundHttpException::class,
        ConstrainException::class
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {


        if ($exception instanceof AuthorizationException) {
            return response()->json((['status' => false, 'message' => 'no_privileges']), Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json((['status' => false, 'message' => 'not_authorized']), Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json((['status' => false, 'message' => 'resource_not_found']), Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof OAuthServerException || $exception instanceof OAuthServerException2) {
            return response()->json((['status' => false, 'message' => 'wrong_password']), Response::HTTP_OK);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json((['status' => false, 'message' => 'method_not_allowed']), Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof ConstrainException) {
            //1451 = delete or update a parent row: a foreign key constraint fails
            return response()->json((['status' => false, 'message' => 'database_'.$exception->getMessage().'_'.$exception->getCode()]), Response::HTTP_BAD_REQUEST);
        }

        //@todo sql exception
        if(config('app.debug') === true){
            return response()->json((['status' => false, 'message' => $exception->getMessage(), 'type' => get_class($exception)]), 500);
        }
        else{
            return response()->json((['status' => false, 'message' => 'unexpected_error']), 500);
        }


    }
}
