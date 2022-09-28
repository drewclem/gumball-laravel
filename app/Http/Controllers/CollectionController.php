<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use Inertia\Inertia;

class CollectionController extends Controller
{
    public function index() {

        $collections = Collection::withCount(['submissions', 'booked'])->get();

        return Inertia::render('User/Collections', [
            'collections' => $collections,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'date|nullable'
        ]);

        Collection::create([
            'user_id' => $request->user()->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_archived' => $request->is_archived
        ]);

        return redirect('/collections');
    }

    public function scheduled_dates() {
        // return every start_end and end_date as well as any dates between them
    }

    public function show(Collection $collection) {
        $submissions = Collection::find($collection->id)->submissions;
        
        return Inertia::render('User/_collectionId', [
            'collection' => $collection,
            'submissions' => $submissions,
        ]); 
    }
}
