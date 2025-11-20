# Game Top-Up Backend API Documentation

## Authentication

### Admin Login
```
POST /api/auth/admin/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

### Member Login
```
POST /api/auth/login
Content-Type: application/json

{
  "email": "member@example.com",
  "password": "password"
}
```

## Member Endpoints

### Get Profile
```
GET /api/profile
Authorization: Bearer {token}
```

### Update Profile
```
PUT /api/profile
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "New Name",
  "email": "newemail@example.com"
}
```

### Reset Password
```
POST /api/reset-password
Authorization: Bearer {token}
Content-Type: application/json

{
  "current_password": "oldpassword",
  "password": "newpassword",
  "password_confirmation": "newpassword"
}
```

## Transaction Endpoints

### Create Order (Authenticated)
```
POST /api/orders
Authorization: Bearer {token}
Content-Type: application/json

{
  "product_id": 1,
  "quantity": 1,
  "payment_method_id": 1
}
```

### Guest Checkout
```
POST /api/guest-checkout
Content-Type: application/json

{
  "product_id": 1,
  "quantity": 1,
  "payment_method_id": 1,
  "customer_email": "guest@example.com"
}
```

### Check Order Status
```
GET /api/orders/{order_id}
Authorization: Bearer {token}
```

## Public Endpoints

### Get Products
```
GET /api/products?game_id=1&category_id=1
```

### Get Games
```
GET /api/games
```

### Get Categories
```
GET /api/categories
```

### Get Payment Methods
```
GET /api/payment-methods
```

## Admin Endpoints

### CRUD Games
```
GET /api/admin/games
POST /api/admin/games
PUT /api/admin/games/{id}
DELETE /api/admin/games/{id}
```

### Analytics
```
GET /api/admin/analytics
```

## Payment Callback
```
POST /api/payment/callback
X-Callback-Signature: {signature}
Content-Type: application/json

{
  "reference": "ORDER-123",
  "status": "PAID"
}
```

## Environment Variables

```
APP_NAME="Game Top-Up"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=topup_game
DB_USERNAME=root
DB_PASSWORD=

DIGIFLAZZ_USERNAME=
DIGIFLAZZ_API_KEY=
DIGIFLAZZ_BASE_URL=https://api.digiflazz.com/v1

VIP_RESELLER_USERNAME=
VIP_RESELLER_API_KEY=
VIP_RESELLER_BASE_URL=https://api.vip-reseller.co.id/v1

TRIPAY_API_KEY=
TRIPAY_PRIVATE_KEY=
TRIPAY_MERCHANT_CODE=
TRIPAY_BASE_URL=https://tripay.co.id/api-sandbox

FONNTE_TOKEN=
FONNTE_BASE_URL=https://api.fonnte.com
