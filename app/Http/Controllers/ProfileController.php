<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Car;
use DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function view(Request $request): View
    {
        $user = User::find(Auth::user()->id);
        $weapons = $user->weapons;

        return view('dashboard', [
            'user' => $request->user(), 'weapons' => $weapons,
        ]);
    }
    
    public function viewGarage(Request $request): View
    {
        $user = User::find(Auth::user()->id);
        $cars = $user->cars;  // Retrieves all cars associated with the user

        return view('garage', [
            'user' => $request->user(), 'cars' => $cars,
        ]);
    }

    public function sellCar(Request $request, $carId, $value): RedirectResponse
    {    
        DB::table('users')
            ->where('id', Auth::user()->id)->increment('money', $value);
        
        $user = User::findOrFail(Auth::user()->id);
        //dd($carId);
        //$user->cars()->detach($carId);

        $deleted = DB::table('car_user')->where('id', '=', $carId)->delete();

        //$cars = $user->cars;  // Retrieves all cars associated with the user
        return Redirect::route('garage');

        //return Redirect::route('garage');
        // return view('garage', [
        //     'user' => $request->user(), 'cars' => $cars,
        // ]);
    }

    /**D
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
