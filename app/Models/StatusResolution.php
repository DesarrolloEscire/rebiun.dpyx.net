<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Resolution when evaluators solve a conciliation
 * 
 */
class StatusResolution extends Model
{
    use HasFactory;

    /**
     * For status_conciliation
     * 
     */
    const UNCONCILIATED = "unconciliated";
    const CLOSED = "close";
    const OPENED = "open";

    /**
     * For status
     * 
     */
    const APPROVED = "aprobado";
    const REJECTED = "rechazado";

    protected $fillable = [
        'evaluator_id',
        'evaluation_id',
        'status',
        'status_conciliation'
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(Evaluator::class);
    }

    public function scopeUnconciliated($query)
    {
        return $query->where('status_conciliation', '=', 'unconciliated');
    }

    public function scopeWhereEvaluation($query, Evaluation $evaluation)
    {
        return $query->where('evaluation_id', '=', $evaluation->id);
    }

    public function scopeWhereEvaluator($query, Evaluator $evaluator)
    {
        return $query->where('evaluator_id', '=', $evaluator->id);
    }

    public function getIsOpenedAttribute()
    {
        return $this->status_conciliation == self::OPENED;
    }

    public function getIsClosedAttribute()
    {
        return $this->status_conciliation == self::CLOSED;
    }

    public function getIsRejectedAttribute()
    {
        return $this->status == self::REJECTED;
    }

    public function getIsApprovedAttribute()
    {
        return $this->status == self::APPROVED;
    }

    public function changeStatusConciliation($statusConciliation)
    {
        $this->status_conciliation = $statusConciliation;
        $this->save();
    }

    public function changeStatus($status)
    {
        $this->status = $status;
        $this->save();
    }

    public function asUnconciliated()
    {
        $this->changeStatusConciliation(
            self::UNCONCILIATED
        );
    }

    public function asOpened()
    {
        $this->changeStatusConciliation(
            self::OPENED
        );
    }

    public function asClosed()
    {
        $this->changeStatusConciliation(
            self::CLOSED
        );
    }
}
