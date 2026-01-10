<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function bets()
    {
        return $this->belongsToMany(Bet::class, 'bet_race')->withPivot('bet_id', 'race_id');
    }

    public function horses()
    {
        return $this->belongsToMany(Horse::class, 'race_horse')->withPivot('race_id', 'horse_id');
    }
}
