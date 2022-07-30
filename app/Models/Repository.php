<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Repository extends Model
{
    use HasFactory;

    const IN_PROGRESS = 'en progreso';
    const APROVED = 'aprobado';
    const REJECTED = 'rechazado';
    const OBSERVATIONS = 'observaciones';


    protected $table = "repositories";
    protected $fillable = [
        "responsible_id",
        "name",
        "status",
        'repository_name',
        'conciliation_id',
    ];

    /**
     * ==========
     * RELATIONSHIPS
     * ==========
     */

    public function responsible()
    {
        return $this->belongsTo('App\Models\User', 'responsible_id');
    }
    public function evaluation()
    {
        return $this->hasOne('App\Models\Evaluation', 'repository_id', 'id');
    }
    public function evaluators()
    {
        return $this->belongsToMany(
            'App\Models\Evaluator',
            'evaluators_repositories',
            'repository_id',
            'evaluator_id'
        );
    }
    public function evaluationsHistory()
    {
        return $this->hasMany('App\Models\EvaluationHistory');
    }
    public function conciliation()
    {
        return $this->hasOne(Conciliation::class);
    }

    public function historicals()
    {
        return $this->belongsToMany(
            Historical::class,
            'historical_repository',
            'repository_id',
            'historical_id'
        )->orderBy('historicals.id', 'desc');
    }

    /**
     * ========
     * ATTRIBUTES
     * ========
     */

    public function getStatusColorAttribute()
    {
        if ($this->is_in_progress) return 'info';
        if ($this->is_aproved) return 'success';
        if ($this->is_rejected) return 'danger';
        if ($this->has_observations) return 'warning';
    }

    public function getQualificationAttribute()
    {
        if ($this->evaluation->answers->pluck('choice.question.max_punctuation')->flatten()->sum() == 0) {
            return 0;
        }

        return round($this->evaluation->answers->pluck('choice.punctuation')->flatten()->sum() / $this->evaluation->answers->pluck('question.max_punctuation')->flatten()->sum() * 100, 2);
    }

    /**
     * check if the user logged can select this repository to evaluate it
     * 
     */
    public function getIsSelectableByUserAttribute()
    {
        if ($this->evaluators()->count() >= 2) {
            return false;
        }

        if ($this->evaluation->status != Evaluation::IN_REVIEW) {
            return false;
        }

        if (!Auth::user()->is_evaluator) {
            return false;
        }

        $evaluator = Auth::user()->evaluators()->first();

        if ($this->evaluators()->where('evaluators.id', $evaluator->id)->exists()) {
            return false;
        }

        return true;
    }

    /**
     * =======
     * BOOLEANS
     * =======
     */

    public function getIsInProgressAttribute()
    {
        return $this->status == self::IN_PROGRESS;
    }

    public function getHasObservationsAttribute()
    {
        return $this->status == self::OBSERVATIONS;
    }

    public function getIsAprovedAttribute()
    {
        return $this->status == self::APROVED;
    }

    public function getIsRejectedAttribute()
    {
        return $this->status == self::REJECTED;
    }

    /**
     * Scope
     * 
     */
    public function scopeInProgress($query)
    {
        return $query->where('repositories.status', '=', self::IN_PROGRESS);
    }

    public function scopeWhereEvaluator($query, Evaluator $evaluator)
    {
        return $query->whereHas('evaluators', function ($query) use ($evaluator) {
            return $query->where('evaluators.id', $evaluator->id);
        });
    }

    /**
     * 
     * 
     */

    public function toInProgress()
    {
        $this->changeStatus(self::IN_PROGRESS);
    }

    public function changeStatus(string $status): void
    {
        if ($this->hasStatus($status)) {
            return;
        }

        $this->status = $status;
        $this->save();

        $historical = Auth::user()
            ->historicals()
            ->create([
                'action' => "El repositorio cambió de status a: $status"
            ]);

        $this->historicals()
            ->syncWithoutDetaching([$historical->id]);
    }

    public function hasStatus(string $status)
    {
        return $this->status == $status;
    }
}
