<?php

namespace App\ViewModels;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductViewModel
{
    public function __construct(
        public readonly Product $product,
        public readonly Collection|array $relatedProducts,
        public readonly Collection|array $categories,
        public readonly int $cartCount = 0
    ) {}

    public static function fromProduct(Product $product, Collection|array|null $relatedProducts = null, Collection|array|null $categories = null, int $cartCount = 0): self
    {
        return new self(
            $product,
            $relatedProducts ?? Product::active()
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->limit(4)
                ->get(),
            $categories ?? collect([]),
            $cartCount
        );
    }
}
