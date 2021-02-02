<?php

use Illuminate\Support\Facades\Artisan;
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
Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'doLogin'])->name('doLogin');
    // Route::view('/', 'welcome')->name('welcome');
});

Route::middleware('auth', 'verified')->group(function () {
	Route::view('dashboard', 'dashboard')->name('dashboard');
	Route::view('profile', 'profile')->name('profile');
    Route::view('testing', 'testing')->name('testing');

});

Route::group([

    'prefix' => 'posyandu',

    'middleware' => 'auth'

    ], function () {

    Route::get('/', [App\Http\Controllers\MstPosyanduController::class, 'index'])->name('posyandu');
    Route::get('/json', [App\Http\Controllers\MstPosyanduController::class, 'json_list'])->name('posyandu.json');

    Route::post('/store', [App\Http\Controllers\MstPosyanduController::class, 'store'])->name('posyandu.store');
    Route::post('/update', [App\Http\Controllers\MstPosyanduController::class, 'update'])->name('posyandu.update');
    Route::post('/delete', [App\Http\Controllers\MstPosyanduController::class, 'destroy'])->name('posyandu.delete');

    Route::get('/get/{id}', [App\Http\Controllers\MstPosyanduController::class, 'getPosyandu'])->name('posyandu.get');
});

Route::group([

    'prefix' => 'kader',

    'middleware' => 'auth'

    ], function () {

    Route::get('/', [App\Http\Controllers\KaderPosyanduController::class, 'index'])->name('kader');
    Route::get('/json', [App\Http\Controllers\KaderPosyanduController::class, 'json_list'])->name('kader.json');
    Route::post('/store', [App\Http\Controllers\KaderPosyanduController::class, 'store'])->name('kader.store');
    Route::post('/update', [App\Http\Controllers\KaderPosyanduController::class, 'update'])->name('kader.update');
    Route::post('/delete', [App\Http\Controllers\KaderPosyanduController::class, 'destroy'])->name('kader.delete');

    Route::get('/get/{id}', [App\Http\Controllers\KaderPosyanduController::class, 'show'])->name('kader.get');
});

Route::group([

    'prefix' => 'anggota',

    'middleware' => 'auth'

    ], function () {

    Route::get('/', [App\Http\Controllers\LansiaPosyanduController::class, 'index'])->name('anggota');
    Route::get('/json', [App\Http\Controllers\LansiaPosyanduController::class, 'json_list'])->name('anggota.json');
    Route::post('/store', [App\Http\Controllers\LansiaPosyanduController::class, 'store'])->name('anggota.store');
    Route::post('/update', [App\Http\Controllers\LansiaPosyanduController::class, 'update'])->name('anggota.update');
    Route::post('/delete', [App\Http\Controllers\LansiaPosyanduController::class, 'destroy'])->name('anggota.delete');

    Route::get('/get/{id}', [App\Http\Controllers\LansiaPosyanduController::class, 'show'])->name('anggota.get');

});

Route::group([

    'prefix' => 'ajax',

    'middleware' => 'auth'

    ], function () {

    Route::get('/json-posyandu', [App\Http\Controllers\MstPosyanduController::class, 'json_select'])->name('list.posyandu.select');
});

// Clear application cache:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});

Route::get('/config-clear', function() {
    $exitCode = Artisan::call('config:clear');
    return 'Application config cleared';
});

// Clear view cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return 'View cache cleared';
});

Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return  'Configuration clear successfully!';
});
