<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Card;

class Category extends Model
{
    use HasFactory;

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_category');
    }
}
