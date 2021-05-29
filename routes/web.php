<?php

use App\Http\Controllers\Lists\ListController;
use App\Http\Controllers\User\GroupController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::resource('/groups', GroupController::class)->names([
    'index' => 'groups',
    'create' => 'groups.create',
    'store' => 'groups.save',
    'destroy' => 'groups.delete',
    'update' => 'groups.udpate',
])->middleware('auth.basic');

Route::resource('/lists', ListController::class)->names([
    'index' => 'lists',
    'create' => 'lists.create',
    'store' => 'lists.save',
    'destroy' => 'lists.delete',
    'update' => 'lists.udpate',
])->middleware('auth.basic');

require __DIR__.'/auth.php';
