<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Car;

use DB;


class ShopController extends Controller
{
    public function viewShop(Request $request): View
    {
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            $weapons = DB::table('weapons')->get();            

            return view('shop', ['user' => $request->user(), 'weapons' => $weapons]);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';

            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function buyWeapon(Request $request, $weaponId, $value): RedirectResponse
    {    
        $userMoney = DB::select("SELECT money FROM users WHERE id = ".Auth::user()->id);
        
        if($userMoney[0]->money > $value){            
            DB::table('users')
                ->where('id', Auth::user()->id)->decrement('money', $value);
            
            $user = User::findOrFail(Auth::user()->id);

            DB::table('user_weapon')->insert([
                'user_id' => Auth::user()->id,
                'weapon_id' => $weaponId,
                'ammo_amount' => 100    
            ]);

            return Redirect::route('prefecture')->with(['success' => 'You bought the weapon.']);
        }
        else{
            return Redirect::route('prefecture')->withErrors(['money' => 'You don\'t have enough money to purchase this item.']);
        }
    }

    public function buyFood($foodId)
    {
        $user = User::find(Auth::user()->id);
        $food = DB::table('foods')->where('id', $foodId)->first();

        if($user->money < $food->value){
            return Redirect::route('prefecture')->withErrors(['You do not have enough money to buy this food item.']);
        }

        // Deduct money and restore health
        $user->money -= $food->value;
        $user->health += $food->health_restore;
        if($user->health > 100){
            $user->health = 100; // Cap health at 100
        }
        $user->save();

        return Redirect::route('prefecture')->with('success', 'You have successfully bought '.$food->name.' and restored '.$food->health_restore.' health!');
    }
}
