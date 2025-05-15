<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomerController;
use App\Livewire\PublicSalesForm;
use App\Http\Controllers\OrderPdfController;
use App\Http\Controllers\DocumentController;



// Route::view('/', 'welcome');

Route::get('/', function () {
    return view('app'); // Assuming your homepage Blade file is resources/views/home.blade.php
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

    Route::get('order-upload/{order}', PublicSalesForm::class)->name('order.upload');

Route::get('customer-photo/{filename}', function ($filename) {
    return response()->file(storage_path("app/private/private/documents/$filename"));
})->middleware('auth')->name('customer.photo');

Route::get('/orders/pi/{order}/pdf', [OrderPdfController::class, 'generatepi'])
    ->name('order.pi.pdf')
    ->middleware('auth');

Route::get('/orders/sd/{order}/pdf', [OrderPdfController::class, 'generatesd'])
    ->name('order.sd.pdf')
    ->middleware('auth');


    Route::get('storage/private/{file}', function ($file) {
        $filePath = storage_path('app/private/' . $file);
        if (file_exists($filePath)) {
            return Response::make(file_get_contents($filePath), 200, [
                'Content-Type' => 'image/jpeg',  // Adjust the content type based on the file
                'Content-Disposition' => 'inline; filename="' . $file . '"',
            ]);
        }
    
        abort(404); // File not found
    });

    Route::get('storage/private/signature/{file}', [DocumentController::class, 'showDocument'])->middleware('auth')->name('document.show');

    Route::get('/private-avatar/{user}', function (\App\Models\User $user) {
        if (!$user->avatar_url || !Storage::disk('private')->exists($user->avatar_url)) {
            abort(404);
        }
    
        // Optional: authorize the request
        // abort_unless(auth()->id() === $user->id, 403);
    
        return response()->file(Storage::disk('private')->path($user->avatar_url));
    })->name('user.private-avatar');

require __DIR__.'/auth.php';