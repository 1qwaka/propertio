<?php

use App\Exceptions\WithErrorCodeException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->validateCsrfTokens(except: [
            '*',
        ]);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return true;
        });
        $exceptions->render(function (QueryException $e, Request $request) {
            return response()->json([
                'message' => 'Database Error occurred: ' . $e->getMessage(),
            ], status: 500);
        });
        $exceptions->render(function (WithErrorCodeException $e, Request $request) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], status: $e->getCode());
        });
        $exceptions->render(function (Exception $e, Request $request) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], status: 500);
        });
    })->create();

