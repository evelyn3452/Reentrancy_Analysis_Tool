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

use App\Http\Controllers\SlitherController;

Route::get('/upload', [SlitherController::class, 'showUploadForm'])->name('upload.form');
Route::post('/upload', [SlitherController::class, 'uploadContract'])->name('upload.contract');
Route::get('/results', [SlitherController::class, 'showResults'])->name('results');


// Route::get('/', function(){
//     return view('scanner1');
// });
// Route::get('/report', function(){
//     return view('report');
// });


// Route::post('/', [ScannerController::class, 'upload'])->name('upload');
// Route::get('/report', [ReportController::class, 'index'])->name('report');

// Route::post('/upload', [TestingController::class, 'upload'])->name('upload'); 
// Route::get('/report', [TestingController::class, 'report'])->name('report');

// Route::get('/dashboard',function(){
//     return view('scanner1');
// })->name('dashboard');

// Auth::routes();

// Route::get('/', [UploadController::class, 'dashboard'])->name('uploads.Dashboard');
// Route::get('upload/scanner', [UploadController::class, 'create'])->name('uploads.create');
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');