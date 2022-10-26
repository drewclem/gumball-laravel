<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SubmissionController extends Controller
{
    // Save a submission
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string',
            'message' => 'required|string'
        ]);

        Submission::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'collection_id' => $request->collection_id,
            'user_id'=> $request->user_id,
        ]);


        return redirect()->back();
    }

    // Shows a single submission
    public function show($collection_id, Submission $submission) {

        // none of this is right
        $tags = DB::table('tag_relations')->where('submission_id', $submission->id)->select('tag_relations.id as tag_relation_id', 'tag_relations.tag_id', 'tag_relations.submission_id', 'tags.label')->join('tags', 'tag_relations.tag_id', '=', 'tags.id')->get();

        return Inertia::render('User/_submissionId', [
            'submission' => $submission,
            'tags' => $tags,
        ]);
    }
    
    // Toggle whether the submission is favorited or not
    public function favorite(Submission $submission) {
        if($submission->is_saved === 0){
            $submission->update([
                'is_saved' => 1
            ]);
        } else {
            $submission->update([
                'is_saved' => 0
            ]);
        }
    }

    // Mark submission as booked
    public function book(Submission $submission) {

        if($submission->is_booked === 0){
            $submission->update([
                'is_booked' => 1
            ]);
        } else {
            $submission->update([
                'is_booked' => 0
            ]);
        }
    }

    public function like(Submission $submission) {
        if($submission->is_liked === 0){
            $submission->update([
                'is_liked' => 1
            ]);
        } else {
            $submission->update([
                'is_liked' => 0
            ]);
        }
    }

    public function dislike(Submission $submission) {
        if($submission->is_liked === 0){
            $submission->update([
                'is_liked' => -1
            ]);
        } else {
            $submission->update([
                'is_liked' => 0
            ]);
        }
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
