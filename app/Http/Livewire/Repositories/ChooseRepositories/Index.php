<?php

namespace App\Http\Livewire\Repositories\ChooseRepositories;

use App\Models\User;
use App\Models\Evaluation;
use App\Models\Evaluator;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Repository;
use Illuminate\Support\Facades\Auth;




class Index extends Component
{
    use WithPagination;

    public $search = "";

    private $repositories;
    public $evaluator;
    public $is_admin;

    public $authEvaluator;

    public function mount()
    {
        $this->repositories = Repository::get();
        $this->evaluator = auth()->user();
        $this->is_admin = Auth::user()->is_admin;

        $this->authEvaluator = Evaluator::firstWhere('evaluator_id', '=', Auth::user()->id);
    }

    public function render()
    {
        $this->handleRepositories();
        return view('livewire.repositories.choose-repositories.index', ['repositories' => $this->repositories]);
    }

    private function handleRepositories()
    {
        $this->repositories = $this->repositories;
    }
}
