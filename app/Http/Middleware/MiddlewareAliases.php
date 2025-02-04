<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class MiddlewareAliases
{
    /**
     * The application's middleware aliases.
     *
     * @var array<string, class-string|string>
     */
    public static array $aliases = [
        // ... existing middleware aliases ...
        'check.menu.access' => \App\Http\Middleware\CheckMenuAccess::class,
    ];
} 