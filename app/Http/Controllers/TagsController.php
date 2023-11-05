<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TagTranslation;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index(){
        return Tag::with('translations')->get();
    }

    public function create(Request $request){

        $tag = new Tag();
        $tag->save();

        for($i = 0; $i < count($request->languages); $i++){
            $translation = $request->translations[$i];
            $language_id = $request->languages[$i]['id'];

            $tagTranslation = new TagTranslation(['language_id' => $language_id, 'tag_id' => $tag->id, 'name' => $translation]);
            $tagTranslation->save();
        }

        // return "Tag creation successful";
    }
}
