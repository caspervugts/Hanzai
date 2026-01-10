<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Carbon\Carbon;
use App\Models\User;
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
            $allusers = DB::select("SELECT * FROM users WHERE health > 0 AND city = ".Auth::user()->city."");
            $currentCity = City::where('id', Auth::user()->city)->first();
            
            return view('city', ['user' => $request->user(), 'users' => $allusers, 'currentCity' => $currentCity]);
        }else{
             return view('jail', ['user' => $request->user()]);
        }
    }
}
