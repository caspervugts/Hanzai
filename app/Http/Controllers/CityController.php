<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Carbon\Carbon;
use App\Models\User;
USE App\Models\Weapon;
use App\Models\City;
use Auth;
use DB;

class CityController extends Controller
{
    public function view(Request $request): View
    {         
        //check if user is in jail             
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            $allusers = DB::select("SELECT * FROM users WHERE health > 0 and id != ".Auth::user()->id." AND city = ".Auth::user()->city."");
            $currentCity = City::where('id', Auth::user()->city)->first();

            $foodItems = DB::table('foods')->get();
            $weapons = DB::table('weapons')->get();    
            
            return view('city', ['user' => $request->user(), 'users' => $allusers, 'currentCity' => $currentCity, 'foods' => $foodItems, 'weapons' => $weapons]);
        }else{
             return view('jail', ['user' => $request->user()]);
        }
    }

    public function changeCity(Request $request)
    {
        $newCityId = $request->input('city_id');
        $user = User::find(Auth::user()->id);
        $user->city = $newCityId;
        $user->save();

        return Redirect::back()->with('status', 'You have moved to a new city!');
    }

    public function getCities(Request $request)
    {
        $cities = City::all();
        return response()->json($cities);
    }
    
}
