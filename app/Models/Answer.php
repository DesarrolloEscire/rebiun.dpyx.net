<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'choice_id',
        'question_id',
        'evaluation_id',
        'description',
        'is_updateable',
    ];

    /**
     * ==========
     * RELATIONSHIPS
     * ==========
     */

    public function observations()
    {
        return $this->belongsToMany('App\Models\Observation', 'answers_observations', 'answer_id', 'observation_id');
    }

    public function choice()
    {
        return $this->belongsTo('App\Models\Choice');
    }

    public function evaluation()
    {
        return $this->belongsTo('App\Models\Evaluation');
    }

    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

    /**
     * ==========
     * SCOPE
     * ==========
     */
    public function scopeWhereEmpty($query, $column)
    {
        return $query->whereNull($column)->orWhere($column, '');
    }
}
