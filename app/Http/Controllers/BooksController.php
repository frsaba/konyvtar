<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Language;
use App\Models\Tag;
use App\Models\Translation;
use DateTime;
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
		])->latest('updated_at')->get();

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

		$book = Book::with(['translations', 'tags.translations', 'authors'])->find($id);

		return Inertia::render('EditBook', ['book' => $book, 'languages' => Language::all(), 'authors' => Author::all(), 'tags' => Tag::with('translations')->get()]);
	}

	public function save($id, Request $request)
	{

		//Handle author changes
		$book = Book::find($id);
		foreach ($request->authorsToRemoveFromBook as $author) {
			$book->authors()->detach($author['id']);
		}
		foreach ($request->authorsToCreate as $name) {
			$newAuthor = Author::create(['name' => $name]);
		}
		foreach ($request->authorsToAddToBook as $name) {
			$author = Author::where('name', $name)->first();
			$book->authors()->attach($author->id);
		}

		//tag changes
		foreach ($request->tagsToRemoveFromBook as $tag) {
			$book->tags()->detach($tag['id']);
		}
		foreach ($request->tagsToAddToBook as $tag) {
			$book->tags()->attach($tag['id']);
		}


		//update ISBN and publication year
		DB::table('books')
			->where('id', $id)
			->update([
				'isbn' => $request->isbn['_value'],
				'publish_year' => $request->publishYear['_value'],
				'thumbnail' => $request->thumbnail['_value'],
				'updated_at' => new DateTime()
			]);


		//update translations
		Translation::upsert($request->input('translations'), ['book_id', 'language_id'],);

		return Inertia::location(url("/"));
	}

	public function destroy($id)
	{
		Book::find($id)->delete();

		return Inertia::location(url("/"));
	}
}
