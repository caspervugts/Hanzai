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
        $messages = ChatMessage::with('user', 'user.gang')
        ->where(function($query) {
            $query->where('chat_type', 'all')
            ->orWhere(function($query) {
                $query->where('chat_type', 'gang')
                      ->where('gang_id', Auth::user()->gang_id);
            });
        })
        ->latest()
        ->take(50)
        ->get()
        ->reverse();
        
        $messages = $messages->map(function($msg) {
            return [
                'id' => $msg->id,
                'user' => $msg->user,
                'chat_type' => $msg->chat_type,
                'message' => $msg->message,
                'created_at' => Carbon::parse($msg->created_at)->format('H:i'),
            ];
        });

        return response()->json($messages->values()); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'chat_type' => 'required|in:all,gang',
            'message' => 'required|string|max:500',
        ]);

        $message = ChatMessage::create([
            'user_id' => Auth::user()->id,
            'chat_type' => $request->chat_type,
            'gang_id' => Auth::user()->gang_id ?? null,
            'message' => $request->message,
        ]);

        return response()->json($message);
    }
}
