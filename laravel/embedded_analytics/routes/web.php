<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

// The home page shows a public embed, which needs the Metabase URL.
Route::get('/', fn () => view('welcome', [
    'metabaseSiteUrl' => config('services.metabase.site_url'),
]))->name('home');

// The embedded Metabase dashboard. See app/Http/Controllers/AnalyticsController.php.
Route::get('/analytics', [AnalyticsController::class, 'show'])
    ->middleware('auth')
    ->name('analytics');

// A basic login for the demo. Replace it with your app's own authentication.
Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
