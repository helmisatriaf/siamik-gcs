<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BillingService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://billing.great.sch.id/api';
    }

    public function checkPaymentStatus($uniqueId)
    {
        try {
            Log::info("Checking payment status for ID: {$uniqueId}");
            $response = Http::timeout(5)->get("{$this->baseUrl}/check-payment-status/{$uniqueId}");

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Payment status response: " . json_encode($data));
                return $data;
            }

            Log::warning("Unsuccessful response from payment status API: {$response->status()}, Body: {$response->body()}");
            return null;
        } catch (\Exception $e) {
            Log::error("Error checking payment status: {$e->getMessage()}");
            return null;
        }
    }

    public function getPaymentHistory($uniqueId)
    {
        try {
            Log::info("Getting payment history for ID: {$uniqueId}");
            $response = Http::timeout(5)->get("{$this->baseUrl}/payment-history/{$uniqueId}");

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Payment history response: " . json_encode($data));
                return $data;
            }

            Log::warning("Unsuccessful response from payment history API: {$response->status()}, Body: {$response->body()}");
            return null;
        } catch (\Exception $e) {
            Log::error("Error fetching payment history: {$e->getMessage()}");
            return null;
        }
    }
}
