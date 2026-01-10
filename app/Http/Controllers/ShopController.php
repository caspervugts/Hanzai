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
             return view('jail', ['user' => $request->user()]);
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
            
            return Redirect::route('shop')->withInput(['value' => 'You bought the item.']);
        }
        else{
            return Redirect::route('shop')->withErrors(['money' => 'You don\'t have enough money to purchase this item.']);
        }
    }
}
