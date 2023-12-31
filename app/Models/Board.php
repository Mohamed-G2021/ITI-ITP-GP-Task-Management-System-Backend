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
    protected $fillable = ['title', 'description', 'view', 'workspace_id', 'background_color'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_board')->withPivot('role');
    }

    public function phases()
    {
        return $this->hasMany(Phase::class, 'Board_id', 'id');
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
