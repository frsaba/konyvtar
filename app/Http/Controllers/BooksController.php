<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Language;
use App\Models\Tag;
use App\Models\Translation;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{
	private $DEFAULT_LANGUAGE_ID = 1;

	//returns a closure for fetching translations based on the provided language ID with a fallback to default language ID.
	private function getTranslationsQuery($languageId)
	{
		$defaultLanguageId = $this->DEFAULT_LANGUAGE_ID;

		return function ($query) use ($languageId, $defaultLanguageId) {
			$query->where('language_id', $languageId)
				->orWhere('language_id', $defaultLanguageId)
				->orderBy('language_id', 'desc');
		};
	}

	public function index(Request $request)
	{
		// DB::enableQueryLog();

		$language = $request->query('lang');
		$languageId = Language::where('short_name', 'LIKE', $language)->value('id') ?? $this->DEFAULT_LANGUAGE_ID;

		$books = Book::with([
			'translations' => $this->getTranslationsQuery($languageId),
			'authors' => function ($query) {
				$query->select('id', 'name');
			},
			'tags',
			'tags.translations' => $this->getTranslationsQuery($languageId),
		])->get();

		$tags = Tag::with(['translations' => $this->getTranslationsQuery($languageId)])->get();

		return Inertia::render('Books', ['books' => $books, 'tags' => $tags, 'languages' => Language::all()]);
	}

	public function create(Request $request)
	{
		$request->validate([
			'isbn' => 'required|string',
			'year' => 'required|integer',
		]);

		$book = new Book([
			'isbn' => $request->input('isbn'),
			'publish_year' => $request->input('year'),
		]);

		$book->save();

		return Inertia::location(url("/books/{$book->id}/edit"));
	}

	public function edit($id)
	{

		$book = Book::with(['translations', 'tags.translations'])->find($id);

		return Inertia::render('EditBook', ['book' => $book, 'languages' => Language::all()]);
	}

	public function save($id, Request $request)
	{
		// dd($request->isbn['_value']);
		// dd($request->input('translations'));
		// $validated = $request->validate([
		// 	'isbn' => 'required|string',
		// 	'publishYear' => 'required|integer',
		// ]);
		// dd($validated);

		DB::table('books')
			->where('id', $id)
			->update([
				'isbn' => $request->isbn['_value'],
				'publish_year' => $request->publishYear['_value']
			]);
			
		
		Translation::upsert($request->input('translations'), ['book_id', 'language_id'],);

		return Inertia::location(url("/"));
	}
}
