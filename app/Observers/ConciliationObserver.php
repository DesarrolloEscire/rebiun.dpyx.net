<?php

namespace App\Observers;

use App\Models\Conciliation;
use Illuminate\Support\Facades\Auth;

class ConciliationObserver
{
    /**
     * Handle the Conciliation "created" event.
     *
     * @param  \App\Models\Conciliation  $conciliation
     * @return void
     */
    public function created(Conciliation $conciliation)
    {
        $historical = Auth::user()
            ->historicals()
            ->create([
                'action' => "Se generó un proceso de conciliación"
            ]);

        $conciliation->repository->historicals()
            ->syncWithoutDetaching([$historical->id]);
    }

    /**
     * Handle the Conciliation "updated" event.
     *
     * @param  \App\Models\Conciliation  $conciliation
     * @return void
     */
    public function updated(Conciliation $conciliation)
    {
        
    }

    /**
     * Handle the Conciliation "deleted" event.
     *
     * @param  \App\Models\Conciliation  $conciliation
     * @return void
     */
    public function deleted(Conciliation $conciliation)
    {
        //
    }

    /**
     * Handle the Conciliation "restored" event.
     *
     * @param  \App\Models\Conciliation  $conciliation
     * @return void
     */
    public function restored(Conciliation $conciliation)
    {
        //
    }

    /**
     * Handle the Conciliation "force deleted" event.
     *
     * @param  \App\Models\Conciliation  $conciliation
     * @return void
     */
    public function forceDeleted(Conciliation $conciliation)
    {
        //
    }
}
