<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use App\Services\EvaluationService;
use App\Services\EvaluatorService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Evaluation;
use App\Models\Repository;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    private $users;
    public $unverifiedUsers;
    public $search = "";

    public function mount()
    {
        $this->unverifiedUsers = User::unverified()->users()->get();
    }

    public function render()
    {
        $this->setUsers();
        return view('livewire.users.index', [
            'users' => $this->users
        ]);
    }

    private function setUsers()
    {

        $this->users = User::verified()->orderBy('id', 'desc');

        if ($this->search) {
            $this->users = $this->users
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhereHas('roles', function ($query) {
                            return $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
        }

        if (Auth::user()->is_admin) {
            $this->users = $this->users;
        } else {

            $repositoryResponsiblesIds = Repository::whereEvaluator(Auth::user()->asEvaluator)
                ->get()
                ->pluck('responsible.id')
                ->flatten()
                ->unique();

            $this->users = $this->users->whereIn('id', $repositoryResponsiblesIds);
        }

        $this->users = $this->users->paginate(10);
    }

    public function updatingSearch()
    {
        $this->gotoPage(1);
    }
}
