<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;

// Rotas públicas
Route::get('/', [NewsController::class, 'index'])->name('home');

// Autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rota específica para mostrar uma notícia (deve ficar DEPOIS das rotas específicas de news)
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show')
    ->where('id', '[0-9]+'); // Forçar que o parâmetro seja um número

// Rotas protegidas
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Notícias
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('news.edit')
        ->where('id', '[0-9]+');
    Route::put('/news/{id}', [NewsController::class, 'update'])->name('news.update')
        ->where('id', '[0-9]+');
    Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy')
        ->where('id', '[0-9]+');
    Route::post('/news/{id}/approve', [NewsController::class, 'approve'])->name('news.approve')
        ->where('id', '[0-9]+');
    
    // Usuários - agora sem o middleware role
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit')
        ->where('id', '[0-9]+');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update')
        ->where('id', '[0-9]+');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy')
        ->where('id', '[0-9]+');
});