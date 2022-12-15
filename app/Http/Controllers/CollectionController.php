<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use Inertia\Inertia;
use Carbon\Carbon;

class CollectionController extends Controller
{
    public function index() {

        $collections = Collection::withCount(['submissions', 'booked'])->where('user_id', auth()->id())->orderBy('start_date', 'desc')->get();
        $active = Collection::active()->withCount(['submissions', 'booked'])->where('user_id', auth()->id())->get();

        return Inertia::render('User/Collections', [
            'collections' => $collections,
            'active' => $active,
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

    public function close(Collection $collection) {

        $current_date = Carbon::now()->toDateTimeString();

        $collection->update([
            'end_date'=> $current_date
        ]);


        return redirect('collections');
    }

    public function show(Collection $collection) {
        $submissions = $collection->submissions()->orderBy('created_at', 'desc')->get();
        
        return Inertia::render('User/_collectionId', [
            'collection' => $collection,
            'submissions' => $submissions,
        ]); 
    }

    public function destroy(Collection $collection) {
        $collection->delete();

        return redirect('collections');
    }
}
