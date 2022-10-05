<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index() {
        $tags = Tag::withCount(['usage'])->where('user_id', auth()->id())->get();
        
        return Inertia::render('User/Tags', [
            'tags' => $tags
        ]);
    }
}
