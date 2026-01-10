<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horse extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'amount',
    ];

    public function races()
    {
        return $this->belongsToMany(Race::class, 'race_horse')->withPivot('race_id', 'horse_id');
    }

    public function bets()
    {
        return $this->hasMany(Bet::class, 'horse_id');
    }
}
