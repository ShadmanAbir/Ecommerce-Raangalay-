# Raangalay — E-Commerce Platform

A Laravel 11 e-commerce platform for **Raangalay**, a Bangladeshi heritage clothing brand.
The storefront opens with a cinematic scroll journey down a banyan tree — six branch
"stations", each arriving at a photograph of a garment hanging in the tree, ending at
the roots.

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.4 · Laravel 11 |
| Frontend | Blade · Livewire 3.5 · Alpine.js · Tailwind CSS (CDN) |
| Database | MySQL / MariaDB |
| Auth | Laravel session auth + guest sessions, Sanctum installed |
| Payments | bKash (sandbox) · Cash on Delivery |

## Quick Start

```bash
# 1. Install PHP dependencies
composer install

# 2. Environment
copy .env.example .env
php artisan key:generate

# 3. Database — create `raangala_ecom` in MySQL, set credentials in .env
php artisan migrate

# 4. Link the public storage disk (product/category uploads)
php artisan storage:link

# 5. Run
php artisan serve
```

- Storefront: `http://localhost:8000`
- Admin dashboard: `http://localhost:8000/admin` (requires a user with `role = admin`)

## Project Structure

```
app/
├── Helpers/            ImageHelper (storage_asset() with public fallback)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/      Dashboard, Products, Categories, Banners
│   │   ├── Auth/       Login / Registration
│   │   ├── Shop/       Products, Cart, Checkout
│   │   └── User/       Customer dashboard
│   └── Middleware/     AdminMiddleware (role gate)
├── Models/             Product, Category, Order, Cart, Wishlist, Payment, ...
├── Services/
│   ├── CartService     Guest + authenticated cart handling
│   ├── OrderService    Order placement & stock management
│   ├── PaymentService  Gateway dispatch
│   ├── GuestService    Guest session lifecycle
│   └── PaymentGateways/BkashService
└── ViewModels/         ProductViewModel, CartViewModel

resources/views/
├── shop/               Landing (banyan journey), category, product, cart, checkout
├── admin/              Product / category / banner CRUD
├── auth/               Login, register
└── layouts/            App shell, header, footer, admin
```

## Signature Feature — The Branch Line

The landing page (`resources/views/shop/index.blade.php`) is a scroll-driven
cinematic journey:

- **9 scenes** — treetop intro → 6 branch stations → trunk → ground
- Camera rides the tree body like a train; blurred foliage strips rush past
  between stations (parallax travel layer)
- Each station settles on a real photograph of a garment in the banyan tree,
  with a gold glass category card
- Progress rail navigation, film grain, dust motes, `prefers-reduced-motion`
  support — all vanilla JS, no animation libraries

## Roadmap & Status

### ✅ Done
- [x] Product catalog with categories, images, stock tracking
- [x] Guest sessions with cart/wishlist transfer on login
- [x] Cart & checkout flow (COD)
- [x] bKash sandbox integration
- [x] Admin CRUD — products, categories, banners
- [x] Cinematic banyan-tree landing experience
- [x] Customer dashboard & order history

### 🚧 In Progress
- [ ] Order management screens in admin
- [ ] Image optimization pipeline (landing photos are full-resolution)

### 🗺️ Planned
- [ ] Nagad & SSLCommerz gateways
- [ ] Coupons & discount engine
- [ ] Product reviews and ratings
- [ ] Multi-vendor support (schema present, UI pending)
- [ ] Email notifications (order confirmation, shipping)
- [ ] Localization (Bangla/English toggle)
- [ ] Move Tailwind from CDN to Vite build
- [ ] Test suite (Feature + Unit)

## License

Open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
