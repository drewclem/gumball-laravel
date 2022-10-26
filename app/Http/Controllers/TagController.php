<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\TagRelation;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index() {
        $tags = Tag::withCount(['usage'])->where('user_id', auth()->id())->get();
        
        return Inertia::render('User/Tags', [
            'tags' => $tags
        ]);
    }

    public function store(Request $request) {

        $tag = Tag::create([
            'label' => $request->label,
            'user_id' => auth()->id(),
        ]);

        if($request->submission_id) {
            TagRelation::create([
                'submission_id' => $request->submission_id,
                'tag_id' => $tag->id,
            ]);
        }
    }

    public function delete(Request $request) {
        $tag = Tag::find($request->id);

        $tag->delete();
    }
}
