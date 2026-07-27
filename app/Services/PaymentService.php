<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Order;
use App\Services\PaymentGateways\BkashService;

class PaymentService
{
    public function processPayment($order, $method)
    {
        return match($method) {
            'cod' => $this->processCOD($order),
            'bkash' => $this->processBkash($order),
            'nagad' => $this->processNagad($order),
            'sslcommerz' => $this->processSSLCommerz($order),
            default => throw new \Exception('Invalid payment method'),
        };
    }

    protected function processCOD($order)
    {
        return Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'cod',
            'amount' => $order->total_amount,
            'status' => 'pending',
        ]);
    }

    protected function processBkash($order)
    {
        $bkashService = new BkashService();
        return $bkashService->createPayment($order);
    }

    protected function processNagad($order)
    {
        // TODO: Implement Nagad payment
        return Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'nagad',
            'amount' => $order->total_amount,
            'status' => 'pending',
        ]);
    }

    protected function processSSLCommerz($order)
    {
        // TODO: Implement SSLCommerz payment
        return Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'sslcommerz',
            'amount' => $order->total_amount,
            'status' => 'pending',
        ]);
    }

    public function handlePaymentCallback($data, $method)
    {
        return match($method) {
            'bkash' => $this->handleBkashCallback($data),
            'nagad' => $this->handleNagadCallback($data),
            'sslcommerz' => $this->handleSSLCommerzCallback($data),
            default => throw new \Exception('Invalid payment method'),
        };
    }

    protected function handleBkashCallback($data)
    {
        $bkashService = new BkashService();
        return $bkashService->handleCallback($data);
    }

    protected function handleNagadCallback($data)
    {
        // TODO: Implement Nagad callback
        return ['success' => false, 'error' => 'Nagad payment not implemented'];
    }

    protected function handleSSLCommerzCallback($data)
    {
        // TODO: Implement SSLCommerz callback
        return ['success' => false, 'error' => 'SSLCommerz payment not implemented'];
    }
}
