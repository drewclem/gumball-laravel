<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubmissionController extends Controller
{
    // Shows a single submission
    public function show($collection_id, Submission $submission) {

        return Inertia::render('User/_submissionId', [
            'submission' => $submission,
        ]);
    }
    
    // Toggle whether the submission is favorited or not
    public function favorite(Request $request, Submission $submission) {
        $submission->update([
            'is_saved' => $request->is_saved
        ]);

        return redirect()->back();

    }

    // Mark submission as booked
    public function book(Request $request, Submission $submission) {

        $submission->update([
            'is_booked' => $request->is_booked
        ]);

        return redirect()->back();

    }

    // Returns all submissions that have been favorited
    public function showFavorites() {
        $submissions = Submission::where('is_saved', 1)->get();

        return Inertia::render('User/Favorites', [
            'submissions' => $submissions,
        ]);
    }

    // Returns all submissions in a single view
    public function inbox() {
        $submissions = Submission::all();

        return Inertia::render('User/Inbox', [
            'submissions' => $submissions,
        ]);
    }

    public function markViewed(Submission $submission) {
        $submission->update([
            'has_viewed' => 1
        ]);
    }

    public function delete(Submission $submission) {
        $redirect_url = "/collections/$submission->collection_id";
        $submission->delete();

        return redirect($redirect_url);
    }
}
