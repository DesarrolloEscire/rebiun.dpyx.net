<?php

namespace App\View\Components\Modals\Repositories;

use App\Models\Repository;
use Illuminate\View\Component;

class History extends Component
{
    public $repository;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.modals.repositories.history');
    }
}
