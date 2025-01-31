<?php

use App\Exceptions\Handler;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ForceJsonResponse::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->render(function (Throwable $e, Request $request) {
        //     return app(Handler::class)->render($request, $e);
        // });
        // $exceptions->render(function (Throwable $e, Request $request) {
        //     return app(Handler::class)->render($request, $e);
        // });

        // Handle authentication exceptions
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'detail' => 'Invalid credentials',
            ], 401);
        });
        $exceptions->render(function (\Exception $e, Request $request) {
            error_log($e);
            $statusCode = $e->getCode() > 0 ? $e->getCode() : 500;

            return response()->json([
                'detail' => $statusCode == 500 ? 'Internal server error' : $e->getMessage(),
            ], $statusCode);
        });
    })->create();
