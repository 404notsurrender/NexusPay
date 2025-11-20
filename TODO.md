# TODO: Build Game Top-Up Backend with Laravel 11

## Step 1: Initialize Laravel 11 Project ✅
- Install Laravel 11 via Composer ✅
- Configure .env file with database and API keys

## Step 2: Set Up Authentication
- Install Laravel Sanctum
- Create User model with roles (admin/member)
- Create authentication controllers and routes
- Implement IP whitelist middleware for admin

## Step 3: Create Models and Migrations
- User (with roles)
- Game
- Category
- Product
- Order
- Deposit
- PaymentMethod
- Banner
- Popup
- Config
- PopularGame

## Step 4: Create Middlewares
- Authenticate
- Admin
- Member
- IPWhitelist

## Step 5: Create Controllers
- AuthController
- AdminController
- MemberController
- TransactionController
- GameController
- CategoryController
- ProductController
- OrderController
- DepositController
- PaymentMethodController
- BannerController
- PopupController
- ConfigController
- PopularGameController

## Step 6: Create Service Classes
- DigiFlazzService
- VipResellerService
- TripayService
- FonnteService

## Step 7: Set Up Routes
- API routes
- Admin routes

## Step 8: Implement Queue Jobs
- OrderUpdateJob
- PaymentCallbackJob
- WhatsappJob

## Step 9: Add Security Features
- Password hashing
- CSRF protection
- Rate limiting
- Request validation

## Step 10: Create Examples and Documentation
- .env template
- API documentation
- Payload examples

## Step 11: Testing and Finalization
- Run migrations
- Test integrations
- Generate docs
