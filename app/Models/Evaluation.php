<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Evaluator;
use App\PDFs\EvaluationPDF;
use Barryvdh\DomPDF\PDF;

class Evaluation extends Model
{
    use HasFactory;

    const IN_PROGRESS = "en progreso";
    const IN_REVIEW = "en revisión";
    const REVIEWED = "revisado";
    const ANSWERED = "contestada";

    protected $fillable = [
        'repository_id',
        'evaluator_id',
        'status',
    ];

    /**
     * ==============
     *  RELATIONSHIPS
     * ==============
     */

    public function answers()
    {
        return $this->hasMany('App\Models\Answer');
    }

    public function repository()
    {
        return $this->belongsTo('App\Models\Repository');
    }

    public function evaluator()
    {
        return $this->belongsTo('App\Models\User', 'evaluator_id', 'id');
    }

    public function statusResolutions()
    {
        return $this->hasMany(StatusResolution::class);
    }

    public function comments(){
        return $this->belongsToMany(
            Comment::class,
            'comment__evaluation',
            'evaluation_id',
            'comment_id'
        );
    }

    /**
     * ========
     * ATTRIBUTES
     * ========
     */

    public function getStatusColorAttribute()
    {
        if ($this->is_in_progress) return 'info';
        if ($this->in_review) return 'warning';
        if ($this->is_reviewed) return 'success';
        if ($this->is_answered) return 'dark';
        return '';
    }

    /**
     * ========
     * BOOLEANS
     * ========
     */

    public function getIsInProgressAttribute()
    {
        return $this->status == self::IN_PROGRESS;
    }

    public function getIsReviewedAttribute()
    {
        return $this->status == self::REVIEWED;
    }

    public function getInReviewAttribute()
    {
        return $this->status == self::IN_REVIEW;
    }

    public function getIsAnsweredAttribute()
    {
        return $this->status == 'contestada';
    }

    public function getIsViewableAttribute()
    {
        if (Auth::user()->is_admin) {
            return true;
        }

        if ($this->is_in_progress && Auth::user()->is_user) {
            return true;
        }
        
        if ($this->is_answered && Auth::user()->is_user) {
            return true;
        }

        if (Auth::user()->is_evaluator && $this->is_in_progress) {
            return false;
        }

        if (Auth::user()->is_evaluator && $this->is_answered) {
            return false;
        }

        return true;
    }


    public function scopeWhereEvaluator($query, Evaluator $evaluator)
    {
        return $query->where('evaluator_id', '=', $evaluator->id);
    }

    public function getIsAnswerableAttribute()
    {
        if (Auth::user()->id != $this->repository->responsible->id) {
            return false;
        }

        if ($this->in_review) {
            return false;
        }

        return true;
    }

    public function getIsReviewableAttribute()
    {
        if (!config('app.is_evaluable')) {
            return false;
        }

        foreach ($this->repository->evaluators as $i => $evaluator) {
            if ($evaluator->responsible->id != Auth::user()->id) {
                if ($i == ($evaluator->count() - 1)) {
                    return false;
                }
            } else {
                break;
            }
        }

        if (!$this->in_review) {
            return false;
        }


        return true;
    }

    public function getIsPDFDownloableAttribute()
    {
        if (Auth::user()->is_evaluator || Auth::user()->is_admin) {
            return true;
        }

        $existsPDFInFileSystem = (new EvaluationPDF($this))->existsPDFInFileSystem();

        return $existsPDFInFileSystem;
    }

    public function pdf(): PDF
    {
        return (new EvaluationPDF($this))->build();
    }

    public function changeStatus($status): void
    {
        if ($this->status == $status) {
            return;
        }

        $this->status = $status;
        $this->save();

        $historical = Auth::user()->historicals()->create([
            'action' => "La evaluación cambió de status a: $status"
        ]);

        $this->repository->historicals()->syncWithoutDetaching([$historical->id]);
    }

    public function toInReview()
    {
        $this->changeStatus(self::IN_REVIEW);
    }

    public function asReviewed()
    {
        $this->changeStatus(self::REVIEWED);
    }

    public function asAnswered()
    {
        $this->changeStatus(self::ANSWERED);
    }

    public function asInProgress()
    {
        $this->changeStatus(self::IN_PROGRESS);
    }
}
