<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Category;
use App\Models\Attachment;
use App\Models\Phase;

class Card extends Model
{
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_cards');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'card_categories', 'card_id', 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'user_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'user_id', 'id');
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }
}
