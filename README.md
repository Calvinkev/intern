# CodeBase Food Ordering System

A complete production-ready food ordering system built with Laravel 12, Blade + Bootstrap 5, and MySQL. The system supports multiple user roles (Customer, Restaurant Admin, Delivery Personnel, System Administrator) with comprehensive features for ordering, restaurant management, delivery tracking, and system administration.

## Features

### Customer Features
- Browse restaurants and food items with search and filtering
- Shopping cart management (add, update, remove, clear)
- Order placement with multiple payment methods
- Order tracking and history
- Restaurant reviews and ratings

### Restaurant Admin Features
- Restaurant profile management
- Menu management (add, edit, delete food items)
- Order management and status updates
- Sales analytics and popular items tracking
- Operating hours and delivery settings

### Delivery Personnel Features
- Available delivery assignments
- Delivery status updates (picked up, delivered)
- Earnings tracking
- Delivery history

### System Admin Features
- User management (activate/deactivate accounts)
- Restaurant management (approve/reject restaurants)
- Order monitoring and analytics
- Platform statistics and reports
- Revenue tracking

### Technical Features
- Role-based authentication and authorization
- REST API for mobile integration
- Database seeders with realistic data
- Responsive Bootstrap 5 UI
- Soft deletes and audit trails
- Transaction-based order processing

## Requirements

- PHP 8.3+
- MySQL 5.7+
- Composer
- Node.js & NPM (for asset compilation)

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd codebase-food-ordering
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Update `.env` with your database credentials:
```
DB_DATABASE=codebase_food_ordering
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations:
```bash
php artisan migrate
```

6. Seed the database:
```bash
php artisan db:seed
```

7. Compile assets:
```bash
npm run dev
```

8. Start the development server:
```bash
php artisan serve
```

## Default Users

After seeding, you can log in with these accounts (password: `password`):

### System Admin
- Email: `admin@codebase.com`
- Role: System Administrator

### Restaurant Admins
- Email: `john@restaurant.com` (Burger Palace)
- Email: `jane@restaurant.com` (Pizza Heaven)
- Email: `bob@restaurant.com` (Asian Fusion Kitchen)
- Role: Restaurant Administrator

### Delivery Personnel
- Email: `mike@delivery.com`
- Email: `sarah@delivery.com`
- Role: Delivery Personnel

### Customers
- Email: `alice@customer.com`
- Email: `charlie@customer.com`
- Email: `diana@customer.com`
- Role: Customer

## API Endpoints

### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user
- `GET /api/user` - Get current user
- `PUT /api/user` - Update user profile

### Public Endpoints
- `GET /api/restaurants` - List restaurants
- `GET /api/restaurants/{slug}` - Get restaurant details
- `GET /api/foods` - List food items
- `GET /api/foods/{slug}` - Get food details
- `GET /api/categories` - List categories

### Protected Endpoints (Require Authentication)
- `GET /api/cart` - Get user's cart
- `POST /api/cart/add` - Add item to cart
- `PUT /api/cart/{id}` - Update cart item
- `DELETE /api/cart/{id}` - Remove cart item
- `POST /api/cart/clear` - Clear cart
- `GET /api/orders` - List user's orders
- `GET /api/orders/{orderNumber}` - Get order details
- `POST /api/orders` - Place new order
- `POST /api/orders/{orderNumber}/cancel` - Cancel order

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── RestaurantController.php
│   │   ├── FoodController.php
│   │   ├── CartController.php
│   │   ├── OrderController.php
│   │   ├── RestaurantAdminController.php
│   │   ├── DeliveryController.php
│   │   ├── SystemAdminController.php
│   │   └── Api/
│   │       ├── AuthController.php
│   │       ├── RestaurantController.php
│   │       ├── FoodController.php
│   │       ├── CartController.php
│   │       └── OrderController.php
│   └── Middleware/
│       ├── IsCustomer.php
│       ├── IsRestaurantAdmin.php
│       ├── IsDeliveryPersonnel.php
│       └── IsSystemAdmin.php
├── Models/
│   ├── User.php
│   ├── Restaurant.php
│   ├── Category.php
│   ├── Food.php
│   ├── FoodImage.php
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Payment.php
│   ├── Delivery.php
│   ├── Review.php
│   ├── Notification.php
│   ├── Setting.php
│   └── ActivityLog.php
database/
├── migrations/
└── seeders/
    ├── CategorySeeder.php
    ├── UserSeeder.php
    ├── RestaurantSeeder.php
    └── FoodSeeder.php
resources/
└── views/
    ├── layouts/
    ├── home.blade.php
    ├── restaurants/
    ├── foods/
    ├── cart/
    ├── orders/
    ├── restaurant-admin/
    ├── delivery/
    └── admin/
routes/
├── web.php
└── api.php
```

## Database Schema

The system uses a normalized database schema with the following main tables:

- **users** - User accounts with role-based access
- **restaurants** - Restaurant profiles and settings
- **categories** - Food categories
- **foods** - Menu items
- **food_images** - Food item images
- **cart** - Shopping carts
- **cart_items** - Cart line items
- **orders** - Customer orders
- **order_items** - Order line items
- **payments** - Payment records
- **deliveries** - Delivery assignments
- **reviews** - Customer reviews
- **notifications** - System notifications
- **settings** - System configuration
- **activity_logs** - Audit trail

## Technologies Used

- **Backend**: Laravel 12, PHP 8.3+
- **Frontend**: Blade Templates, Bootstrap 5, Bootstrap Icons
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **API**: RESTful API with Laravel Sanctum
- **Caching**: Redis (configured but optional)

## Security Features

- Role-based authentication and authorization
- CSRF protection
- SQL injection prevention via Eloquent ORM
- XSS protection via Blade templating
- Password hashing with bcrypt
- Soft deletes for data recovery

## Future Enhancements

- Payment gateway integration (Stripe, Mobile Money)
- Real-time order tracking with WebSockets
- Redis caching for performance optimization
- Queue system for background job processing
- Advanced search functionality
- Push notifications
- Dark mode support
- Multi-language support

## License

This project is open-sourced software licensed under the MIT license.

## Support

For support, please contact the development team or open an issue in the repository.
# intern
