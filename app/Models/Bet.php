<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'amount',
        'horse_id',
        'race_id',
        'user_id',
    ];

    public function races()
    {
        return $this->belongsToMany(Race::class, 'bet_race')->withPivot('bet_id', 'race_id');
    }

    public function horses()
    {
        return $this->belongsTo(Horse::class, 'horse_id');
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
