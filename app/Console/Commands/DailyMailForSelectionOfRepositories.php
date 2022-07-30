<?php

namespace App\Console\Commands;

use App\Http\Controllers\Evaluators\SendMail\SendDailyMailController;
use App\Mail\DailyEvaluatorsMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class DailyMailForSelectionOfRepositories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_daily:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send workweek daily mail';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $send_daily_mail = new SendDailyMailController();
        $send_daily_mail->SendMail();

        // Mail::to('andrestor@gmail.com')->send(new DailyEvaluatorsMail());

     //    $testo = "si se graba: " . date("Y-m-d H:i:s");
       // Storage::append("testschedule.txt", $testo);
       //  return 0;
    }
}
