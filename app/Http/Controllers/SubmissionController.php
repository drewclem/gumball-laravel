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
}
