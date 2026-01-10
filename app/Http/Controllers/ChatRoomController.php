<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ChatMessage;
use Auth;
use DB;

class ChatRoomController extends Controller
{
    public function view()
    {              
        $messages = ChatMessage::with('user')->latest()->take(50)->get()->reverse();
        
        $messages = $messages->map(function($msg) {
            return [
                'id' => $msg->id,
                'user' => $msg->user,
                'message' => $msg->message,
                'created_at' => Carbon::parse($msg->created_at)->format('H:i'),
            ];
        });

        return response()->json($messages->values()); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $message = ChatMessage::create([
            'user_id' => Auth::user()->id,
            'message' => $request->message,
        ]);

        return response()->json($message);
    }
}
