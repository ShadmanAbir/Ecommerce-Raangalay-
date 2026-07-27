<?php

namespace App\ViewModels;

use App\Models\Cart;
use Illuminate\Support\Collection;

class CartViewModel
{
    public function __construct(
        public readonly Collection|array $cartItems,
        public readonly float|string $subtotal,
        public readonly float|string $discountAmount,
        public readonly float|string $vatAmount,
        public readonly float|string $payableAmount,
        public readonly int $cartCount
    ) {}

    public static function fromCart(Collection $cartItems, array $totals): self
    {
        return new self(
            $cartItems,
            $totals['subtotal'],
            $totals['discount_amount'],
            $totals['vat_amount'],
            $totals['payable_amount'],
            $cartItems->count()
        );
    }
}
