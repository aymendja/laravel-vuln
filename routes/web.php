<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VulnerabilityController;
use App\Http\Controllers\SqlInjectionController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/vulns/{slug}', [VulnerabilityController::class, 'show'])->name('vulns.show');
Route::post('/lab/sql-login/{type}', [SqlInjectionController::class, 'login'])->name('lab.sql-login');