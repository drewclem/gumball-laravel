<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Actions\StoreCollectionAction;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */



    public function index() {
        return Inertia::render('User/Settings');
    }

    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, StoreCollectionAction $createCollection)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'terms' => true,
        ]);

        event(new Registered($user));

        $user->createAsStripeCustomer();

        $current_date = Carbon::now()->toDateTimeString();
        
        $createCollection->handle([
            'user' => $user,
            'start_date' => $current_date,
            'end_date' => null
        ]);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
    
    public function show(Request $request) {
        return Inertia::render('User/SettingsEdit');
    }

    public function edit(Request $request) {

        $user = $request->user();

        $user->update([
            'name'=> $request->name,
            'instagram_url'=> $request->instagram_url,
            'tiktok_url'=> $request->tiktok_url,
            'twitter_url'=> $request->twitter_url,
            'facebook_url'=> $request->facebook_url,
            'prescreen'=> $request->prescreen,
            'decline_response'=> $request->decline_response
        ]);

        return redirect('/settings');
    }

    public function uploadAvatar(Request $request) {

        $user = $request->user();
        $avatarPath = null;
        
        $request->validate([
            'avatar'=> ['nullable', 'mimes:jpg,jpeg,png,gif', 'max:2048']
            ]);

        if ($request->hasFile('avatar')) {
            $result = $request->file('avatar')->storeOnCloudinary('avatars');

            $avatarPath = $result->getSecurePath();
        };

        $user->update([
            'avatar_path'=> $avatarPath
        ]);
    }

    public function setViewMode(Request $request) {

        $user = $request->user();

        $user->update([
            'default_view' => !$user->default_view
        ]);
    }
}
