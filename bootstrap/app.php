<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth; // <--- JANGAN LUPA IMPORT INI

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Alias Middleware Role (Yang sudah kamu buat sebelumnya)
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // 2. LOGIKA REDIRECT JIKA SUDAH LOGIN (GUEST MIDDLEWARE)
        // Ini akan jalan otomatis jika user yang sudah login mencoba buka halaman '/'
        $middleware->redirectUsersTo(function () {
            $user = Auth::user();
            
            if (!$user) return '/'; // Jaga-jaga

            // Cek Role dan arahkan ke halaman masing-masing
            return match($user->role->name) {
                'boss'   => route('boss.dashboard'),
                'admin'  => route('admin.dashboard'),
                'crew'   => route('crew.jobs'),
                'editor' => route('editor.dashboard'),
                default  => '/'
            };
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();