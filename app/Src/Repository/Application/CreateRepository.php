<?php

namespace App\Src\Repository\Application;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;

class CreateRepository
{
    public function handle(User $user, string $repository_name)
    {
        $repository = $user->repositories()->create([
            'responsible_id' => $user->id,
            'name' => $repository_name,
            'repository_name' => $user->name,
        ]);
    }
}
