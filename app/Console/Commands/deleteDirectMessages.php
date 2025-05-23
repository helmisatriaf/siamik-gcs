<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Chat_history;
use Carbon\Carbon;
use Illumninate\Support\Facades\Log;

class deleteDirectMessages extends Command
{
    protected $signature = 'chat:delete';
    protected $description = 'Menghapus riwayat pesan great care';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $chatHistory = Chat_history::where('created_at', '<', $today)->delete();

        $this->info("Riwayat pesan sebelum tanggal $today sudah dihapus");
    }
}
