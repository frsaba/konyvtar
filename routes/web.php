<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\LanguagesController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Book;
use App\Models\Language;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;

use function Termwind\render;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BooksController::class, 'index']);
Route::post('/books', [BooksController::class, 'create']);
Route::get('/books/{id}/edit', [BooksController::class, 'edit']);
Route::put('/books/{id}', [BooksController::class, 'save']);
Route::get('/languages', [LanguagesController::class, 'index']);