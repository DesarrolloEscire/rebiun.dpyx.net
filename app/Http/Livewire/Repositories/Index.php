<?php

namespace App\Http\Livewire\Repositories;

use App\Models\Category;
use App\Models\Repository;
use App\Models\Evaluator;
use App\Models\Conciliation;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $firstCategory;

    public $search_filter = "Sin filtro";
    private $repositories;


    public $search = "";
    public $user;
    public $repositoryIdAndQualification;
    public $show_PDF;

    public function mount()
    {
        // dd(auth()->user()->name);
        $this->user = Auth::user();
        $this->firstCategory = Category::first();
    }

    public function render()
    {
        $this->handleRepositories();
        return view('livewire.repositories.index', [
            'repositories' => $this->repositories
        ]);
    }

    protected function handleRepositories()
    {

        switch ($this->search_filter) {

            case 'Sin filtro':
                $this->repositories = Repository::orderBy('id', 'desc');
                break;
            case 'Filtrar sin progreso':
                $this->repositories = Repository::inProgress()->orderBy('id', 'desc');
                break;
            case 'Filtrar en evaluación':
                $this->repositories = Repository::all();
                break;
            case 'Filtrar aprobado':
                $this->repositories = Repository::where('repositories.status', '=', 'aprobado')
                    ->orderBy('id', 'desc');
                break;
            case 'Filtrar rechazado':
                $this->repositories = Repository::where('repositories.status', '=', 'rechazado')
                    ->orderBy('id', 'desc');
                break;
        }

        if ($this->search) {
            $this->repositories = $this->repositories->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('responsible', function ($query) {
                        return $query->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('evaluation', function ($query) {
                        return $query->whereHas('evaluator', function ($query) {
                            return $query->where('name', 'like', '%' . $this->search . '%');
                        });
                    });
            });
        }

        if (Auth::user()->is_admin) {

            $this->repositories = $this->repositories->paginate(10);
            foreach ($this->repositories as $repository) {
                $x = $repository->append('qualification');
                $this->repositoryIdAndQualification[] = implode(',', array($x->id, $x->qualification));
                // dd($x->qualification);
            }
        } else if (Auth::user()->is_evaluator) {

            $evaluator =  Evaluator::where('evaluator_id', '=', Auth::user()->id)->first();

            $repositories_2_search = Conciliation::where('evaluator_solve_id', '=', $evaluator->id)
                ->get()
                ->pluck('repository_id');

            foreach ($evaluator->repositories as $i => $repository) {
                $repositories_2_search[] = $repository->id;
            }

            foreach ($this->repositories as $repository) {
                $this->repositoryIdAndQualification[] = $repository->append('qualification');
            }

            $this->repositories = $this->repositories->whereIn('id', $repositories_2_search)->paginate(10);

            foreach ($this->repositories as $repository) {
                $x = $repository->append('qualification');
                $this->repositoryIdAndQualification[] = implode(',', array($x->id, $x->qualification));
            }
            // dump($this->repositoryIdAndQualification);

        } else {
            $this->repositories = Auth::user()->repositories()->paginate(10);

            foreach ($this->repositories as $repository) {
                $x = $repository->append('qualification');
                $this->repositoryIdAndQualification[] = implode(',', array($x->id, $x->qualification));
            }
        }
    }
}
