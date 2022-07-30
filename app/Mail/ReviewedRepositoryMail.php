<?php

namespace App\Mail;

use App\Models\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Category;
use App\Models\Subcategory;
use App\Support\Collection;
use PDF;

class ReviewedRepositoryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $repository;
    public $certificationPDF;
    public $evaluationPDF;
    public $comments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.repositories.reviewed')
            ->subject('Repositorio revisado');
    }
}
