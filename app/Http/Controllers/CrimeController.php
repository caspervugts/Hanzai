<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Car;
use Auth;
use DB;

class CrimeController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function view(Request $request): View
    {              
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            $robbery = DB::table('crimes_robbery')->select('difficulty', 'description')->get();
            $cartheft = DB::table('crimes_cartheft')->select('difficulty', 'description')->get();

            return view('crime', ['user' => $request->user(), 'robdata' => $robbery, 'cardata' => $cartheft]);
        }else{
             return view('jail', ['user' => $request->user()]);
        }
    }

    public function performRobbery($whichCrime, Request $request)
    {
        $crime = DB::table('crimes_robbery')->select('difficulty','min_money','max_money','cooldown','exp','failure','success')->where('id', '=', $whichCrime)->get();
        $roll = rand(1, 100);
        $reward = rand($crime[0]->min_money, $crime[0]->max_money);
        
        $rewardSentence = $crime[0]->success.' '.$reward.' yen!';

        //Roll to see if crime is succesfull
        if ($roll < $crime[0]->difficulty){
            DB::table('crimes_performed')->insert([
                'userid' => Auth::user()->id,
                'crimeid' => $whichCrime,
                'cash' => $reward          
            ]);

            DB::table('users')
            ->where('id', Auth::user()->id)->increment('money', $reward);

            DB::table('users')
            ->where('id', Auth::user()->id)->increment('exp', $crime[0]->exp);

            return view('crimeResult', [
                'cash' => $reward,
                'rewardSentence' => $rewardSentence,
            ]);
        }else{
            $releaseDate =  Carbon::now()->addSeconds($crime[0]->cooldown);
            DB::table('crimes_performed')->insert([
                'userid' => Auth::user()->id,
                'crimeid' => $whichCrime,
                'cash' => 0,
                'releasedate' => $releaseDate            
            ]);

            $reward = 0;
            DB::table('users')
            ->where('id', Auth::user()->id)->increment('exp', $crime[0]->exp);
           
            return view('crimeResult', [
                'cash' => $reward,
                'rewardSentence' => $crime[0]->failure,
            ]);
        }
    }

    public function performCarTheft($whichCrime, Request $request){
        $crime = DB::table('crimes_cartheft')->select('difficulty','cooldown','exp','failure','success')->where('id', '=', $whichCrime)->get();
        $roll = rand(1, 100);
        
        if ($roll < $crime[0]->difficulty){
            $reward = DB::table('cars')->select('id','description','min_money','max_money')->where('difficulty', '=', $crime[0]->difficulty)->inRandomOrder()->limit(1)->get();  
            $rewardSentence = $crime[0]->success.' '.$reward[0]->description.'!';
            $carValue = rand($reward[0]->min_money, $reward[0]->max_money);

            $user = User::find(Auth::user()->id);            
            $user->cars()->attach($reward[0]->id, ['value' => $carValue]);

            $rewardSentence = $crime[0]->success.' '.$reward[0]->description.' worth '.$carValue.' yen! The car is added to your garage.';

            DB::table('users')
            ->where('id', Auth::user()->id)->increment('exp', $crime[0]->exp);
            
            return view('crimeResult', [
                'cash' => $carValue,
                'rewardSentence' => $rewardSentence,
            ]);

        }else{
            $releaseDate =  Carbon::now()->addSeconds($crime[0]->cooldown);
            DB::table('crimes_performed')->insert([
                'userid' => Auth::user()->id,
                'crimeid' => $whichCrime,
                'cash' => 0,
                'releasedate' => $releaseDate            
            ]);

            $reward = 0;
            DB::table('users')
            ->where('id', Auth::user()->id)->increment('exp', $crime[0]->exp);
           
            return view('crimeResult', [
                'cash' => $reward,
                'rewardSentence' => $crime[0]->failure,
            ]);
        }
    }
}
