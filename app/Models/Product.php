<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'vendor_id',
        'name',
        'slug',
        'description',
        'price',
        'discount',
        'stock',
        'sku',
        'tags',
        'sizes',
        'colors',
        'remarks',
        'weight',
        'status',
        'is_trending',
        'is_new',
        'is_featured',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'weight' => 'decimal:2',
        'tags' => 'array',
        'sizes' => 'array',
        'colors' => 'array',
        'is_trending' => 'boolean',
        'is_new' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByRemark($query, $remark)
    {
        return $query->where('remarks', 'like', '%' . $remark . '%');
    }

    public function scopeSearch($query, $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }
        
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%')
              ->orWhere('description', 'like', '%' . $keyword . '%')
              ->orWhere('sku', 'like', '%' . $keyword . '%')
              ->orWhereJsonContains('tags', $keyword);
        });
    }

    public function isInStock(): bool
    {
        return $this->totalStock() > 0;
    }

    public function totalStock(): int
    {
        return $this->stocks()->sum('quantity') ?? 0;
    }

    public function isNew(): bool
    {
        return $this->created_at > now()->subDays(30);
    }

    public function getDiscountedPrice()
    {
        if ($this->discount > 0) {
            return $this->price - ($this->price * $this->discount / 100);
        }
        return $this->price;
    }
}
