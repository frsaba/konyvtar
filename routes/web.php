<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Book;
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

Route::get('/', function () {

    $books = Book::with([
        'translations' => function ($query) {
            $query->where('language_id', 1); // English translations
        },
        'authors' => function ($query) {
            $query->select('id','name');
        },
        'tags.translations' => function ($query) {
            // $query->select('id', 'name');
            $query->where('language_id', 1)->first();
        },
    ])->get();


    return Inertia::render("Books", ['books' => $books]);
});
