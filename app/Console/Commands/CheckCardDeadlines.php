<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class CheckCardDeadlines extends Command
{
    protected $signature = 'check:card-deadlines';
    protected $description = 'Mengirim notifikasi jika ada card yang hampir deadline';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $cards = Card::where('deadline', $tomorrow)->get();

        foreach ($cards as $card) {
            Mail::raw("Reminder: Card '{$card->title}' memiliki deadline besok!", function ($message) {
                $message->to('user@example.com')->subject('Task Reminder');
            });
        }

        $this->info('Notifikasi deadline telah dikirim.');
    }
}
