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
    public function view(Request $request): View
    {         
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
                
                if($boss[0]->id == Auth::user()->id){                    
                    $approvalRequests = DB::table('gang_approval')->join('users', 'gang_approval.user_id', '=', 'users.id')->where('gang_approval.gang_id', Auth::user()->gang_id)->where('gang_approval.status', '0')->get();
                    $userBoss = true;
                    return view('gang', ['user' => $request->user(), 'gangMembers' => $gangMembers, 'gangs' => $gangs, 'isBoss' => $boss, 'approvalRequests' => $approvalRequests, 'userBoss' => $userBoss]);
                }

                return view('gang', ['user' => $request->user(), 'gangMembers' => $gangMembers, 'gangs' => $gangs, 'isBoss' => $boss]);
            }
        }else{
             return view('jail', ['user' => $request->user()]);
        }
    }

    public function applyToGang($gangId, Request $request)
    {         
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
             return view('jail', ['user' => $request->user()]);
        }
    }

    public function approveApplication($userId, Request $request)
    {         
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
             return view('jail', ['user' => $request->user()]);
        }
    }

    public function rejectApplication($userId, Request $request)
    {         
        //check if user is in jail     
        $results = DB::select("SELECT * FROM crimes_performed WHERE userid = ".Auth::user()->id." and releasedate > now()");

        if(empty($results)){
            DB::table('gang_approval')
                ->where('user_id', $userId)
                ->update(['status' => '2']);

            return Redirect::route('gang')->withInput(['value' => 'You have rejected the gang application.']);
        }else{
             return view('jail', ['user' => $request->user()]);
        }
    }

    public function leaveGang($gangId, Request $request)
    {         
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
             return view('jail', ['user' => $request->user()]);
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
}
