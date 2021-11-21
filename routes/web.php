<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Models\Role;
use App\Http\Controllers\{CategoryController, AuthorController, BookController, BorrowController};

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
$admin = Role::ADMIN;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('books', BookController::class)->only(['index']);
Route::resource('categories', CategoryController::class)->only(['show']);
Route::resource('authors', AuthorController::class)->only(['show']);

Route::group(['middleware' => ['auth:sanctum', "checkRole:{$admin}"]], function () {
    Route::post('/books/request', [BookController::class, 'request'])->name('book.request');
});

Route::group(['middleware' => ['auth:sanctum', "checkRole:{$admin}"]], function () {
    Route::resource('categories', CategoryController::class)->only(['index', 'destroy']);
    Route::resource('authors', AuthorController::class)->only(['index', 'destroy']);
    Route::resource('books', BookController::class)->only(['destroy']);
    Route::resource('borrows', BorrowController::class)->only(['index']);
});
