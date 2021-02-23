<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {        
        // if($exception instanceof AuthenticationException){
        //     $guard = Arr::get($exception->guards(), 0);

        //     return redirect(route( ($guard == 'admin')?'admin.login':'login' ));
        // }
        // return parent::render($request, $exception);
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            return response()->json(['User have not permission for this page access.']);
        }
     
        return parent::render($request, $exception);
    }

        protected function unauthenticated($request, AuthenticationException $exception)
        {
            // return $request->expectsJson()
            //             ? response()->json(['message' => $exception->getMessage()], 401)
            //             : redirect()->guest($exception->redirectTo() ?? route('login'));



        //         if($request->expectsJson())
        //         {
        //             return response()->json(['message' => $exception->getMessage()], 401);
        //         }
                        
        //                 $guard = Array($exception->guards(),0);

        //                 switch ($guard) 
        //                 {
        //                     case 'admin':
        //                        $login  = 'admin.login';
        //                         break;
                            
        //                     default:
        //                         $login = 'login';
        //                         break;
        //                 }

        //            return   redirect()->guest(route($login));
        // }



        if ($request->expectsJson()) {
          return response()->json(['error' => 'Unauthenticated.'], 401);
         }

         $guard = Arr::get($exception->guards(),0);

         switch ($guard) {
             case 'admin':
                     return redirect()->guest(route('admin.login'));
                 break;
            
             default:
                   return redirect()->guest(route('login'));
                break;
       }
    }
        
}

