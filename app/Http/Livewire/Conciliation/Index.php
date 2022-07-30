<?php

namespace App\Http\Livewire\Conciliation;

use App\Models\Category;
use App\Models\Repository;
use Livewire\Component;
use App\Models\Message;
use App\Models\Evaluator;
use App\Models\StatusResolution;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $categories;
    public $repository;
    public $conciliation;
    public $chat;
    public $messages;
    public $evaluator_auth;
    public $evaluatorAssosiated;
    public $response;
    public $status = false;
    public $status_contrary;
    public $check_list_auth = [];
    public $check_list_assosiated = [];


    // private $evaluator;
    public function mount(Repository $repository)
    {
        $this->categories = Category::all();
        $this->repository = $repository;

        $this->evaluator_auth = Evaluator::firstWhere('evaluator_id', '=', Auth::user()->id);

        $this->evaluatorAssosiated = $repository->conciliation
            ->evaluators()
            ->firstWhere('evaluators.id', '!=', $this->evaluator_auth->id);
    }

    public function render()
    {
        $this->takeMessages();

        $resolutionOtherEvaluator = StatusResolution::whereEvaluation($this->repository->evaluation)
            ->whereEvaluator($this->evaluator_auth)->first();

        if ($resolutionOtherEvaluator->status_conciliation == 'close') {
            $this->status_contrary = $resolutionOtherEvaluator->status;
        }

        $conciliation = $this->repository->conciliation;
        $this->conciliation = $conciliation;

        $evaluatorsCheckList = json_decode($conciliation->check_list);

        $compare_eval = null;
        foreach ($evaluatorsCheckList as $checkList) {
            foreach ($checkList as $key => $listElement) {
                if ($key == 0) {
                    $compare_eval = $listElement;
                    continue;
                }

                if ($compare_eval == $this->evaluator_auth->id) {
                    $this->check_list_auth[] = $listElement;
                    continue;
                }

                $this->check_list_assosiated[] = $listElement;
            }
        }


        return view('livewire.conciliation.index');
    }

    private function takeMessages()
    {
        $this->messages = $this->repository->conciliation->messages()->orderBy('created_at', 'DESC')->get();
    }

    public function saveChat()
    {
        $message = Message::create([
            'evaluator_id' => $this->evaluator_auth->id,
            'chat' => $this->chat,
            'conciliation_id' => $this->repository->conciliation->id,
        ]);
    }

    public function sendStatus()
    {
        $this->status = true;
    }
}
