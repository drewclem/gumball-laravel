<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmissionResource;
use App\Actions\GetSubmissionTagsAction;
use App\Models\Submission;
use App\Models\SubmissionUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Mailgun\Mailgun;

class SubmissionController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string',
            'message' => 'required|string'
        ]);

        // dd($request);

        $submission = Submission::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'collection_id' => $request->collection_id,
            'user_id'=> $request->user_id,
        ]);

        if(count($request->images) > 0) {
            $request->validate([
                'images' => 'nullable|array'
            ]);
            
            foreach ( $request->file('images') as $image) {
                $uuid = Str::uuid();
                $imagePath = null;

                $file = $image['file'];

                $result = $file->storeOnCloudinary('submission-uploads');
                $imagePath = $result->getSecurePath();

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
        $images = $submission->images()->get();

        return Inertia::render('User/_submissionId', [
            'submission' => $submission,
            'images' => $images,
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

    public function showFavorites(Request $request, GetSubmissionTagsAction $action) {
        $submissions = Submission::where('is_saved', 1)->where('user_id', $request->user()->id)->orderBy('created_at', 'asc')->get();

        foreach($submissions as $submission) {
            $submission->tags = $action->handle($submission);
            $submission->images = $submission->images()->get();
        }

        return Inertia::render('User/Favorites', [
            'submissions' => $submissions,
        ]);
    }

    public function inbox(Request $request, GetSubmissionTagsAction $action) {
        // dd($request->user());
        $submissions = Submission::where('user_id', $request->user()->id)->orderBy('created_at', 'asc')->get();

        foreach($submissions as $submission) {
            $submission->tags = $action->handle($submission);
            $submission->images = $submission->images()->get();
        }

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

    public function decline(Request $request, Submission $submission) {
        $mg = Mailgun::create(env('MAILGUN_PRIVATE_KEY'));
        $user = $request->user()->name;

        $mg->messages()->send(env('MAILGUN_DOMAIN'), [
            'from' => 'support@heygumball.com',
            'to' => $submission->email,
            'subject' => "Message from : $user",
            'text' => $request->user()->decline_response
        ]);

        $submission->update([
            'is_declined' => true
        ]);
    }
}
