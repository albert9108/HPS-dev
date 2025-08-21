<?php
protected $routeMiddleware = [
    // ...
    'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
    'web' => \App\Http\Middleware\EncryptCookies::class,
    'web' => \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    'web' => \Illuminate\Session\Middleware\StartSession::class,
    'web' => \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    'web' => \App\Http\Middleware\VerifyCsrfToken::class,
    'web' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
    'check.upload.permission' => \App\Http\Middleware\CheckUploadPermission::class,
];
