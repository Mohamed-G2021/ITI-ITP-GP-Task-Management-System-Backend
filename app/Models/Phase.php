<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Card;
use App\Models\Board;

class Phase extends Model
{
    use HasFactory;

    public function cards()
    {
        return $this->hasMany(Card::class, 'user_id', 'id');
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
