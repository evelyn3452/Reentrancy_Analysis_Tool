<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\TestingController;

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

Route::get('/', function(){
    return view('upload');
});
// Route::post('/', [ScannerController::class, 'upload'])->name('upload');
// Route::get('/report', [ReportController::class, 'index'])->name('report');

Route::post('/upload', [TestingController::class, 'upload'])->name('upload'); 
Route::get('/report', [TestingController::class, 'report'])->name('report');

// Auth::routes();

// Route::get('/', [UploadController::class, 'dashboard'])->name('uploads.Dashboard');
// Route::get('upload/scanner', [UploadController::class, 'create'])->name('uploads.create');
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');