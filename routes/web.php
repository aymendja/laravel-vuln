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

// Must be declared BEFORE the generic /vulns/{slug} route
Route::get('/vulns/xss', [XssController::class, 'index'])->name('vulns.xss');
Route::get('/vulns/ssti', [SstiController::class, 'index'])->name('vulns.ssti');
Route::get('/vulns/path-traversal', [PathTraversalController::class, 'index'])->name('vulns.path-traversal');
Route::get('/vulns/file-upload', [FileUploadController::class, 'index'])->name('vulns.file-upload');
Route::post('/vulns/file-upload', [FileUploadController::class, 'store'])->name('vulns.file-upload.store');
Route::get('/vulns/{slug}',[VulnerabilityController::class, 'show'])->name('vulns.show');
Route::post('/lab/sql-login/{type}', [SqlInjectionController::class, 'login'])->name('lab.sql-login');