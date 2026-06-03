<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\BirthdayReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class SendBirthdayReminder extends Command
{
    protected $signature = 'birthday:reminder';

    protected $description = 'Send birthday reminder emails';

    public function handle()
    {
        $date = Carbon::now()->addDays(7)->format('m-d');

        $users = User::whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$date])->get();

        foreach ($users as $user) {

            Mail::to($user->email)->send(new BirthdayReminderMail($user));

            $this->info('Mail sent to ' . $user->email);
        }
    }
}
