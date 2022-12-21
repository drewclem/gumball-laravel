<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;

class SearchController extends Controller
{
    public function search(Request $request) {

        $submissions = Submission::search($request->search)->get();

        return [
            'submissions' => $submissions,
        ];
    }
}
