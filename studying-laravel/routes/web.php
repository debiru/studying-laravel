<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('global.index');
})->name('/');

Route::get('/mypage', function () {
    return view('auth.mypage');
})->name('mypage');

Route::get('/contact', function () {
    return view('global.contact');
})->name('contact');

Route::get('/law', function () {
    return view('global.law');
})->name('law');

Route::get('/privary', function () {
    return view('global.privacy');
})->name('privacy');

Route::get('/terms-of-use', function () {
    return view('global.terms-of-use');
})->name('terms-of-use');
