<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;

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

Route::get('/dashboard', function(){
    return view('scanner1');
})->name('dashboard.form');
Route::post('/dashboard', [UploadController::class, 'upload'])->name('upload');
Route::get('/report', [UploadController::class, 'display'])->name('uploads.report');
// Auth::routes();

// Route::get('/', [UploadController::class, 'dashboard'])->name('uploads.Dashboard');
// Route::get('upload/scanner', [UploadController::class, 'create'])->name('uploads.create');
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');