<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Board;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'background_color', 'background_image'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_workspaces');
    }

    public function boards()
    {
        return $this->hasMany(Board::class, 'workspace_id', 'id');
    }
}
