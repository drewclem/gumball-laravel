<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TagRelation;

class TagRelationController extends Controller
{
    public function store(Request $request) {
        TagRelation::create([
            'submission_id' => $request->submission_id,
            'tag_id' => $request->tag_id
        ]);
    }

    public function delete(Request $request) {
        $tag_relation = TagRelation::find($request->id);

        $tag_relation->delete();
    }
}
