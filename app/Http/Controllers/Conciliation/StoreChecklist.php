<?php

namespace App\Http\Controllers\Conciliation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CheckList;
use App\Models\Repository;
use App\Models\Conciliation;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


class StoreChecklist extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Repository $repository, $evaluator_auth)
    {
        $conciliation = $repository->conciliation;

        $conciliation
            ->checklist()
            ->whereEvaluator(Auth::user()->asEvaluator)
            ->delete();

        if (!$request->checklist) {
            Alert::success('¡Categorias seleccionadas!');
            return redirect()->back();
        }

        $categories = Category::whereIn('id', $request->checklist)->get();

        $categories->map(function ($category) use ($conciliation) {
            CheckList::create([
                'category_id' => $category->id,
                'evaluator_id' => Auth::user()->asEvaluator->id,
                'conciliation_id' => $conciliation->id
            ]);
        });

        Alert::success('¡Categorias seleccionadas!');
        return redirect()->back();
    }
}
