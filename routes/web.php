<?php

use Illuminate\Support\Facades\Route;

Route::get('{any}', fn () => view('index'))
    ->where('any', '.*')
    ->name('spa');
