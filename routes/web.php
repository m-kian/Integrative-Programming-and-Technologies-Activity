<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

Route::get('/sanctum/csrf-cookie', function () {
    $token = Str::random(40);

    Cookie::queue('XSRF-TOKEN', $token, 120, '/', '.testProject.test', true, false, false, 'Lax');

    return response()->json(['csrfToken' => $token]);
});

Route::get('/', function () {
    return view('welcome');
});
