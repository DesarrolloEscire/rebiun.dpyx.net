<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Evaluation;
use App\Models\Question;
use App\Models\Repository;
use App\Models\User;
use App\Models\Evaluator;
use App\Services\EvaluationService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Cristian',
            'phone' => '5512328187',
            'email' => 'cguzman@ibsaweb.com',
            'password' => bcrypt('¡MyP4ssw0rd!'),
            'email_verified_at' => Carbon::now(),
        ]);

        $user->assignRole('admin');


        $userAdmin = User::create([
            'name' => 'Nydia Lopez',
            'phone' => null,
            'email' => 'nlopez@escire.mx',
            'password' => bcrypt('1Aprender3948.'),
            'email_verified_at' => Carbon::now(),
        ])->assignRole('admin');

        if (config('app.is_evaluable')) {

            $userEvaluator = User::create([
                'name' => 'Nydia Evaluador',
                'phone' => null,
                'email' => 'valni.info@gmail.com',
                'password' => bcrypt('1Aprender3948.'),
                'email_verified_at' => Carbon::now(),
            ])->assignRole('evaluador');

            $evaluator = Evaluator::create([
                'evaluator_id' => $userEvaluator->id,
                'evaluator_name' => $userEvaluator->name,
            ]);

            $userEvaluator = User::create([
                'name' => 'Andres Padilla',
                'phone' => null,
                'email' => 'apadilla@ibsaweb.com',
                'password' => bcrypt('123456789'),
                'email_verified_at' => Carbon::now(),
            ])->assignRole('evaluador');

            $evaluator = Evaluator::create([
                'evaluator_id' => $userEvaluator->id,
                'evaluator_name' => $userEvaluator->name

            ]);

        }

        if (config('app.is_evaluable')) {
            $userEvaluator = User::create([
                'name' => 'Carlos Lopez',
                'phone' => null,
                'email' => 'carlos@gmail.com',
                'password' => bcrypt('123456789')
            ])->assignRole('evaluador');

            $evaluator = Evaluator::create([
                'evaluator_id' => $userEvaluator->id,
                'evaluator_name' => $userEvaluator->name

            ]);
        }


        $user = User::create([
            'name' => 'hector',
            'phone' => null,
            'email' => 'hector@gmail.com',
            'password' => bcrypt('123456789'),
            'email_verified_at' => Carbon::now(),
        ])->assignRole('usuario');

        $repository = Repository::create([
            'responsible_id' => $user->id,
            'repository_name' => $user->name,

            'name' => 'repo de hector',
            'status' => 'en progreso',
        ]);

        if (config('app.is_evaluable')) {
            $evaluation = Evaluation::create([
                'repository_id' => $repository->id,
                'status' => 'en progreso',
            ]);

        } else {
            $evaluation = Evaluation::create([
                'repository_id' => $repository->id,
                'status' => 'en progreso',
            ]);
        }
        /**
         * Create empty answers for each question
         */

        Question::get()->each(function ($question) use ($evaluation) {
            Answer::create([
                'evaluation_id' => $evaluation->id,
                'question_id' => $question->id,
                'is_updateable' => 1
            ]);
        });



    }
}
