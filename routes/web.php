<?php

use Illuminate\Support\Facades\Route;

Route::get('login', static function () {
    return 'Take me to your leader';
})->name('login');
