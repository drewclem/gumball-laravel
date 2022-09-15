<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;

class CollectionController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'date'
        ]);

        Collection::create([
            'user_id' => $request->user()->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_archived' => $request->is_archived
        ]);

        return redirect('/collections');
    }
}
