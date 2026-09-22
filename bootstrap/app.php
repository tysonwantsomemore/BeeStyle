<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ShipperMiddleware;
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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'   => AdminMiddleware::class,
            'shipper' => ShipperMiddleware::class,
        ]);
        $middleware->redirectTo(
            guests: '/dang-nhap',
            users: '/'
        );
        $middleware->validateCsrfTokens(except: [
            'api/payments/momo/ipn',
            'thanh-toan/momo/ipn',
            'thanh-toan/momo/*',
            'thanh-toan/momo',
            'atm_momo.php',
            'query_transaction.php',
            'thanh-toan/momo/query_transaction.php',
            'api/payments/vnpay/ipn',
            'thanh-toan/vnpay/ipn',
            'thanh-toan/vnpay/*',
            'vnpay_payment',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
