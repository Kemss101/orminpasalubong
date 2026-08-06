# Quick Start Guide - New Features

## 🚀 Get Started in 5 Minutes

### Installation

```bash
# 1. Update dependencies
composer install
npm install

# 2. Configure Google OAuth
# Edit .env file and add:
GOOGLE_CLIENT_ID=your_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_secret
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback

# 3. Run migrations
php artisan migrate

# 4. Clear cache
php artisan config:cache

# 5. Start server
php artisan serve
```

---

## 📋 Feature Checklist

After installation, verify each feature:

### ✓ Google OAuth
- Go to http://localhost:8000/login
- Click "Login with Google"
- Should redirect to Google login

### ✓ GCash Payment
- Place an order as customer
- Choose "GCash" as payment method
- Enter GCash number (any 11 digits)
- Get reference number
- Enter receipt to verify

### ✓ Delivery Tracking
- After payment, order gets delivery tracking
- Go to /admin/delivery/all (as admin)
- Update delivery status
- Customer sees updates in /delivery/history

### ✓ Cashback
- Visit http://localhost:8000/cashback/dashboard
- Should show 0 balance initially
- After payment, balance increases (0.05%)
- Can redeem cashback

### ✓ Admin Dashboard
- Go to http://localhost:8000/admin/gcash/transactions (as admin)
- Should see all GCash transactions
- Can filter, search, and manage

---

## 📁 File Structure

```
app/
├── Models/
│   ├── GcashTransaction.php
│   ├── DeliveryTracking.php
│   ├── Cashback.php
│   └── CashbackTransaction.php
├── Http/Controllers/
│   ├── PaymentController.php
│   ├── DeliveryController.php
│   ├── GoogleAuthController.php
│   ├── CashbackController.php
│   └── Admin/GcashMonitoringController.php

database/
├── migrations/
│   ├── 2026_05_01_000001_create_gcash_transactions_table.php
│   ├── 2026_05_01_000002_create_delivery_tracking_table.php
│   ├── 2026_05_01_000003_create_cashback_table.php
│   ├── 2026_05_01_000004_add_google_auth_to_users_table.php
│   └── 2026_05_01_000005_add_payment_delivery_to_orders_table.php

resources/views/
├── payment/
│   ├── payment-form.blade.php
│   └── history.blade.php
├── cashback/
│   └── dashboard.blade.php
└── admin/
    ├── gcash-monitoring.blade.php
    ├── delivery-monitoring.blade.php
    └── cashback-monitoring.blade.php

routes/web.php (updated with all new routes)
```

---

## 🔍 Testing Workflows

### Test GCash Payment
1. Login as customer
2. Add products to cart
3. Checkout
4. Select GCash payment
5. Enter any 11-digit GCash number
6. Get reference number
7. Enter any receipt number to verify
8. Payment confirmed

### Test Google Login
1. Go to /login
2. Click Google button
3. Choose account or login
4. Should redirect to dashboard
5. New user? Account auto-created

### Test Delivery Tracking
1. Login as admin
2. Go to /admin/delivery/all
3. Find an order
4. Click Edit
5. Change status to "Shipped"
6. As customer, visit /delivery/history
7. Should show updated status

### Test Cashback
1. Complete a ₱1000 payment
2. Visit /cashback/dashboard
3. Should show ₱0.50 cashback earned
4. Click "Redeem" button
5. Enter amount ≤ balance
6. Balance deducted

### Test Admin GCash Dashboard
1. Login as admin
2. Go to /admin/gcash/transactions
3. Should see all transactions
4. Can filter by status or date
5. Click transaction to view details
6. Change status if needed

---

## 🐛 Common Issues & Fixes

### "Socialite package not found"
```bash
composer require laravel/socialite
php artisan config:cache
```

### "Google OAuth button not showing"
- Check /resources/views/welcome.blade.php has OAuth button
- Verify routes exist: `php artisan route:list | grep auth/google`
- Check services.php has google config

### "Payment verification fails"
- Verify GcashTransaction exists
- Check reference number format: `GCH-YYYYMMDDHIS-XXXXXXXX`
- Ensure order belongs to user

### "Cashback not calculating"
- Check payment_status = 'completed'
- Verify Order::calculateCashback() runs
- Check Cashback model exists for user

### "Migration errors"
```bash
# Check migration status
php artisan migrate:status

# Rollback and retry
php artisan migrate:rollback
php artisan migrate
```

---

## 📚 Key Routes Reference

### Customer Routes
```
GET  /payment/order/{order}           - Payment form
POST /payment/gcash                   - Process payment
POST /payment/verify/{transaction}    - Verify payment
GET  /payment/history                 - Payment history

GET  /delivery/order/{order}          - Delivery status
GET  /delivery/history                - Delivery history

GET  /cashback/balance                - Get balance (JSON)
GET  /cashback/dashboard              - Dashboard
GET  /cashback/history                - History
POST /cashback/redeem                 - Redeem

GET  /auth/google                     - Google login
GET  /auth/google/callback            - OAuth callback
POST /account/link-google             - Link account
POST /account/unlink-google           - Unlink account
```

### Admin Routes
```
GET  /admin/gcash/transactions        - Dashboard
GET  /admin/gcash/transactions/{id}   - Details
PATCH /admin/gcash/transactions/{id}/status - Update

GET  /admin/delivery/all              - Deliveries
PATCH /admin/delivery/{order}         - Update delivery

GET  /admin/cashback                  - Cashback management
PATCH /admin/cashback/{id}/adjust     - Adjust balance
```

---

## 🎯 Example Usage in Code

### Get User Cashback Balance
```php
$user = Auth::user();
$balance = $user->getCashbackBalance(); // float

// Or create if doesn't exist
$cashback = $user->getOrCreateCashback();
$balance = $cashback->balance;
```

### Add Cashback After Payment
```php
$order = Order::find(1);
$cashbackAmount = $order->calculateCashback(); // Returns 0.0005 * total

$cashback = $order->user->getOrCreateCashback();
$cashback->addCashback($cashbackAmount, $order->id, 'Purchase reward');
```

### Update Delivery Status
```php
$order = Order::find(1);
$delivery = $order->deliveryTracking;
$delivery->updateStatus('Shipped');
$order->update(['delivery_status' => 'Shipped']);
```

### Create Transaction
```php
$transaction = GcashTransaction::create([
    'user_id' => Auth::id(),
    'order_id' => $order->id,
    'reference_number' => 'GCH-' . now()->format('YmdHis') . '-' . Str::random(8),
    'amount' => $order->total_amount,
    'status' => 'pending',
    'type' => 'payment',
]);
```

---

## 📞 Need Help?

1. **Setup Issues?** → See `ENV_SETUP_GUIDE.md`
2. **Feature Details?** → See `FEATURES_IMPLEMENTATION.md`
3. **Implementation Info?** → See `IMPLEMENTATION_SUMMARY.md`
4. **Laravel Docs?** → https://laravel.com/docs
5. **Socialite Help?** → https://laravel.com/docs/socialite

---

## ✨ Next Steps

1. ✅ Run migrations
2. ✅ Configure Google OAuth
3. ✅ Test each feature
4. ✅ Update UI components (customize buttons, colors, etc.)
5. ✅ Integrate with real GCash API (if needed)
6. ✅ Set up email notifications
7. ✅ Deploy to production

