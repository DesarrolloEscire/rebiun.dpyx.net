<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Conciliation extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_list',
        'repository_id',
        'status',
        'evaluator_solve_id',
    ];


    public function repository()
    {
        return $this->belongsTo(Repository::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function comments()
    {
        return $this->belongsToMany(
            Comment::class,
            'comment_conciliation',
            'conciliation_id',
            'comment_id'
        );
    }

    public function checklist()
    {
        return $this->hasMany(
            CheckList::class,
            'conciliation_id',
            'id'
        );
    }

    // public function getEvaluatorsAttribute()
    // {
    //     return $this->evaluators()->get();
    // }

    public function getIsCheckListCompleteAttribute()
    {
        $checklistCount = $this->checklist()->whereIn('category_id', Category::get()->pluck('id')->toArray())->count();

        if ($checklistCount == Category::count() * 2) {
            return true;
        }

        return false;
    }

    /**
     * Check if the evaluator resolutions are closed
     * 
     */
    public function getIsClosedAttribute()
    {
        $allStatus = $this->repository
            ->evaluation
            ->statusResolutions
            ->pluck('status_conciliation');

        foreach ($allStatus as $status) {
            if ($status != StatusResolution::CLOSED) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the evaluator resolutions are closed
     * 
     */
    public function getIsOpenAttribute()
    {
        $allStatus = $this
            ->statusResolutions()
            ->get()
            ->pluck('status_conciliation');

        foreach ($allStatus as $status) {
            if ($status != StatusResolution::CLOSED) {
                return true;
            }
        }

        return false;
    }

    public function evaluators()
    {
        return $this->belongsToMany(
            Evaluator::class,
            'conciliation__evaluator',
            'conciliation_id',
            'evaluator_id'
        );
    }

    /**
     * Change status to opened
     * 
     */
    public function asOpened()
    {
        foreach ($this->statusResolutions()->get() as $statusResolution) {
            $statusResolution->asOpened();
        }
    }

    public function statusResolutions()
    {
        return $this->repository
            ->evaluation
            ->statusResolutions();
    }
}
