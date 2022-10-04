<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InertiaController;

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

Route::get('/inertia-test', function () {
    return Inertia::render('InertiaTest');
    }
);

Route::get('/component-test', function () {
    return Inertia::render('componentTest');
    }
);

Route::get('/inertia/index', [InertiaController::class, 'index'])->name('inertia.index');
Route::get('/inertia/create', [InertiaController::class, 'create'])->name('inertia.create');
Route::get('/inertia/show/{id}', [InertiaController::class, 'show'])->name('inertia.show');
Route::post('/inertia', [InertiaController::class, 'store'])->name('inertia.store');
Route::post('/inertia/{id}', [InertiaController::class, 'delete'])->name('inertia.delete');

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

require __DIR__.'/auth.php';
