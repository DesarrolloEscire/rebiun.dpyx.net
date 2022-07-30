<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\PDFs\EvaluationPDF;
use Illuminate\Http\Request;

class ExportEvaluationController extends Controller
{
    public function __invoke(Request $request, Evaluation $evaluation)
    {
        return (new EvaluationPDF($evaluation, $request->picture))
            ->download();
    }
}
