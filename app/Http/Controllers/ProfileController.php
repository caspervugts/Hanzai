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
        
        DB::table('prefectures')
            ->where('boss_id', Auth::user()->id)
            ->update(['boss_id' => null]);

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
        $weapons = $user->weapons()->whereNull('storage_id')->get();

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
        //check if user is in jail             
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            $user = User::find(Auth::user()->id);
            #$cars = $user->cars;  // Retrieves all cars associated with the user
            $cars = DB::table('car_user')
                ->join('cars', 'car_user.car_id', '=', 'cars.id')
                ->where('car_user.user_id', '=', Auth::user()->id)
                ->where('car_user.gang_crime_id', '=', null)
                ->select('cars.*', 'car_user.id as pivot_id', 'car_user.value as value', 'car_user.user_id as user_id', 'car_user.id as car_id')
                ->get();

            return view('garage', [
                'user' => $request->user(), 'cars' => $cars,
            ]);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function sellCar(Request $request, $userId, $carId): RedirectResponse
    {    
        
        
        $user = User::findOrFail(Auth::user()->id);
        #dd($carId, $userId);
        $carValue = DB::table('car_user')->where('id', '=', $carId)->where('user_id', '=', $userId)->get();

        $prefectureTax = DB::table('prefectures')->where('id', Auth::user()->prefecture_id)->first()->tax_percentage;
        $prefectureBoss = DB::table('prefectures')->where('id', Auth::user()->prefecture_id)->first()->boss_id;
        # dd($carValue);
        //$user->cars()->detach($carId);
        
        DB::table('users')->where('id', Auth::user()->id)->increment('money', $carValue[0]->value);
        if($prefectureBoss != null){
            if(!$prefectureTax){
                $prefectureTax = 0;
            }
            $bossCut = intval($carValue[0]->value * ($prefectureTax / 100));

            if($bossCut < 0){
                $rewardSentence = $carValue[0]->value.' ¥'.   $carValue[0]->value.'.';
            }else{
                $rewardSentence = ' ¥'.$carValue[0]->value.' and gave ¥'.$bossCut.' to the prefecture boss.';
            }
        }else{
            $rewardSentence = ' ¥'.$carValue[0]->value.'.';
        }
        $deleted = DB::table('car_user')->where('id', '=', $carId)->where('user_id', '=', $userId)->delete();

        //$cars = $user->cars;  // Retrieves all cars associated with the user
        return Redirect::route('garage')->with('success', 'You have sold your car for '.$rewardSentence);

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
