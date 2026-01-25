<?php

use App\Http\Controllers\AskDarlingController;
use Illuminate\Support\Facades\Route;

// AskDarling Chatbot API
Route::middleware(['throttle:60,1'])->group(function () {
    Route::post('/ask-darling', [AskDarlingController::class, 'ask'])->name('api.ask-darling');
});

// Chat feedback endpoint (no rate limit needed for feedback)
Route::post('/chat-feedback', [AskDarlingController::class, 'feedback'])->name('api.chat-feedback');
