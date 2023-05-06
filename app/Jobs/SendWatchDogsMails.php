<?php

namespace App\Jobs;

use App\Mail\WatchDogMail;
use App\Mail\WelcomeMail;
use App\SystemVariable;
use App\WatchDog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendWatchDogsMails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $var = json_decode(SystemVariable::where('name', 'registration')->first()->value)->permit;
        $data = collect([
            'dateTime' => is_bool($var) ? null : Carbon::parse($var),
        ]);
        $watchDogs = WatchDog::all();
        foreach ($watchDogs as $watchDog) {
            Mail::to($watchDog)->locale(App::getLocale())->send(new WatchDogMail($data));
        }

    }
}
