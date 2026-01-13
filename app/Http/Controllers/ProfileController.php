<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\PvpItemEvent;
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

    public function help(Request $request): View
    {
        return view('help', [
            'user' => $request->user(),
        ]);
    }

    public function death(Request $request): View
    {
        $combatLogs = DB::table('pvp_battle_instance')
            ->where('attacker_id', Auth::user()->id)
            ->orWhere('defender_id', Auth::user()->id)            
            ->orderBy('id', 'desc')
            ->get();

        $HitsEvents = [];
        foreach($combatLogs as $hit){
            $events = DB::table('pvp_battle_moves')
            ->where('battle_instance_id', $hit->id)
            ->get();
            $HitsEvents[$hit->id] = $events;
        }
        #dd($events);
        
        $eventDescriptions = [];
        foreach($HitsEvents as $hitId => $events){
            foreach($events as $event){
                $eventDetail = PvpItemEvent::where('id', $event->move_event_id)->first();
                // Store both the event detail and the user who made the move so the view can reference the move_user_id
                $eventDescriptions[$hitId][] = (object)[
                    'event_detail' => $eventDetail,
                    'move_user_id' => $event->move_user_id,
                    'move_user_name' => User::where('id', $event->move_user_id)->first()->name,
                    'move_recipient_name' => User::where('id', $event->move_recipient_id)->first()->name,
                ];
            }
        }        
        #$aliveTime = $request->user()->time_of_death->addHours(24);
        $aliveTime = $request->user()->time_of_death;
        #d($event->move_recipient_id->first()->name);
        return view('death', [
            'user' => $request->user(), 'combats' => $combatLogs, 'HitsEvents' => $HitsEvents, 'eventDescriptions' => $eventDescriptions, 'aliveTime' => $aliveTime
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function view(Request $request): View
    {
        $user = User::find(Auth::user()->id);
        $weapons = $user->weapons;

        $previousHits = DB::table('pvp_battle_instance')
        ->where('completed', 1)
        ->where('attacker_id', Auth::user()->id)
        ->orWhere('defender_id', Auth::user()->id)            
        ->orderBy('id', 'desc')->take(1)->get();
        #dd($previousHits);

        $previousHitsEvents = [];
        foreach($previousHits as $hit){
            $events = DB::table('pvp_battle_moves')
            ->where('battle_instance_id', $hit->id)
            ->get();
            $previousHitsEvents[$hit->id] = $events;
        }
        #dd($previousHits);
        
        $eventDescriptions = [];
        foreach($previousHitsEvents as $hitId => $events){
            foreach($events as $event){
                $eventDetail = PvpItemEvent::where('id', $event->move_event_id)->first();
                // Store both the event detail and the user who made the move so the view can reference the move_user_id
                $eventDescriptions[$hitId][] = (object)[
                    'event_detail' => $eventDetail,
                    'move_user_id' => $event->move_user_id,
                    'move_user_name' => User::where('id', $event->move_user_id)->first()->name,
                    'move_recipient_name' => User::where('id', $event->move_recipient_id)->first()->name,
                ];
            }
        }
        
        return view('dashboard', [
            'user' => $request->user(), 'weapons' => $weapons, 'previousHits' => $previousHits, 'previousHitsEvents' => $previousHitsEvents, 'eventDescriptions' => $eventDescriptions
        ]);
    }
    
    public function leaderboard(Request $request): View
    {
        $users = User::orderBy('money', 'desc')->get();

        return view('leaderboard', [
            'users' => $users,
        ]);
    }

    public function emptyTimeOfDeath(){
        $deadUsers = DB::table('users')
            ->where('time_of_death', '<=', now())
            ->get();
        
        DB::table('users')
            ->where('time_of_death', '<=', now())
            ->update(['time_of_death' => null
            ,'alive' => 1
            ,'health' => 100
            ,'money' => 0]);  

        //remove inventory
        DB::table('user_weapon')
            ->where('user_id', $deadUsers->id)
            ->delete();
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

    public function combatlog(Request $request): View
    {
        $combatLogs = DB::table('pvp_battle_instance')
            ->where('attacker_id', Auth::user()->id)
            ->orWhere('defender_id', Auth::user()->id)            
            ->orderBy('id', 'desc')
            ->get();

        $HitsEvents = [];
        foreach($combatLogs as $hit){
            $events = DB::table('pvp_battle_moves')
            ->where('battle_instance_id', $hit->id)
            ->get();
            $HitsEvents[$hit->id] = $events;
        }
        
        $eventDescriptions = [];
        foreach($HitsEvents as $hitId => $events){
            foreach($events as $event){
                $eventDetail = PvpItemEvent::where('id', $event->move_event_id)->first();
                // Store both the event detail and the user who made the move so the view can reference the move_user_id
                $eventDescriptions[$hitId][] = (object)[
                    'event_detail' => $eventDetail,
                    'move_user_id' => $event->move_user_id,
                    'move_user_name' => User::where('id', $event->move_user_id)->first()->name,
                    'move_recipient_name' => User::where('id', $event->move_recipient_id)->first()->name,
                ];
            }
        }

        return view('combatlog', [
            'combats' => $combatLogs, 'HitsEvents' => $HitsEvents, 'eventDescriptions' => $eventDescriptions
        ]);
    }
}
