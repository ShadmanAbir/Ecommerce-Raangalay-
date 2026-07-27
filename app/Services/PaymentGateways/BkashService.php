<?php

namespace App\Services\PaymentGateways;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class BkashService
{
    protected $baseUrl;
    protected $appKey;
    protected $appSecret;
    protected $username;
    protected $password;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = env('BKASH_BASE_URL', 'https://checkout.sandbox.bka.sh/v1.2.0');
        $this->appKey = env('BKASH_APP_KEY');
        $this->appSecret = env('BKASH_APP_SECRET');
        $this->username = env('BKASH_USERNAME');
        $this->password = env('BKASH_PASSWORD');
    }

    public function authenticate()
    {
        $response = Http::post("{$this->baseUrl}/tokenized/checkout/token/grant", [
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
        ]);

        if ($response->successful()) {
            $this->token = $response->json('id_token');
            return $this->token;
        }

        throw new \Exception('bKash authentication failed: ' . $response->body());
    }

    public function createPayment($order)
    {
        try {
            $this->authenticate();

            $response = Http::withHeaders([
                'Authorization' => $this->token,
                'X-APP-Key' => $this->appKey,
            ])->post("{$this->baseUrl}/tokenized/checkout/create", [
                'mode' => '0011',
                'payerReference' => $order->user_id,
                'callbackURL' => route('payment.callback', ['method' => 'bkash']),
                'amount' => $order->total_amount,
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $order->order_number,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'bkash',
                    'transaction_id' => $data['paymentID'] ?? null,
                    'amount' => $order->total_amount,
                    'status' => 'pending',
                    'gateway_response' => $data,
                ]);

                return ['success' => true, 'url' => $data['bkashURL'] ?? null];
            }

            return ['success' => false, 'error' => $response->json('errorMessage') ?? 'bKash payment creation failed'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function handleCallback($data)
    {
        try {
            // Execute payment
            $response = Http::withHeaders([
                'Authorization' => $this->token,
                'X-APP-Key' => $this->appKey,
            ])->post("{$this->baseUrl}/tokenized/checkout/execute", [
                'paymentID' => $data['paymentID'],
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                $payment = Payment::where('transaction_id', $data['paymentID'])->first();
                
                if (!$payment) {
                    return ['success' => false, 'error' => 'Payment not found'];
                }

                if ($result['statusCode'] === '0000') {
                    $payment->update([
                        'status' => 'success',
                        'transaction_id' => $result['trxID'],
                        'paid_at' => now(),
                        'gateway_response' => $result,
                    ]);

                    $payment->order->update([
                        'payment_status' => 'paid',
                        'transaction_id' => $result['trxID'],
                        'status' => 'processing',
                    ]);

                    return ['success' => true, 'message' => 'Payment successful', 'trxID' => $result['trxID']];
                }

                $payment->update([
                    'status' => 'failed',
                    'gateway_response' => $result,
                ]);

                return ['success' => false, 'error' => $result['statusMessage'] ?? 'Payment failed'];
            }

            return ['success' => false, 'error' => 'Payment execution failed'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
