<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Historical allow to save all repository actions made by users
 * 
 */
class Historical extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhereUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
}
