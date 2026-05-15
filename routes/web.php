<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('chat.index'));

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', fn() => redirect()->route('chat.index'))
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chat
    Route::get('/chats', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chats/private', [ChatController::class, 'createPrivate'])->name('chat.private');
    Route::post('/chats/group', [ChatController::class, 'createGroup'])->name('chat.group');

    // Message
    Route::post('/chats/{chat}/messages', [MessageController::class, 'store'])->name('message.store');
    Route::put('/messages/{message}', [MessageController::class, 'update'])->name('message.update');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('message.destroy');
    Route::post('/chats/{chat}/read', [MessageController::class, 'markAsRead'])->name('message.read');

});

require __DIR__.'/auth.php';