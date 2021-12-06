<?php

use App\Http\Controllers\AcademicPeriods;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Degrees;
use App\Http\Controllers\Fakes\Results;
use App\Http\Controllers\Indicators;
use App\Http\Controllers\ReportRequests;
use App\Http\Controllers\ReportResults;
use App\Http\Controllers\Reports;
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

Route::get('/', [Dashboard::class, 'index'])->name('dashboard.index');

Route::get('/data-gathering/{report}/requests', [ReportRequests::class, 'index'])->name('report-requests.index');
Route::get('/data-gathering/{report}/results/{request}', [ReportResults::class, 'index'])->name('report-results.index');

Route::get('/indicators/SGIC', [Indicators::class, 'SGIC'])->name('indicators.sgic.index');
Route::get('/indicators/SGIC/{sgicId}', [Indicators::class, 'sgicManage'])->name('indicators.sgic.manage');
Route::get('/indicators/satisfaction', [Indicators::class, 'satisfaction'])->name('indicators.satisfaction.index');
Route::get('/indicators/satisfaction/{satisfactionId}', [Indicators::class, 'satisfactionManage'])->name('indicators.satisfaction.manage');

Route::get('/reports', [Reports::class, 'index'])->name('reports.index');

Route::get('/tags', [Tags::class, 'index'])->name('tags.index');
Route::get('/academic-periods', [AcademicPeriods::class, 'index'])->name('academic-periods.index');
Route::get('/degrees', [Degrees::class, 'index'])->name('degrees.index');

Route::get('/templates', [Templates::class, 'index'])->name('dashboard.templates.index');
Route::get('/runtime-connections', [RuntimeConnections::class, 'index'])->name('dashboard.runtime-connections.index');


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
