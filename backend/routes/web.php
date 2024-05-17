<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/null', function () {
    return null;
});

// Route::get('/{any}', function () {
//     return Response::file(public_path('index.html'));
// })->where('any', '.*');

//require __DIR__.'/auth.php';
