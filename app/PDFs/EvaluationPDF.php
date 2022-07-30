<?php

namespace App\PDFs;

use App\Models\Category;
use App\Models\Evaluation;
use App\Models\Subcategory;
use App\PDFs\PDF as PDFsPDF;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class EvaluationPDF implements PDFsPDF
{

    private $evaluation;
    private $picture;

    public function __construct(Evaluation $evaluation, ?string $picture = null)
    {
        $this->evaluation = $evaluation;
        $this->picture = $picture;
    }

    public function view()
    {
        return 'pdfs.evaluation';
    }

    public function build(): DomPDFPDF
    {
        return PDF::loadView(
            $this->view(),
            [
                'img' => $this->picture,
                'repository' => $this->evaluation->repository,
                'categories' => Category::has('questions')->get(),
                'subcategories' => Subcategory::has('questions')->with(['questions.answers' => function ($query) {
                    $query->where('evaluation_id', $this->evaluation->id);
                }])->get()
            ]
        );
    }

    /**
     * Store file in filesystem
     * 
     */
    public function store()
    {
        Storage::put(
            $this->path(),
            $this->build()->output()
        );
    }

    public function path(): string
    {
        $filename = $this->filename();
        return "evaluations/$filename";
    }

    public function filename()
    {
        $evaluationId = $this->evaluation->id;
        return "$evaluationId.pdf";
    }

    public function download(?string $filename = null)
    {
        if (Auth::user()->is_admin || Auth::user()->is_evaluator) {
            return $this->build()->download($filename ?? 'evaluacion.pdf');
        }

        return $this->downloadFromFileSystem();
    }

    public function existsPDFInFileSystem()
    {
        return Storage::exists(
            $this->path()
        );
    }

    public function downloadFromFileSystem()
    {
        return Storage::download($this->path());
    }
}
