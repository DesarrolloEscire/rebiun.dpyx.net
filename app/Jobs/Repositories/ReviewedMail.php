<?php

namespace App\Jobs\Repositories;

use App\Mail\ReviewedRepositoryMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ReviewedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $repository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function evaluatorEmails(): array
    {
        return $this->repository
            ->evaluators
            ->pluck('responsible.email')
            ->toArray();
    }

    public function adminEmails(): array
    {
        return User::administrators()
            ->get()
            ->pluck('email')
            ->toArray();
    }

    public function responsibleEmail(): string
    {
        return $this->repository
            ->responsible
            ->email;
    }

    public function emails(): array
    {
        return collect($this->evaluatorEmails())
            ->merge($this->adminEmails())
            ->push($this->responsibleEmail())
            ->toArray();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->emails())
            ->send(new ReviewedRepositoryMail($this->repository));
    }
}
