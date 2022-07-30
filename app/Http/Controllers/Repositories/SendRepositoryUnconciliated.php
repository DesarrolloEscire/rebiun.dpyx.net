<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Controller;
use App\Jobs\Repositories\ReviewedMail;
use Illuminate\Http\Request;
use App\Models\Repository;
use RealRashid\SweetAlert\Facades\Alert;

class SendRepositoryUnconciliated extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Repository $repository)
    {
        $repository->changeStatus($request->status);
        $repository->evaluation->asReviewed();
        ReviewedMail::dispatch($repository);
        Alert::success('¡Repositorio evaluado!');
        return redirect()->route('repositories.index');
    }
}
