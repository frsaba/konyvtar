<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Language;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{
	public function index(Request $request)
	{
		DB::enableQueryLog();
		$DEFAULT_LANGUAGE_ID = 1;

		$language = $request->query('lang');
		$language_id = Language::where('short_name', 'LIKE', $language)->value('id');
		$language_id ??= $DEFAULT_LANGUAGE_ID;


		$books = Book::with([
			'translations' => function ($query) use ($language_id, $DEFAULT_LANGUAGE_ID) {
				$query->where('language_id', $language_id)
					->orWhere('language_id', $DEFAULT_LANGUAGE_ID)
					->orderBy('id', 'desc')->first(); 	//FIXME: since english has an ID of 1, this makes sure the selected language is used if a translation exists, else it falls back to english. But it breaks if a different default language is used
			},
			'authors' => function ($query) {
				$query->select('id', 'name');
			},
			'tags.translations' => function ($query) use ($language_id, $DEFAULT_LANGUAGE_ID) {
				// $query->select('id', 'name');
				$query->where('language_id', $language_id)
					->orWhere('language_id', $DEFAULT_LANGUAGE_ID)
					->orderBy('id', 'desc')
					->first();
			},
		])->get();

		// dd(DB::getQueryLog());


		return Inertia::render("Books", ['books' => $books]);
	}
}
