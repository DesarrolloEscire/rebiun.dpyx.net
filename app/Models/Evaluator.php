<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluator extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluator_id', //user
        'evaluator_name',
        'check_list',
    ];

    public function repositories()
    {
        return $this->belongsToMany('App\Models\Repository', 'evaluators_repositories', 'evaluator_id', 'repository_id');
    }

    public function asUser()
    {
        return $this->belongsTo(
            User::class,
            'evaluator_id',
            'id'
        );
    }

    public function responsible()
    {
        return $this->belongsTo('App\Models\User', 'evaluator_id');
    }
}
