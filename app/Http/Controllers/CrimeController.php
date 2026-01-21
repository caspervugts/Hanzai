<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Car;
use App\Models\City;
use App\Models\Weapon;
use App\Models\PvpItemEvent;
use Auth;
use DB;

class CrimeController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function view(Request $request): View{              
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");
        #dd($timeLeft);

        if(empty($results)){
            $robbery = DB::table('crimes_robbery')->select('difficulty', 'description')->get();
            $cartheft = DB::table('crimes_cartheft')->select('difficulty', 'description')->get();

            if(Auth::user()->gang_id == null){
                $allusers = DB::select("SELECT * FROM users WHERE health > 0 AND city = ".Auth::user()->city."");
            }else{
                $allusers = DB::select("SELECT * FROM users WHERE health > 0 AND city = ".Auth::user()->city." and gang_id != ".Auth::user()->gang_id." or gang_id is null");
            }
            $currentCity = City::where('id', Auth::user()->city)->first();

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
            #dd($eventDescriptions);

            return view('crime', ['user' => $request->user(), 'robdata' => $robbery, 'cardata' => $cartheft, 'users' => $allusers, 'currentCity' => $currentCity, 'previousHits' => $previousHits, 'previousHitsEvents' => $previousHitsEvents, 'eventDescriptions' => $eventDescriptions]);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            #dd($finalTime);
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function performRobbery($whichCrime, Request $request){
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");
        #dd($timeLeft);

        if(empty($results)){
            $crime = DB::table('crimes_robbery')->select('difficulty','min_money','max_money','cooldown','exp','failure','success')->where('id', '=', $whichCrime)->get();
            $roll = rand(1, 100);
            $reward = rand($crime[0]->min_money, $crime[0]->max_money);
            
            $prefectureTax = DB::table('prefectures')->where('id', Auth::user()->prefecture_id)->first()->tax_percentage;
            $prefectureBoss = DB::table('prefectures')->where('id', Auth::user()->prefecture_id)->first()->boss_id;

            if(!$prefectureTax){
                $prefectureTax = 0;
            }
            $bossCut = intval($reward * ($prefectureTax / 100));

            if($prefectureBoss == null){
                $rewardSentence = $crime[0]->success.' ¥'.$reward.'.';
            }else{
                $rewardSentence = $crime[0]->success.' ¥'.$reward.' and gave ¥'.$bossCut.' to the prefecture boss.';
            }
            
            //dd($prefectureTax);
            #$prefectureBossCut = intval($reward * 0.1);
            //Roll to see if crime is succesfull
            if ($roll < ($crime[0]->difficulty + (Auth::user()->exp/10))){
                DB::table('crimes_performed')->insert([
                    'userid' => Auth::user()->id,
                    'crimeid' => $whichCrime,
                    'cash' => $reward,
                    'prefecture_boss_cut' => $bossCut,
                    'prefecture_boss_id' => $prefectureBoss
                ]);

                DB::table('users')
                ->where('id', Auth::user()->id)->increment('money', $reward);

                DB::table('users')
                ->where('id', Auth::user()->id)->increment('exp', $crime[0]->exp);

                if($bossCut > 0){
                    DB::table('users')
                    ->where('id', $prefectureBoss)->increment('money', $bossCut);
                }

                return redirect()->route('crime')->with('success', $rewardSentence);

                // return view('crimeResult', [
                //     'cash' => $reward,
                //     'rewardSentence' => $rewardSentence,
                // ]);
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
            
                #return Redirect::route('crime')->withInput(['value' => 'You placed a bet. Good luck!']);
                #return view('jail')->withErrors(['theft' => $crime[0]->failure]);
                return redirect()->route('crime')->withErrors(['error' => 'You failed to commit the robbery and got caught!']);
                // return view('crimeResult', [
                //     'cash' => $reward,
                //     'rewardSentence' => $crime[0]->failure,
                // ]);
            }
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            #dd($finalTime);
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function performCarTheft($whichCrime, Request $request){
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");
        #dd($timeLeft);

        if(empty($results)){
            $crime = DB::table('crimes_cartheft')->select('difficulty','cooldown','exp','failure','success')->where('id', '=', $whichCrime)->get();
            $roll = rand(1, 100);

            if ($roll < ($crime[0]->difficulty + (Auth::user()->exp/40))){
                $reward = DB::table('cars')->select('id','description','min_money','max_money')->where('difficulty', '=', $whichCrime)->inRandomOrder()->limit(1)->get();  
                #dd($reward);
                $rewardSentence = $crime[0]->success.' '.$reward[0]->description.'!';
                $carValue = rand($reward[0]->min_money, $reward[0]->max_money);

                $user = User::find(Auth::user()->id);            
                $user->cars()->attach($reward[0]->id, ['value' => $carValue]);

                $rewardSentence = $crime[0]->success.' '.$reward[0]->description.' worth '.$carValue.' yen! The car is added to your garage.';

                DB::table('users')
                ->where('id', Auth::user()->id)->increment('exp', $crime[0]->exp);
                
                return redirect()->route('crime')->with('success', $rewardSentence);

                // return view('crimeResult', [
                //     'cash' => $carValue,
                //     'rewardSentence' => $rewardSentence,
                // ]);

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
            
                return redirect()->route('crime')->withErrors(['error' => 'You failed to commit the car theft and got caught!']);
            }
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            #dd($finalTime);
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }
    
    public function scheduleHit($userId, Request $request){
        $targetUser = User::where('id', $userId)->first();

        $scheduledAttack = DB::table('pvp_battle_instance')->where('completed', 0)->where('attacker_id', Auth::user()->id)->first();
        #dd($targetUser->cooldown);
        if($scheduledAttack != null){
            #dd($scheduledAttack);
            return redirect()->route('crime')->withErrors(['error' => 'You already have a hit scheduled.']);
        }

        if($targetUser->cooldown == 1){
            return redirect()->route('crime')->withErrors(['error' => 'User is already targeted for a hit.']);
        }

        if(Auth::user()->cooldown == 1){
            return redirect()->route('crime')->withErrors(['error' => 'You are already feel something bad is going to happen soon..']);
        }

        //update cooldown voor users
        DB::table('users')->where('id', Auth::user()->id)->update(['cooldown' => 1]);
        DB::table('users')->where('id',$userId)->update(['cooldown' => 1]);

        db::table('pvp_battle_instance')->insert([
            'attacker_id' => Auth::user()->id,
            'defender_id' => $targetUser->id,
            'battle_starttime' => Carbon::now()->addSeconds(600),
        ]);
       

        return redirect()->route('crime')->with('success', 'Hit scheduled on ' . Carbon::now()->addSeconds(600) . '.');
    }

    public function performHit(){
        //retrieve battle instance
        $battleInstance = DB::table('pvp_battle_instance')->where('completed', 0)->where('battle_starttime', '<=', Carbon::now());
        #dd($battleInstance);
        // if(!$battleInstance){
        //     return redirect()->route('crime')->with('error', 'Hit not found.'); 
        // }
        // if($battleInstance->completed == 0 && Carbon::now() < Carbon::parse($battleInstance->battle_starttime)){
        //     return redirect()->route('crime')->with('error', 'Hit has already been scheduled.'); 
        // }
        //retrieve attacker and defender
        foreach($battleInstance->get() as $battleInstance){
            $attacker = User::where('id', $battleInstance->attacker_id)->first();
            $defender = User::where('id', $battleInstance->defender_id)->first();
            
            //retrieve inventory items (weapons) for both users
            $attackerWeapons = $attacker->weapons()->whereNull('storage_id')->get();
            $defenderWeapons = $defender->weapons()->whereNull('storage_id')->get();  
            #dd($attackerWeapons);
            
            foreach($attackerWeapons as $weapon){
                #dd($weapon);
                $events = $weapon->events;
                if(empty($events) || count($events) == 0){
                    continue; // nothing to do for this weapon
                }
                
                // Choose a single event per weapon using event_chance as a weight
                $weighted = [];
                foreach($events as $ev){
                    $weighted[] = ['event' => $ev, 'weight' => max(0, (int)$ev->event_chance)];
                }

                $totalWeight = array_sum(array_column($weighted, 'weight'));
                if($totalWeight <= 0){
                    // fallback to uniform random if no weights
                    $selectedEvent = $events[array_rand($events)];
                }else{
                    $r = rand(1, $totalWeight);
                    $cum = 0;
                    $selectedEvent = null;
                    foreach($weighted as $w){
                        $cum += $w['weight'];
                        if($r <= $cum){
                            $selectedEvent = $w['event'];
                            break;
                        }
                    }
                    if(!$selectedEvent){
                        // safety fallback
                        $selectedEvent = end($events);
                    }
                }
                
                // record the move
                DB::table('pvp_battle_moves')->insert([
                    'battle_instance_id' => $battleInstance->id,
                    'move_user_id' => $attacker->id,
                    'move_recipient_id' => $defender->id,
                    'move_event_id' => $selectedEvent->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                //remove attacker weapon
                DB::table('user_weapon')
                    ->where('user_id', $attacker->id)
                    ->where('weapon_id', $weapon->id)
                    ->delete();

                //apply event effects
                if($selectedEvent->event_recipient == 2){ //target
                    DB::table('users')
                        ->where('id', $defender->id)
                        ->decrement('health', $selectedEvent->event_damage);
                }elseif($selectedEvent->event_recipient == 1){ //self
                    DB::table('users')
                        ->where('id', $attacker->id)
                        ->decrement('health', $selectedEvent->event_damage);
                }
            }

            foreach($defenderWeapons as $weapon){
                $events = $weapon->events;
                if(empty($events) || count($events) == 0){
                    continue; // nothing to do for this weapon
                }

                // Choose a single event per weapon using event_chance as a weight
                $weighted = [];
                foreach($events as $ev){
                    $weighted[] = ['event' => $ev, 'weight' => max(0, (int)$ev->event_chance)];
                }

                $totalWeight = array_sum(array_column($weighted, 'weight'));
                if($totalWeight <= 0){
                    // fallback to uniform random if no weights
                    $selectedEvent = $events[array_rand($events)];
                }else{
                    $r = rand(1, $totalWeight);
                    $cum = 0;
                    $selectedEvent = null;
                    foreach($weighted as $w){
                        $cum += $w['weight'];
                        if($r <= $cum){
                            $selectedEvent = $w['event'];
                            break;
                        }
                    }
                    if(!$selectedEvent){
                        // safety fallback
                        $selectedEvent = end($events);
                    }
                }

                // record the move
                DB::table('pvp_battle_moves')->insert([
                    'battle_instance_id' => $battleInstance->id,
                    'move_user_id' => $defender->id,
                    'move_recipient_id' => $attacker->id,
                    'move_event_id' => $selectedEvent->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                //remove defender weapon
                DB::table('user_weapon')
                    ->where('user_id', $defender->id)
                    ->where('weapon_id', $weapon->id)
                    ->delete();

                //apply event effects
                if($selectedEvent->event_recipient == 2){ //target
                    DB::table('users')
                        ->where('id', $attacker->id)
                        ->decrement('health', $selectedEvent->event_damage);                
                }elseif($selectedEvent->event_recipient == 1){ //self
                    DB::table('users')
                        ->where('id', $defender->id)
                        ->decrement('health', $selectedEvent->event_damage);                
                }
            }

            //reset cooldowns
            DB::table('users')->where('id', $attacker->id)->update(['cooldown' => 0]); //reset cooldown after being attacked (dit moet in de toekomst anders)
            DB::table('users')->where('id', $defender->id)->update(['cooldown' => 0]); //reset cooldown (dit moet in the toekomst anders)

            //update battle instance as completed
            DB::table('pvp_battle_instance')
            ->where('id', $battleInstance->id)
            ->update(['completed' => 1]);  
        }
    }
}
