<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\GetSubmissionTagsAction;
use App\Models\Collection;
use App\Actions\StoreCollectionAction;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function index() {

        $collections = Collection::withCount(['submissions', 'booked'])->where('user_id', Auth::user()->id)->orderBy('start_date', 'asc')->get();
        $active = Collection::active()->withCount(['submissions', 'booked'])->where('user_id', Auth::user()->id)->first();

        return Inertia::render('User/Collections', [
            'collections' => $collections,
            'active' => $active,
        ]);
    }

    public function store(Request $request, StoreCollectionAction $action) {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'date|nullable'
        ]);

        $collection = $action->handle([
            'user' => $request->user(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
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

    public function show(Collection $collection, GetSubmissionTagsAction $action) {
        $submissions = $collection->submissions()->orderBy('created_at')->get();

        foreach($submissions as $submission) {
            $submission->tags = $action->handle($submission);
            $submission->images = $submission->images()->get();
        }
        
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
