<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LiveStreamController;
use App\Http\Middleware\AdminMiddleware;

// Rotas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

// Rota pública para obter a transmissão ativa
Route::get('/live-stream/active', [LiveStreamController::class, 'getActive']);

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rotas de notícias para usuários autenticados
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
    
    // Rotas para aprovadores e administradores
    Route::post('/news/{news}/approve', [NewsController::class, 'approve'])->name('news.approve');
});

// Rotas administrativas com namespace completo e sem usar prefix/group
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Rotas para livestreams
    Route::get('/admin/live-streams', [LiveStreamController::class, 'index'])->name('live-streams.index');
    Route::post('/admin/live-streams', [LiveStreamController::class, 'store'])->name('live-streams.store');
    Route::get('/admin/live-streams/{id}', [LiveStreamController::class, 'show'])->name('live-streams.show');
    Route::put('/admin/live-streams/{id}', [LiveStreamController::class, 'update'])->name('live-streams.update');
    Route::delete('/admin/live-streams/{id}', [LiveStreamController::class, 'destroy'])->name('live-streams.destroy');
    Route::put('/admin/live-streams/{id}/activate', [LiveStreamController::class, 'activate'])->name('live-streams.activate');
});

// Rotas públicas com parâmetros (DEVE VIR DEPOIS das rotas específicas)
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');