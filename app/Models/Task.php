<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Group;


class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_done', 'user_id', 'group_id'];

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
