<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        /*
        \DB::rollback();
        $properties = array_merge($exception->getTrace()[0]['args'], ['error' => ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'file' => $exception->getFile()]]);

        \DB::table('activity_log')->insert([
            'log_name' => 'Error',
            'description' => $exception->getTrace()[0]['function'],
            'subject_type' => $exception->getTrace()[0]['class'], 
            'causer_type' => $exception->getMessage(), 
            'properties' => json_encode($properties),
            'created_at' => date('Y/d/m H:i:s'),
            'updated_at' => date('Y/d/m H:i:s'),
        ]);*/
        
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
        return parent::render($request, $exception);
    }
}
