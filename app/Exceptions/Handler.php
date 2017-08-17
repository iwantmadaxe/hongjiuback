<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
     * 定义json类型的exception返回
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //验证类的Exception
        if($exception instanceof ValidationException){
            return $exception->getResponse();
        }
        //预处理Exception
        $exception =  $this->prepareException($exception);
        if($request->expectsJson()){
            if($exception instanceof UnauthorizedHttpException){
                return response()->json([
                    'code'=>$exception->getCode(),
                    'status_code'=>$exception->getStatusCode(),
                    'message'=>$exception->getMessage()
                ],$exception->getStatusCode());
            }else{
                return response()->json([
                    'code' => $exception->getCode().'000',
                    'status_code' => $exception->getCode(),
                    'message'=>$exception->getMessage(),
                ],$exception->getCode());
            }
        }
        return parent::render($request, $exception);
    }

    /**
     * 自定义处理部分exception
     * @param Exception $e
     * @return Exception
     */
    protected function prepareException(Exception $e)
    {
        //身份验证失败
        if($e instanceof UnauthorizedHttpException){
            return new UnauthorizedHttpException('jwt-auths','身份验证失败，请重新登录',null,401000);
        }
        return parent::prepareException($e);
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
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
