<?php

namespace App\Http\Controllers;
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



class GangController extends Controller
{
    public function view(Request $request): View{                     
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            $gangs = DB::table('gangs')->where('id', Auth::user()->gang_id)->get();

            if($gangs->isEmpty()){
                $allGangs = DB::table('gangs')->get();

                if(Auth::user()->exp > 1000){
                    $canCreateGang = true;
                    return view('findgang', ['user' => $request->user(), 'gangs' => $allGangs, 'canCreateGang' => $canCreateGang]);
                }

                return view('findgang', ['user' => $request->user(), 'gangs' => $allGangs]);
            }else{
                $gangMembers = DB::table('users')->where('gang_id', Auth::user()->gang_id)->get();
                $boss = DB::table('users')->where('id', $gangs[0]->gang_boss_id)->get();
                $gangCrimes = DB::table('crimes_gang')->get();
                $availableGangMembers = DB::table('users')->where('gang_id', Auth::user()->gang_id)->get();

                $ongoingCrime = DB::table('crimes_gang_performed')
                ->where('user_id', Auth::user()->id)
                ->where('completed', 0)
                ->first();           
                
                $openInvite = DB::table('crimes_gang_invites')
                ->where('user_id', Auth::user()->id)
                ->where('accepted', 0)
                ->where('completed', 0)
                ->get();
                
                $recentGangCrimes = DB::table('crimes_gang_performed')->join('crimes_gang', 'crimes_gang_performed.gang_crime_id', '=', 'crimes_gang.id')->where('crimes_gang_performed.completed', 2)->orderBy('crimes_gang_performed.id', 'desc')->limit(3)->get();

                $membersInJail = DB::table('crimes_performed')->where('releasedate', '>', Carbon::now())->get();
                #dd($membersInJail);
                if($boss[0]->id == Auth::user()->id){                    
                    $approvalRequests = DB::table('gang_approval')->join('users', 'gang_approval.user_id', '=', 'users.id')->where('gang_approval.gang_id', Auth::user()->gang_id)->where('gang_approval.status', '0')->get();
                    $userBoss = true;
                    return view('gang', ['user' => $request->user(), 'openInvite'=>$openInvite, 'ongoingCrime'=>$ongoingCrime, 'gangCrimes' => $gangCrimes, 'gangMembers' => $gangMembers, 'gangs' => $gangs, 'isBoss' => $boss, 'approvalRequests' => $approvalRequests, 'userBoss' => $userBoss, 'recentGangCrimes' => $recentGangCrimes, 'membersInJail' => $membersInJail]);
                }

                return view('gang', ['user' => $request->user(), 'openInvite'=>$openInvite, 'gangCrimes' => $gangCrimes, 'ongoingCrime'=>$ongoingCrime, 'gangMembers' => $gangMembers, 'gangs' => $gangs, 'isBoss' => $boss, 'recentGangCrimes' => $recentGangCrimes, 'membersInJail' => $membersInJail]);
            }
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';

            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function applyToGang($gangId, Request $request){         
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");
        $activeApplication = DB::table('gang_approval')->where('user_id', Auth::user()->id)->where('status', '0')->first();
        #dd($activeApplication);
        if($activeApplication !== null){
            return Redirect::route('gang')->withErrors(['application' => 'You already have an active gang application. Please wait for approval.']);
        }

        if(empty($results)){
            // DB::table('users')
            //     ->where('id', Auth::user()->id)
            //     ->update(['gang_id' => $gangId]);
            DB::table('gang_approval')->insert([
                'user_id' => Auth::user()->id,
                'gang_id' => $gangId,  
                'status' => '0'            
            ]);

            return Redirect::route('gang')->withInput(['value' => 'You have applied to join the gang.']);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';

            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function approveApplication($userId, Request $request){         
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            DB::table('users')
                ->where('id', $userId)
                ->update(['gang_id' => Auth::user()->gang_id]);

            DB::table('gang_approval')
                ->where('user_id', $userId)
                ->update(['status' => '1']);

            return Redirect::route('gang')->withInput(['value' => 'You have approved the gang application.']);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';

            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function rejectApplication($userId, Request $request){         
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            DB::table('gang_approval')
                ->where('user_id', $userId)
                ->update(['status' => '2']);

            return Redirect::route('gang')->withInput(['value' => 'You have rejected the gang application.']);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function leaveGang($gangId, Request $request){         
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            DB::table('users')
                ->where('id', Auth::user()->id)
                ->update(['gang_id' => null]);

            if(Auth::user()->id == DB::table('gangs')->where('id', $gangId)->value('gang_boss_id')){
                DB::table('gangs')
                    ->where('id', $gangId)
                    ->delete();

                DB::table('users')
                    ->where('gang_id', $gangId)
                    ->update(['gang_id' => null]);
            }

            return Redirect::route('gang')->withInput(['value' => 'You have left the gang.']);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function createGang(Request $request): View
    {         
        $gangId = DB::table('gangs')->insertGetId([
            'name' => $request->input('name'),
            'gang_boss_id' => Auth::id(),
            'total_gang_exp' => 0,
            'gang_money' => 0,
            'description' => $request->input('description')
        ]);

        DB::table('users')->where('id', Auth::id())->update(['gang_id' => $gangId]);

        $gangs = DB::table('gangs')->where('id', $gangId)->get();
       
        $gangMembers = DB::table('users')->where('gang_id', Auth::user()->gang_id)->get();
        $boss = DB::table('users')->where('id', $gangs[0]->gang_boss_id)->get();
        
        if($boss[0]->id == Auth::user()->id){                    
            $approvalRequests = DB::table('gang_approval')->join('users', 'gang_approval.user_id', '=', 'users.id')->where('gang_approval.gang_id', Auth::user()->gang_id)->where('gang_approval.status', '0')->get();
            $userBoss = true;
            return view('gang', ['user' => $request->user(), 'gangMembers' => $gangMembers, 'gangs' => $gangs, 'isBoss' => $boss, 'approvalRequests' => $approvalRequests, 'userBoss' => $userBoss]);
        }

        return view('gang', ['user' => $request->user(), 'gangMembers' => $gangMembers, 'gangs' => $gangs, 'isBoss' => $boss]);
    }

    public function startGangCrime($crimeId, Request $request){         
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            $ongoingCrime = DB::table('crimes_gang_performed')
            ->where('user_id', Auth::user()->id)
            ->where('completed', 0)
            ->first();
            
            if($ongoingCrime !== null){
                return Redirect::route('gang')->withErrors(['gangcrime' => 'You already have an ongoing gang crime.']);
            }

            $gangId = Auth::user()->gang_id;
            $availableGangMembers = DB::table('users')
            ->where('gang_id', $gangId)
            ->where('prefecture_id', Auth::user()->prefecture_id)
            ->where('id', '!=', Auth::user()->id)
            ->get();
            $availableCars = Auth::user()->cars->where('storage_id', null);  
            $availableWeapons = Auth::user()->weapons->where('storage_id', null);       
            $crimeDetails = DB::table('crimes_gang')->where('id', $crimeId)->first();



            return view('gangcrime', ['user' => $request->user(), 'availableGangMembers' => $availableGangMembers, 'availableCars' => $availableCars, 'availableWeapons' => $availableWeapons, 'crimeDetails' => $crimeDetails]);
            
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function initiateGangCrime(Request $request){         
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            $gangMembers = $request->input('gang_members');
            $gangCars = $request->input('gang_cars');
            $gangWeapons = $request->input('gang_weapons');
            $crimeId = $request->input('crime_id');

            #dd($gangMembers, $gangCars, $gangWeapons, $crimeId);

            $crimeDetails = DB::table('crimes_gang')->where('id', $crimeId)->first();
            $weaponCount = count($gangWeapons);
            $carCount = count($gangCars);  
            #dd($weaponCount, $carCount);
            if(count($gangMembers) + 1 < $crimeDetails->required_gang_size){                
                return Redirect::back()->withErrors(['gangcrime' => 'Not enough gang members selected for this crime. Minimum required: '.$crimeDetails->required_gang_size]);
            }

            if($weaponCount < $crimeDetails->required_weapons){
                return Redirect::back()->withErrors(['gangcrime' => 'Not enough weapons selected for this crime. Minimum required: '.$crimeDetails->required_weapons]);
            }

            if($carCount < $crimeDetails->required_cars){            
                return Redirect::back()->withErrors(['gangcrime' => 'Not enough cars selected for this crime. Minimum required: '.$crimeDetails->required_cars]);
            }
            #dd($gangMembers, $gangCars, $gangWeapons, $crimeId);
            $gangCrimePerformedId = DB::table('crimes_gang_performed')->insertGetId([
                'user_id' => Auth::user()->id,
                'gang_crime_id' => $crimeId,  
                'completed' => 0             
            ]);

            foreach($gangMembers as $member){
                DB::table('crimes_gang_invites')->insert([
                    'user_id' => $member,
                    'gang_crime_id' => $gangCrimePerformedId,  
                    'accepted' => 0,  
                    'completed' => 0               
                ]);
            }

            //insert thyself
            DB::table('crimes_gang_invites')->insert([
                'user_id' => Auth::user()->id,
                'gang_crime_id' => $gangCrimePerformedId,  
                'accepted' => 1,  
                'completed' => 0               
            ]);

            foreach($gangCars as $car){
                $carStorageId = DB::table('crimes_gang_storage')->insertGetId([
                    'user_id' => Auth::user()->id,
                    'gang_crime_id' => $gangCrimePerformedId,  
                    'car_id' => $car,
                    'weapon_id' => null              
                ]);

                DB::table('car_user')->where('user_id', Auth::user()->id)->where('car_id', $car)->update(['storage_id' => $carStorageId, 'gang_crime_id' => $gangCrimePerformedId]);
            }   

            foreach($gangWeapons as $weapon){
                $weaponStorageId = DB::table('crimes_gang_storage')->insertGetId([
                    'user_id' => Auth::user()->id,
                    'gang_crime_id' => $gangCrimePerformedId,  
                    'car_id' => null,
                    'weapon_id' => $weapon              
                ]);

                DB::table('user_weapon')->where('user_id', Auth::user()->id)->where('weapon_id', $weapon)->update(['storage_id' => $weaponStorageId, 'gang_crime_id' => $gangCrimePerformedId]);
            }

             DB::table('users')->where('id', Auth::user()->id)->decrement('money', $crimeDetails->required_money);

            return Redirect::route('gang')->withInput(['value' => 'Gang crime initiated.']);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';

            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function acceptGangCrime($gangCrimeId, Request $request){         
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            DB::table('crimes_gang_invites')
                ->where('user_id', Auth::user()->id)
                ->where('gang_crime_id', $gangCrimeId)
                ->update(['accepted' => 1]);

            $openInvites = DB::table('crimes_gang_invites')
                ->where('gang_crime_id', $gangCrimeId)->where('accepted', 0)->get();
            
                if($openInvites->isEmpty()){
                DB::table('crimes_gang_performed')
                ->where('id', $gangCrimeId)
                ->update(['completed' => 1]);
            }
            
            return Redirect::route('gang')->withInput(['value' => 'You have accepted the gang crime invitation. The crime will commence once all members have accepted.']);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }   

    public function rejectGangCrime($gangCrimeId , Request $request){         
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            DB::table('crimes_gang_invites')
                ->where('user_id', Auth::user()->id)
                ->where('gang_crime_id', $gangCrimeId)
                ->update(['accepted' => 2]);

            DB::table('crimes_gang_performed')
                ->where('id', $gangCrimeId)
                ->update(['completed' => 1]);
            return Redirect::route('gang')->withInput(['value' => 'You have rejected the gang crime invitation.']);
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function executeGangCrime(){                
        //get all open gang crime
        $ongoingCrime = DB::table('crimes_gang_performed')            
        ->where('completed', 1)
        ->get();

        foreach($ongoingCrime as $crime){
            //check if members have declined    
            $declinedInvites = DB::table('crimes_gang_invites')
            ->where('gang_crime_id', $crime->id)
            ->where('accepted', 2)
            ->get();

            if(!$declinedInvites->isEmpty()){
                //mark gang crime as failed         
                #dd($declinedInvites, $crime);           
                DB::table('crimes_gang_performed')
                ->where('id', $crime->id)
                ->update(['completed' => 2, 'result' => 'The gang crime has failed due to a member declining the invitation.']);            

                DB::table('crimes_gang_invites')
                ->where('gang_crime_id', $crime->id)
                ->update(['completed' => 2]);  

                DB::table('crimes_gang_storage')
                ->where('gang_crime_id', $crime->id)
                ->delete();
        
                DB::table('user_weapon')
                ->where('gang_crime_id', $crime->id)
                ->update(['gang_crime_id' => null, 'storage_id' => null]);

                DB::table('car_user')
                ->where('gang_crime_id', $crime->id)
                ->update(['gang_crime_id' => null, 'storage_id' => null]);
                
                $gangCrime = DB::table('crimes_gang')->where('id', '=', $crime->gang_crime_id)->get();

                DB::table('users')->where('id', $crime->user_id)->increment('money', $gangCrime[0]->required_money);
            }else{
                $gangCrime = DB::table('crimes_gang')->where('id', '=', $crime->gang_crime_id)->get();
                $roll = rand(1, 100);
                $reward = rand($gangCrime[0]->min_money, $gangCrime[0]->max_money);
                
                $rewardSentence = $gangCrime[0]->success.' '.$reward.' yen!';
                //Roll to see if crime is succesfull
                if ($roll < $gangCrime[0]->difficulty){
                    DB::table('crimes_gang_performed')
                    ->where('id', $crime->id)
                    ->update(['completed' => 2, 'result' => $rewardSentence, 'cash' => $reward]);
                
                    $crewMembers = DB::table('crimes_gang_invites')
                    ->where('gang_crime_id', $crime->id)
                    ->get();

                    foreach($crewMembers as $member){                           
                        DB::table('users')
                        ->where('id', $member->user_id)->increment('money', intval($reward / (count($crewMembers))));

                        DB::table('users')
                        ->where('id', $member->user_id)->increment('exp', intval($gangCrime[0]->exp));

                        DB::table('crimes_performed')->insert([
                        'userid' => $member->user_id,
                        'gangcrimeid' => $crime->id,
                        'cash' => $reward / (count($crewMembers))]);
                    }

                    DB::table('user_weapon')
                    ->where('gang_crime_id', $crime->id)
                    ->update(['gang_crime_id' => null, 'storage_id' => null]);

                    DB::table('car_user')
                    ->where('gang_crime_id', $crime->id)
                    ->update(['gang_crime_id' => null, 'storage_id' => null]);
                    
                    #return redirect()->route('crime')->with('success', $rewardSentence);

                    // return view('crimeResult', [
                    //     'cash' => $reward,
                    //     'rewardSentence' => $rewardSentence,
                    // ]);
                }else{
                    $releaseDate =  Carbon::now()->addSeconds($gangCrime[0]->cooldown);
                    $gangCrime = DB::table('crimes_gang')->where('id', '=', $crime->gang_crime_id)->get();

                    DB::table('crimes_gang_performed')
                    ->where('id', $crime->id)
                    ->update(['completed' => 2, 'result' => $gangCrime[0]->failure, 'cash' => 0, 'releasedate' => $releaseDate]);

                    $crewMembers = DB::table('crimes_gang_invites')
                    ->where('gang_crime_id', $crime->id)
                    ->get();

                    foreach($crewMembers as $member){                                                                               
                        DB::table('crimes_performed')->insert([
                            'userid' => $member->user_id,
                            'gangcrimeid' => $crime->id,
                            'cash' => 0,
                            'releasedate' => $releaseDate,
                            'createdate' => Carbon::now()            
                        ]);

                        DB::table('users')
                        ->where('id', $member->user_id)->increment('exp', $gangCrime[0]->exp);   
                    }

                    DB::table('user_weapon')
                    ->where('gang_crime_id', $crime->id)
                    ->delete();

                    DB::table('car_user')
                    ->where('gang_crime_id', $crime->id)
                    ->delete();
                    
                    #$reward = 0;                                            
                
                    #return Redirect::route('crime')->withInput(['value' => 'You placed a bet. Good luck!']);
                    #return view('jail')->withErrors(['theft' => $crime[0]->failure]);
                    #return redirect()->route('crime')->withErrors(['error' => 'You failed to commit the robbery and got caught!']);
                    // return view('crimeResult', [
                    //     'cash' => $reward,
                    //     'rewardSentence' => $crime[0]->failure,
                    // ]);
                }
            }
            
        }
        //execute gang crime logic here

        #return Redirect::route('gang')->withInput(['value' => 'Gang crime executed.']);
    
    }

    public function bailMember($userId, Request $request){         
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            $bailAmount = 20000; // Fixed bail amount, can be modified as needed

            $gangMoney = DB::select("SELECT gang_money FROM gangs WHERE id = ".Auth::user()->gang_id);

           $jailRecord = DB::table('crimes_performed')->where('userid', $userId)->where('releasedate', '>', now())->first();
            #dd($jailRecord);
            if($jailRecord === null){

                #dd('here');
                return Redirect::route('gang')->with(['error' => 'This gang member is not in jail.']);
                
            }

            if($gangMoney[0]->gang_money >= $bailAmount){            
                DB::table('gangs')
                    ->where('id', Auth::user()->gang_id)->decrement('gang_money', $bailAmount);
                    
                DB::table('crimes_performed')
                    ->where('userid', $userId)
                    ->update(['releasedate' => Carbon::now()]); 

                return Redirect::route('gang')->with(['success' => 'You have posted bail for the gang member using ¥'.$bailAmount.' from the gang\'s funds.']);
            }
            else{
                return Redirect::route('gang')->with(['error' => 'The gang doesn\'t have enough money to post bail for this member.']);
            }
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

    public function depositToGang($gangId, Request $request){         
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            $amount = $request->input('amount');

            $userMoney = DB::select("SELECT money FROM users WHERE id = ".Auth::user()->id);

            if($userMoney[0]->money >= $amount){            
                DB::table('users')
                    ->where('id', Auth::user()->id)->decrement('money', $amount);

                DB::table('gangs')
                    ->where('id', $gangId)
                    ->increment('gang_money', $amount); 

                return Redirect::route('gang')->with(['success' => 'You have deposited ¥'.$amount.' into the gang\'s funds.']);
            }
            else{
                return Redirect::route('gang')->with(['error' => 'You don\'t have enough money to deposit that amount.']);
            }
        }else{
            $timeLeft = Carbon::parse($results[0]->releasedate)->diffInSeconds(Carbon::now());
            $timeLeft = substr($timeLeft, 1, 25);
            $finalTime = substr($timeLeft / 60, 0, 2).' minutes and '.($timeLeft % 60).' seconds';
            return view('jail', ['user' => $request->user(), 'timeLeft' => $finalTime]);
        }
    }

}
