<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubmissionController extends Controller
{
    public function show($collection_id, Submission $submission) {

        return Inertia::render('User/_submissionId', [
            'submission' => $submission,
        ]);
    }

    public function favorites() {
        $submissions = Submission::where('is_saved', 1)->get();

        return Inertia::render('User/Favorites', [
            'submissions' => $submissions,
        ]);
    }
}
