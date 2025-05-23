<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeactivateAssessments extends Command
{
    protected $signature = 'assessment:deactivate';
    protected $description = 'Menonaktifkan assessment yang sudah melewati deadline';

    public function handle() {
        $yesterday = Carbon::yesterday()->toDateString(); // Ambil tanggal kemarin

        $expiredAssessments = Exam::where('date_exam', '<=', $yesterday)
        ->where('is_active', true)
        ->update(['is_active' => false]);

        $this->info("Assessment yang dinonaktifkan: $expiredAssessments");
        Log::info("Assessment yang dinonaktifkan: " . ($expiredAssessments));
    }
}
