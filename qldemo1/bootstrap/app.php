<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\EnsureLoggedIn;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\EnsureActiveAccount;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {

        // Alias middleware custom
        $middleware->alias([
            'auth.session' => EnsureLoggedIn::class,
            'role'         => RoleMiddleware::class,
            'active'       => EnsureActiveAccount::class,
        ]);

        /**
         * KHÔNG tắt CSRF ở đây (an toàn hơn).
         * Chatbox sẽ gửi X-CSRF-TOKEN đúng nên không cần except.
         *
         * Nếu bạn vẫn muốn except (không khuyến nghị), thì mở dòng dưới:
         *
         * $middleware->validateCsrfTokens(except: ['ai/event-chat']);
         */
    })
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
