<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PvpItemEvent extends Model
{
    use HasFactory;

    public function weapon()
    {
        return $this->belongsTo(Weapon::class, 'item_id');
    }
}
