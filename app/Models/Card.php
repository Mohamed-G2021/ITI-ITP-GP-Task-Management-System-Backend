<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Category;
use App\Models\Attachment;
use App\Models\Phase;
use App\Models\Comment;
use App\Models\Group;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'status_icon', 'position', 'phase_id'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_card')->withPivot('role');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'card_category');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'card_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'card_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'card_id', 'id');
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }
}
