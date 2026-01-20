<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Gang;
use App\Models\Race;
use App\Models\Bet;
use App\Models\Horse;
use Auth;
use DB;

class PrefectureController extends Controller
{
    public function viewPrefecture(Request $request): View
    {
        //check if user is in jail             
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            $allusers = DB::select("SELECT * FROM users WHERE health > 0 and id != ".Auth::user()->id." AND prefecture_id = ".Auth::user()->prefecture_id."");
            $currentPrefecture = DB::table('prefectures')->where('id', Auth::user()->prefecture_id)->first();
            
            $foodItems = DB::table('foods')->get();
            $weapons = DB::table('weapons')->get();    
            
            return view('prefecture', ['user' => $request->user(), 'users' => $allusers, 'currentPrefecture' => $currentPrefecture, 'foods' => $foodItems, 'weapons' => $weapons]);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function viewTravel(Request $request): View
    {
        //check if user is in jail             
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            $prefectures = DB::table('prefectures')->get();
            return view('travel', ['user' => $request->user(), 'prefectures' => $prefectures]);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }
    
    public function claimPrefecture(Request $request, $prefectureId)
    {
        $user = User::find(Auth::user()->id);
        $prefecture = DB::table('prefectures')->where('id', $prefectureId)->first();

        if($user->money >= $prefecture->investment_cost){
            //deduct money
            DB::table('users')
                ->where('id', Auth::user()->id)
                ->decrement('money', $prefecture->investment_cost);

            //update user's prefecture
            DB::table('prefectures')
                ->where('id', $prefectureId)
                ->update(['boss_id' => Auth::user()->id]);

            return Redirect::route('prefecture')->with(['success' => 'You have successfully claimed the prefecture of '.$prefecture->name.'.']);
        }
        else{
            return Redirect::route('prefecture')->withErrors(['money' => 'You don\'t have enough money to claim this prefecture.']);
        }
    }

    public function travelToPrefecture(Request $request, $prefectureId)
    {
        $user = User::find(Auth::user()->id);
        $prefecture = DB::table('prefectures')->where('id', $prefectureId)->first();

        if($user->prefecture_id == $prefectureId){
            return Redirect::route('travel')->withErrors(['travel' => 'You are already in this prefecture.']);
        }

        if($user->money >= $prefecture->travel_cost){
            //deduct money
            DB::table('users')
                ->where('id', Auth::user()->id)
                ->decrement('money', $prefecture->travel_cost);

            //update user's prefecture
            DB::table('users')
                ->where('id', Auth::user()->id)
                ->update(['prefecture_id' => $prefectureId]);

            return Redirect::route('prefecture')->with(['success' => 'Welcome to '.$prefecture->name.'.']);
        }
        else{
            return Redirect::route('travel')->withErrors(['money' => 'You don\'t have enough money to travel to this prefecture.']);
        }
    }
}
