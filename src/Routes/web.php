
<?php

use Illuminate\Support\Facades\Route;
use TallStackUi\Foundation\Http\Controllers\TallStackUiAssetsController;
use twa\uikit\Controllers\UIAssetsController;

Route::name('twauikit.')
    ->prefix('/twauikit')
    ->group(function () {
        Route::get('/script/{file?}', [UIAssetsController::class, 'script'])->name('script');
        Route::get('/style/{file?}', [UIAssetsController::class, 'style'])->name('style');
    });
