<?php

use App\Http\Controllers\CallController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MediaController;
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

    // Media
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    // Call
    Route::post('/chats/{chat}/call', [CallController::class, 'initiate'])->name('call.initiate');
    Route::post('/call-rooms/{callRoom}/join', [CallController::class, 'join'])->name('call.join');
    Route::post('/call-rooms/{callRoom}/leave', [CallController::class, 'leave'])->name('call.leave');
    Route::post('/call-rooms/{callRoom}/signal', [CallController::class, 'signal'])->name('call.signal');

    // search
    Route::get('/users/search', [ChatController::class, 'searchUsers'])->name('users.search');
    
});

require __DIR__.'/auth.php';