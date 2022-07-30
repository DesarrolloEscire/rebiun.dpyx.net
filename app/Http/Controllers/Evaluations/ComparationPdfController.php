<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Repository;
use App\Models\Evaluation;
use App\Models\Subcategory;
use App\Models\Category;
use PDF;



class ComparationPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //public $repository;



    public function __invoke(Request $request, Repository $repository)
    {

        $categories = Category::has('questions')->get();
        $subcategories = Subcategory::has('questions')->with(['questions.answers' => function ($query) use ($repository) {
            $query->where('evaluation_id', $repository->evaluation->id);
        }])->get();

        return PDF::loadView('pdfs.comparation', compact('repository', 'categories', 'subcategories'))->download('comparation-' . $repository->name . '-' . date('Y') . '.pdf');
    }



}
