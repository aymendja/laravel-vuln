<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VulnerabilityController;
use App\Http\Controllers\SqlInjectionController;
use App\Http\Controllers\XssController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Must be declared BEFORE the generic /vulns/{slug} route
Route::get('/vulns/xss', [XssController::class, 'index'])->name('vulns.xss');
Route::get('/vulns/{slug}', [VulnerabilityController::class, 'show'])->name('vulns.show');
Route::post('/lab/sql-login/{type}', [SqlInjectionController::class, 'login'])->name('lab.sql-login');