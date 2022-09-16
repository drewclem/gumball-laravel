<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubmissionController extends Controller
{
    public function index() {

        return Inertia::render('User/Inbox', [
            'submissions' => Submission::all(),
        ]);
    }
}
