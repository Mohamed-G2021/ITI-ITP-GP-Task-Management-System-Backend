<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Phase;

class Board extends Model
{
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_boards');
    }

    public function phases()
    {
        return $this->hasMany(Phase::class, 'user_id', 'id');
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
