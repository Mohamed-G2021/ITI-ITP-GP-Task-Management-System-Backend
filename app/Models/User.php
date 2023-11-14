<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Comment;
use App\Models\Attachment;
use App\Models\Task;
use App\Models\Workspace;
use App\Models\Board;
use App\Models\Card;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    ## one to many relations
    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }


    ##many to many relations
    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'user_workspace')->withTimestamps()->withPivot('role');
    }

    public function boards(): BelongsToMany
    {
        return $this->belongsToMany(Board::class,  'user_board')->withTimestamps()->withPivot('role');
    }

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class,  'user_card')->withTimestamps()->withPivot('role');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_member')->withTimestamps();
    }
}
