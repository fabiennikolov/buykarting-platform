# 🏁 BuyKarting.com - Karting Equipment Marketplace

## 📋 Project Overview

BuyKarting.com is a comprehensive e-commerce platform for karting equipment, built with modern web technologies. The platform operates on a **freemium model** that allows users to publish a limited number of free listings before transitioning to paid subscriptions. The platform features intelligent geolocation-based filtering and a user-friendly interface.

### 🎯 Key Features
- **Freemium Model**: 3 free listings per user, unlimited with paid plans
- **Geolocation Filtering**: Smart location-based search and recommendations
- **Multi-Currency Support**: BGN, EUR, USD support
- **User Types**: Individual and Business account types
- **Real-time Search**: Advanced filtering by category, condition, price, and location
- **Responsive Design**: Mobile-first approach with dark/light mode support

### 🛠 Technology Stack
- **Backend**: Laravel 12 (PHP 8.3)
- **Frontend**: Vue 3 + Inertia.js v2
- **Styling**: Tailwind CSS v4
- **Database**: SQLite (development), MySQL/PostgreSQL (production)
- **Authentication**: Laravel Fortify
- **Testing**: Pest PHP v4
- **Code Quality**: ESLint, Prettier, Laravel Pint
- **Development**: Vite, TypeScript support

## 🗄️ Database Architecture

### 📊 Core Models & Relationships

We'll build the following core models and their relationships to ensure a stable database structure:

#### 👤 User Model
**Purpose**: User management and authentication

**Fields** (Migration: `create_users_table`):
```php
id (primary key, bigint)
name (string, 255)
email (string, 255, unique)
password (string, 255)
type (enum: 'individual', 'business')
country (string, 100)
state_province (string, 100)
city (string, 100)
email_verified_at (timestamp, nullable)
remember_token (string, 100, nullable)
created_at, updated_at (timestamps)
```

**Relationships**:
- `hasMany(Listing::class)` - One user can have many listings
- `hasOne(Subscription::class)` - One user can have one subscription

#### 🏷️ Listing Model
**Purpose**: Product/listing management

**Fields** (Migration: `create_listings_table`):
```php
id (primary key, bigint)
user_id (foreign key, unsigned bigint) -> references('id')->on('users')
title (string, 255)
description (text)
category (enum: 'go_kart', 'parts', 'accessories', 'consumables')
condition (enum: 'new', 'used')
price (decimal, 8, 2)
currency (string, 3) // BGN, EUR, USD
country (string, 100)
state_province (string, 100, nullable)
city (string, 100)
image_path (string, 500, nullable)
status (enum: 'active', 'sold', 'draft')
created_at, updated_at (timestamps)
```

**Relationships**:
- `belongsTo(User::class)` - Each listing belongs to one user

#### 💳 Subscription Model
**Purpose**: Subscription plan management

**Fields** (Migration: `create_subscriptions_table`):
```php
id (primary key, bigint)
user_id (foreign key, unsigned bigint) -> references('id')->on('users')
plan_name (string, 50) // 'Freemium', 'Basic', 'Premium'
starts_at (timestamp)
ends_at (timestamp, nullable)
listings_limit (integer)
created_at, updated_at (timestamps)
```

**Relationships**:
- `belongsTo(User::class)` - Each subscription belongs to one user

### 📋 Subscription Plans
- **Freemium**: 3 free listings, basic features
- **Basic**: 50 listings/month, priority support
- **Premium**: Unlimited listings, advanced analytics, featured placement

## 🚀 Development Roadmap

### Phase 1: Foundation Setup ✅
- [x] Laravel 12 installation and configuration
- [x] Database setup (SQLite for development)
- [x] Laravel Fortify for authentication
- [x] Inertia.js v2 integration
- [x] Vue 3 + TypeScript setup
- [x] Tailwind CSS v4 configuration
- [x] Development environment setup

### Phase 2: Database & Models
- [ ] Create migrations for User, Listing, and Subscription models
- [ ] Run migrations (`php artisan migrate`)
- [ ] Create Eloquent models with relationships
- [ ] Set up model factories and seeders
- [ ] Implement model validation rules

### Phase 3: Authentication & User Management
- [ ] Configure Inertia.js authentication pages
- [ ] Implement user registration with type selection (individual/business)
- [ ] Set up email verification
- [ ] Create user profile management
- [ ] Implement password reset functionality

### Phase 4: Listing Management (CRUD)
- [ ] Create `ListingController` with full CRUD operations
- [ ] Implement listing creation with image upload
- [ ] Build listing listing (index) with pagination
- [ ] Create individual listing view
- [ ] Implement listing editing and deletion
- [ ] Add listing status management (active/sold/draft)

### Phase 5: Subscription System
- [ ] Auto-create Freemium subscription on user registration
- [ ] Implement listing limit validation
- [ ] Create subscription upgrade/downgrade logic
- [ ] Build subscription management interface
- [ ] Add payment integration (Stripe/PayPal)

### Phase 6: Search & Filtering
- [ ] Implement category-based filtering
- [ ] Add condition and price range filters
- [ ] Build geolocation-based search
- [ ] Create advanced search with multiple criteria
- [ ] Implement search result ranking by location

### Phase 7: Frontend Enhancement
- [ ] Create responsive listing cards
- [ ] Implement image gallery for listings
- [ ] Add dark/light mode toggle
- [ ] Build mobile-optimized interface
- [ ] Create loading states and animations

### Phase 8: Testing & Quality Assurance
- [ ] Write unit tests for models
- [ ] Create feature tests for CRUD operations
- [ ] Test authentication flows
- [ ] Validate subscription limits
- [ ] Performance testing and optimization

## 🛠️ Development Commands

### Getting Started
```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Create database
touch database/database.sqlite

# Run migrations
php artisan migrate

# Start development server
composer run dev
```

### Code Quality
```bash
# Format PHP code
vendor/bin/pint --dirty

# Format JavaScript/Vue code
npm run format

# Run linting
npm run lint

# Run tests
php artisan test
```

### Database Operations
```bash
# Create new migration
php artisan make:migration create_listings_table

# Create new model with migration
php artisan make:model Listing -m

# Create factory
php artisan make:factory ListingFactory

# Create seeder
php artisan make:seeder ListingSeeder
```

## 📁 Project Structure

```
buykarting-platform/
├── app/
│   ├── Models/           # Eloquent models
│   ├── Http/Controllers/ # Application controllers
│   └── Services/         # Business logic services
├── database/
│   ├── migrations/       # Database migrations
│   ├── factories/        # Model factories
│   └── seeders/          # Database seeders
├── resources/
│   ├── js/
│   │   ├── pages/        # Inertia.js pages
│   │   ├── components/   # Vue components
│   │   └── layouts/      # Page layouts
│   └── css/              # Stylesheets
├── routes/
│   ├── web.php          # Web routes
│   └── api.php          # API routes
└── tests/
    ├── Feature/         # Feature tests
    └── Unit/            # Unit tests
```

## 🎯 Key Features Implementation

### Freemium Model Logic
- New users automatically get 3 free listings
- Subscription validation before listing creation
- Upgrade prompts when limit is reached
- Plan comparison and upgrade interface

### Geolocation Features
- Location-based search prioritization
- Distance calculation for nearby listings
- Country/region-specific filtering
- Local currency display

### User Experience
- Responsive design for all devices
- Fast search with real-time filtering
- Image optimization and lazy loading
- Progressive Web App capabilities

## 🔧 Configuration Files

The project includes pre-configured:
- **ESLint**: Code linting with Vue 3 support
- **Prettier**: Code formatting with Tailwind CSS plugin
- **TypeScript**: Full TypeScript support for Vue components
- **Vite**: Fast build tool with HMR
- **Pest**: Modern PHP testing framework
- **Laravel Pint**: PHP code formatting

## 📚 Additional Resources

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Vue 3 Documentation](https://vuejs.org/guide/)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Tailwind CSS v4 Documentation](https://tailwindcss.com/docs)
- [Pest PHP Testing](https://pestphp.com/docs)
