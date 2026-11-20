<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VulnerabilityController;
use App\Http\Controllers\SqlInjectionController;
use App\Http\Controllers\XssController;
use App\Http\Controllers\SstiController;
use App\Http\Controllers\PathTraversalController;
use App\Http\Controllers\FileUploadController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::prefix('vulns')->name('vulns.')->group(function () {
    Route::get('/sql-injection', [SqlInjectionController::class, 'index'])->name('sql-injection');
    Route::get('/xss', [XssController::class, 'index'])->name('xss');
    Route::get('/ssti', [SstiController::class, 'index'])->name('ssti');
    Route::get('/path-traversal', [PathTraversalController::class, 'index'])->name('path-traversal');
    Route::get('/file-upload', [FileUploadController::class, 'index'])->name('file-upload');
    Route::post('/file-upload', [FileUploadController::class, 'store'])->name('file-upload.store');
    Route::post('/sql-login/{type}', [SqlInjectionController::class, 'login'])->name('sql-login');
});