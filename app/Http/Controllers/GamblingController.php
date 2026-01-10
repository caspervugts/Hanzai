<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Race;
use App\Models\Bet;
use App\Models\Horse;
use Auth;
use DB;

class GamblingController extends Controller
{
    public function view(Request $request): View
    {              
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");       

        if(empty($results)){                      
            $race = Race::with('horses')->where('completed', '0')->first();
            $horses = $race->horses;  // Retrieves all horses associated with the race
            // removed debug dd so execution continues
            $bets = Bet::with('horses') 
                        ->where('user_id', Auth::id())
                        ->where('race_id', $race->id)
                        ->get();
            
            $recentlyCompletedRaces = Race::where('completed', '1')->orderBy('id', 'desc')->take(5)->get();
            foreach($recentlyCompletedRaces as $completedRace){
                $winners = Horse::find($completedRace->winner);
                // You can now use $winnerHorse as needed
            }
            #dd($recentlyCompletedRaces);
            return view('gambling', ['user' => $request->user(), 'horses' => $horses, 'bets' => $bets, 'recentlyCompletedRaces' => $recentlyCompletedRaces]);
        }else{
             return view('jail', ['user' => $request->user()]);
        }
    }

    public function placeBet($horseId, Request $request)
    {
        
        $amount = $request->only('amount');

        $userMoney = DB::select("SELECT money FROM users WHERE id = ".Auth::user()->id);
        if($userMoney[0]->money < $amount['amount']){            
            return Redirect::route('gambling')->withErrors(['money' => 'Your broke ass doesn\'t have enough money to place this bet.']);
        }   

        $race = DB::table('races')->select('id')->where('completed', 0)->first();  
       
        // Bet::create([
        //     'amount' => $request->input('amount'),
        //     'horse_id'  => $horseId,
        //     'race_id' => $race->id,
        //     'user_id' => Auth::user()->id 
        // ]);

        $horse = Horse::findOrFail($horseId);

        $horse->bets()->create([
            'amount' => $request->amount,
            'race_id' => $race->id,
            'user_id' => Auth::user()->id 
        ]);

        DB::table('users')->where('id', Auth::user()->id)->decrement('money', $request->amount);
        #$horse->bets()->attach($horseId);

        $race = Race::with('horses')->where('completed', '0')->first();
        $horses = $race->horses;  // Retrieves all horses associated with the race
         
        return Redirect::route('gambling')->withInput(['value' => 'You placed a bet. Good luck!']);
    }

    public function createRace(){
        $closeDate =  Carbon::now()->addSeconds(300);

        $race = Race::with('horses')->where('completed', '0')->first();

        if(!$race){
            $race = new Race();
            $race->closedate = $closeDate ;
            $race->save();

            $race->horses()->attach([1, 2, 3, 4, 5, 6, 7, 8]);
            return;
        }

        $horses = $race->horses->sortBy('odds');

        for($i = 0; $i < count($horses); $i++){
            $horse = $horses->values()[$i];
            
            $number = rand(1, 100);
            
            $horseOdds = $horse->odds*100;
            if($number <= $horseOdds){
                $winner = $horse;
                break;
            }

            if ($i == 7){
                $winner = $horses->values()[$i];
                break;
            }
        }
        
        $payoutHorses = 1 / $winner->odds;
        $betsonWinner = DB::select("SELECT * FROM bets WHERE horse_id = ".$winner->id." AND race_id = ".$race->id);
        $betsOnWinner = collect($betsonWinner);
       
        foreach($betsOnWinner as $bet){
            $payoutAmount = $bet->amount * $payoutHorses;
            
            if(Race::where('id', $race->id) and $race->completed == 0 and User::where('id', $bet->user_id)){
                DB::table('users')
                ->where('id', $bet->user_id)->increment('money', $payoutAmount);
            }
           
        }
        
        DB::table('races')
            ->where('id', $race->id)
            ->update(['completed' => 1, 'winner' => $winner->id]);  

        $race = new Race();
        $race->closedate = $closeDate ;
        $race->save();

        $id = $race->id;

        $race->horses()->attach([1, 2, 3, 4, 5, 6, 7, 8]);
    }
}
