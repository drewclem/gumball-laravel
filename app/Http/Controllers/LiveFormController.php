<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LiveFormController extends Controller
{
    public function index(Request $request) {
        $raw_username = $request->getPathInfo();
        $username = ltrim($raw_username, '/');
        
        if(User::where('username', $username)->exists()) {
            
            $user = User::where('username', $username)->get(['username', 'id', 'prescreen', 'avatar_path']);
            $collection = Collection::active()->where('user_id', $user[0]->id)->first(['id']);
            
            return Inertia::render('User/UserForm', [
                'user' => $user[0],
                'collection_id' => $collection ? $collection[0] : null
                ]);
        } else {
            return Inertia::render('NotFound');
        }
    }
}
