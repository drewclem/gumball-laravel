<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\SubmissionUpload;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    public function store(Request $request){


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string',
            'message' => 'required|string'
        ]);

        $submission = Submission::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'collection_id' => $request->collection_id,
            'user_id'=> $request->user_id,
        ]);

        if($request->has('images')) {
            $request->validate([
                'images' => 'nullable|array'
            ]);
            
            foreach ( $request->file('images') as $image) {
                $uuid = Str::uuid();
                $imagePath = null;

                $file = $image['file'];
            
                $imagePath = $file->storeAs(
                    'submission-uploads',
                    $uuid . '-submission.' . $file->extension(),
                    'public'
                );

                SubmissionUpload::create([
                    'submission_id' => $submission->id,
                    'file_path'=>$imagePath
                ]);
            }
            
        }

        return redirect()->back();
    }

    public function show($collection_id, Submission $submission) {

        $tags = $submission->tags()->join('tags', 'tag_relations.tag_id', '=', 'tags.id')->get();

        return Inertia::render('User/_submissionId', [
            'submission' => $submission,
            'tags' => $tags,
        ]);
    }
    
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

    public function showFavorites() {
        $submissions = Submission::where('is_saved', 1)->get();

        return Inertia::render('User/Favorites', [
            'submissions' => $submissions,
        ]);
    }

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
