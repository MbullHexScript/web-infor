<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    protected $signature = 'mail:test {--to= : Recipient email, defaults to configured admin email}';

    protected $description = 'Send a safe test email using configured mail transport.';

    public function handle(): int
    {
        $to = $this->option('to') ?: 'informatika@uinbanten.ac.id';
        Mail::raw('Test email from Informatika website.', fn ($mail) => $mail->to($to)->subject('Website mail test'));
        $this->info('Mail test dispatched to '.$to);

        return self::SUCCESS;
    }
}
