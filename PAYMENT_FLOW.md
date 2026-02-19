# Midtrans Payment Integration - Success & Failed Pages

## Overview
Implementasi halaman success dan failed untuk payment menggunakan Midtrans dengan Livewire Components.

## Struktur File

### Livewire Components
- `app/Livewire/PaymentSuccess.php` - Component untuk halaman payment berhasil
- `app/Livewire/PaymentFailed.php` - Component untuk halaman payment gagal

### Controller
- `app/Http/Controllers/MidtransCallbackController.php` - Handle callback dari Midtrans

### Views
- `resources/views/livewire/payment-success.blade.php` - View halaman success
- `resources/views/livewire/payment-failed.blade.php` - View halaman failed

### Database
- `orders` table - Menyimpan order details
- `order_items` table - Menyimpan item-item dalam order
- `payments` table - Menyimpan payment transaction details

## Flow Pembayaran

### 1. User Checkout dari Cart
```
Cart.php → checkout() method:
1. Validate selected items
2. Create Order in database
3. Create Order Items
4. Save selected items to session
5. Get Midtrans Snap Token
6. Show payment modal
```

### 2. User Melakukan Pembayaran
- Midtrans Snap modal terbuka
- User memilih metode pembayaran
- User menyelesaikan pembayaran

### 3. Midtrans Callback
```
POST /midtrans/callback
→ MidtransCallbackController@handle:
  1. Verifikasi notification dari Midtrans
  2. Update/Create payment record
  3. Update order status
  4. Return JSON response
```

### 4. Redirect ke Success/Failed Page
```
GET /midtrans/finish?order_id={orderId}
→ MidtransCallbackController@finish:
  1. Check order status
  2. Redirect to success page if paid
  3. Redirect to failed page if not paid
```

### 5. Display Success/Failed Page
Success: `/payment/success/{orderId}`
- Show order details
- Show payment info
- Clear cart items
- Download invoice option
- Continue shopping button

Failed: `/payment/failed/{orderId}`
- Show error message
- Show order details
- Retry payment button
- Help section

## Routes

```php
// Payment Pages (Auth Required)
Route::get('/payment/success/{orderId}', PaymentSuccess::class)->name('payment.success');
Route::get('/payment/failed/{orderId}', PaymentFailed::class)->name('payment.failed');

// Midtrans Callbacks (No Auth)
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);
Route::get('/midtrans/finish', [MidtransCallbackController::class, 'finish']);
```

## Database Schema

### Payments Table
```sql
- id
- order_id (FK to orders)
- gateway (enum: midtrans, xendit, manual)
- gateway_transaction_id
- payment_type (va, ewallet, cc, qris)
- payment_method (bca_va, gopay, credit_card, etc)
- amount
- currency (default: IDR)
- status (enum: pending, success, failed, expired)
- fraud_status
- payload (JSON - full Midtrans callback)
- paid_at
- expired_at
- created_at
- updated_at
```

## Status Mapping

### Midtrans → Our Status
- `capture` + `fraud_status=accept` → `success`
- `settlement` → `success`
- `pending` → `pending`
- `cancel` → `failed`
- `deny` → `failed`
- `expire` → `expired`

### Payment Type Mapping
- `bank_transfer`, `*_va` → `va`
- `gopay`, `shopeepay`, `qris` → `ewallet`
- `credit_card` → `cc`

## Features

### Payment Success Page
✅ Animated success icon
✅ Order details display
✅ Items list with pricing
✅ Payment method & timestamp
✅ Shipping address
✅ Download invoice button
✅ Continue shopping button
✅ Next steps information
✅ Automatic cart clearing

### Payment Failed Page
✅ Clear failure indication
✅ Error message display
✅ Order details (not processed)
✅ Retry payment button
✅ Back to cart button
✅ Common failure reasons
✅ Support contact info

## Configuration

### Midtrans Settings
File: `config/midtrans.php`
```php
return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => true,
    'is_3ds' => true,
];
```

### Environment Variables
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_IS_PRODUCTION=false
```

## Testing

### Success Flow
1. Add items to cart
2. Select items
3. Click checkout
4. Complete payment in Midtrans Snap
5. Should redirect to success page
6. Cart should be cleared

### Failed Flow
1. Add items to cart
2. Select items
3. Click checkout
4. Cancel payment in Midtrans Snap
5. Should redirect to failed page
6. Can retry payment

## Security

1. **Callback Verification**: Midtrans callbacks are verified using server key
2. **User Authorization**: Success/Failed pages check if order belongs to logged-in user
3. **Order Validation**: Order code is validated before showing details
4. **CSRF Protection**: POST routes are protected by Laravel CSRF middleware

## Future Enhancements

- [ ] PDF invoice generation
- [ ] Email notifications
- [ ] Order tracking page
- [ ] WhatsApp notifications
- [ ] Payment receipt download
- [ ] Multiple payment retry with different methods
