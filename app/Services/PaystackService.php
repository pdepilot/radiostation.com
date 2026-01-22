<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected string $secretKey;
    protected string $publicKey;
    protected string $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key', env('PAYSTACK_SECRET_KEY'));
        $this->publicKey = config('services.paystack.public_key', env('PAYSTACK_PUBLIC_KEY'));
    }

    /**
     * Initialize a payment transaction
     */
    public function initializeTransaction(array $data): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/transaction/initialize", $data);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Paystack initialization failed', [
            'response' => $response->json(),
            'status' => $response->status(),
        ]);

        throw new \Exception('Failed to initialize Paystack transaction: ' . ($response->json()['message'] ?? 'Unknown error'));
    }

    /**
     * Verify a transaction by reference
     */
    public function verifyTransaction(string $reference): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->get("{$this->baseUrl}/transaction/verify/{$reference}");

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Paystack verification failed', [
            'reference' => $reference,
            'response' => $response->json(),
            'status' => $response->status(),
        ]);

        throw new \Exception('Failed to verify Paystack transaction: ' . ($response->json()['message'] ?? 'Unknown error'));
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
