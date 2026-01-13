<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PvpItemEvent;
use App\Models\User;

class CheckIfAlive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If there's no authenticated user, continue the request

        #dd($user);
        if (! $user) {
            return $next($request);
        }
        if ((int)$user->health <= 0) {
            if( (int)$user->alive !== 0 ){
                $user->time_of_death = now();
            }
            $user->alive = 0;            
            $user->save();
        }

        #dd((int)$user->alive);
        if ((int)$user->alive === 0) {

            // Allow the death route and auth routes to proceed to avoid loops
            if ($request->routeIs('death') || $request->is('logout', 'login', 'register', 'password/*')) {
                return $next($request);
            }

            $combatLogs = DB::table('pvp_battle_instance')
                ->where('attacker_id', $user->id)
                ->orWhere('defender_id', $user->id)
                ->orderBy('id', 'desc')
                ->get();

            $HitsEvents = [];
            foreach ($combatLogs as $hit) {
                $events = DB::table('pvp_battle_moves')
                    ->where('battle_instance_id', $hit->id)
                    ->get();
                $HitsEvents[$hit->id] = $events;
            }

            $eventDescriptions = [];
            foreach ($HitsEvents as $hitId => $events) {
                foreach ($events as $event) {
                    $eventDetail = PvpItemEvent::find($event->move_event_id);
                    $moveUser = User::find($event->move_user_id);

                    $eventDescriptions[$hitId][] = (object)[
                        'event_detail' => $eventDetail,
                        'move_user_id' => $event->move_user_id,
                        'move_user_name' => $moveUser ? $moveUser->name : null,
                    ];
                }
            }

            return response()->view('death', [
                'user' => $user,
                'combats' => $combatLogs,
                'HitsEvents' => $HitsEvents,
                'eventDescriptions' => $eventDescriptions,
            ], 403);
        }

        return $next($request);
    }
}
