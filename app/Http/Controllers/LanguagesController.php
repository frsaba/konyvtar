<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;

class LanguagesController extends Controller
{
    public function index(Request $request)
    {
		$languages = Language::all();

        return $languages;
    }
}
