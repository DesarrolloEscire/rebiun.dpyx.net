<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckList extends Model
{
    use HasFactory;

    protected $table = "checklists";

    protected $fillable = [
        'evaluator_id',
        'category_id',
        'conciliation_id',
    ];

    public $timestamps = false;

    public function conciliation()
    {
        return $this->belongsTo(
            Conciliation::class,
            'conciliation_id',
            'id'
        );
    }

    public function evaluator()
    {
        return $this->belongsTo(
            Evaluator::class,
            'evaluator_id',
            'id'
        );
    }

    public function category()
    {
        return $this->belongsTo(
            Category::class,
            'category_id',
            'id'
        );
    }

    public function scopeWhereConciliation($query, Conciliation $conciliation)
    {
        return $query->where('conciliation_id', $conciliation->id);
    }

    public function scopeWhereEvaluator($query, Evaluator $evaluator)
    {
        return $query->where('evaluator_id', $evaluator->id);
    }
}
