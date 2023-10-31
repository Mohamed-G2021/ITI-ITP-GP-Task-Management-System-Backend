<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Card;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'card_id'];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'group_id', 'id');
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
