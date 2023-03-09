<?php

namespace App\Exceptions;

use App\Http\Traits\ResponseTraits;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseTraits;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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

    // public function render($request, Throwable $e)
    // {
    //     if ($e instanceof ModelNotFoundException) {
    //         return $this->dataNotFound('Data Not Found on id :'.substr( $e->getMessage(),-2));
    //     }

    //     else if($e instanceof RouteNotFoundException)
    //     {
    //         return $this->sendFailureResponse("Authantication error");
    //     }
    //     $boolean = Str::contains($e->getMessage(), 'Duplicate entry');
    //     if($boolean)
    //     {
    //         return response()->json(['status'=>false,'message'=>"Data Duplicate Error"],500);
    //     }
    // }
}
