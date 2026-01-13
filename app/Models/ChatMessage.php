<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    public $timestamps = false;
    use HasFactory;
    
    protected $fillable = ['user_id', 'chat_type', 'gang_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
