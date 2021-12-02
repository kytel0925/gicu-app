<?php

use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Fakes\Results;
use App\Http\Controllers\ReportRequests;
use App\Http\Controllers\ReportResults;
use App\Http\Controllers\RuntimeConnections;
use App\Http\Controllers\Tags;
use App\Http\Controllers\Templates;
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

//Route::get('/', [Dashboard::class, 'index'])->name('index.get');

Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/runtime-connections', [RuntimeConnections::class, 'index'])->name('dashboard.runtime-connections.index');
Route::get('/dashboard/report/{report}/requests', [ReportRequests::class, 'index'])->name('dashboard.report-requests.index');
Route::get('/dashboard/report/{report}/results/{request}', [ReportResults::class, 'index'])->name('dashboard.report-results.index');

Route::get('/dashboard/tags', [Tags::class, 'index'])->name('dashboard.tags.index');
Route::get('/dashboard/templates', [Templates::class, 'index'])->name('dashboard.templates.index');

/*Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('foo', function (){
    return Inertia::render('Foo');
})->name('foo');*/
