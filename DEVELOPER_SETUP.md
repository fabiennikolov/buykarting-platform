# 🚀 BuyKarting.com Developer Setup Guide

## 📋 Prerequisites

Before setting up the development environment, ensure you have:

- **PHP 8.3+** with extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML
- **Composer** (latest version)
- **Node.js 18+** and **npm/yarn**
- **Git**
- **SQLite** (for development) or **MySQL/PostgreSQL** (for production)

## 🛠️ Quick Setup

### 1. Clone and Install Dependencies
```bash
# Clone the repository
git clone <repository-url>
cd buykarting-platform

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 2. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database (for development)
touch database/database.sqlite
```

### 3. Database Setup
```bash
# Run migrations
php artisan migrate

# (Optional) Seed with sample data
php artisan db:seed
```

### 4. Start Development Server
```bash
# Start all development services
composer run dev

# Or start individually:
# Backend: php artisan serve
# Frontend: npm run dev
# Queue: php artisan queue:work
# Logs: php artisan pail
```

## 🔧 Development Tools

### Code Quality
```bash
# Format PHP code
vendor/bin/pint --dirty

# Format JavaScript/Vue code
npm run format

# Check code formatting
npm run format:check

# Run ESLint
npm run lint

# Run all quality checks
npm run lint && vendor/bin/pint --test
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ListingTest.php

# Run tests with coverage
php artisan test --coverage

# Run browser tests (Pest v4)
php artisan test --browser
```

### Database Operations
```bash
# Create new migration
php artisan make:migration create_listings_table

# Create model with migration
php artisan make:model Listing -m

# Create factory
php artisan make:factory ListingFactory

# Create seeder
php artisan make:seeder ListingSeeder

# Reset database
php artisan migrate:fresh --seed
```

## 📁 Project Structure

```
buykarting-platform/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Application controllers
│   │   ├── Middleware/      # Custom middleware
│   │   └── Requests/        # Form request validation
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   └── Providers/           # Service providers
├── database/
│   ├── factories/           # Model factories
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── js/
│   │   ├── components/      # Reusable Vue components
│   │   ├── layouts/         # Page layouts
│   │   ├── pages/           # Inertia.js pages
│   │   ├── composables/     # Vue composables
│   │   └── types/           # TypeScript type definitions
│   └── css/                 # Stylesheets
├── routes/
│   ├── web.php              # Web routes
│   ├── api.php              # API routes
│   └── console.php          # Console commands
└── tests/
    ├── Feature/             # Feature tests
    ├── Unit/                # Unit tests
    └── Browser/             # Browser tests (Pest v4)
```

## 🎨 Frontend Development

### Vue 3 + Inertia.js
- **Pages**: Located in `resources/js/pages/`
- **Components**: Reusable components in `resources/js/components/`
- **Layouts**: Page layouts in `resources/js/layouts/`
- **Composables**: Shared logic in `resources/js/composables/`

### Styling with Tailwind CSS v4
- **Configuration**: Uses CSS-first approach with `@import "tailwindcss"`
- **Custom Properties**: Defined in `resources/css/app.css`
- **Dark Mode**: Built-in support with `dark:` variants
- **Components**: shadcn/ui components available

### TypeScript Support
- **Configuration**: `tsconfig.json` with Vue 3 support
- **Types**: Custom types in `resources/js/types/`
- **Components**: Full TypeScript support for Vue components

## 🗄️ Database Development

### Models and Relationships
```php
// User Model
class User extends Authenticatable
{
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }
    
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }
}

// Listing Model
class Listing extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

### Factories and Seeders
```php
// ListingFactory
Listing::factory()
    ->count(50)
    ->create([
        'category' => 'go_kart',
        'condition' => 'used',
    ]);
```

## 🧪 Testing Strategy

### Unit Tests
- Test individual model methods
- Test business logic in services
- Test utility functions

### Feature Tests
- Test complete user workflows
- Test API endpoints
- Test authentication flows

### Browser Tests (Pest v4)
- Test user interactions
- Test responsive design
- Test JavaScript functionality

## 🚀 Deployment

### Production Build
```bash
# Build frontend assets
npm run build

# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

## 🔍 Debugging

### Laravel Debugging
```bash
# View logs
php artisan pail

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Debug with Tinker
php artisan tinker
```

### Frontend Debugging
- **Vue DevTools**: Browser extension for Vue debugging
- **Browser Console**: Check for JavaScript errors
- **Network Tab**: Monitor API requests
- **Lighthouse**: Performance and accessibility testing

## 📚 Useful Commands

### Artisan Commands
```bash
# List all available commands
php artisan list

# Create new controller
php artisan make:controller ListingController

# Create new middleware
php artisan make:middleware CheckSubscription

# Create new job
php artisan make:job ProcessListingImage

# Create new event
php artisan make:event ListingCreated
```

### NPM Scripts
```bash
# Development
npm run dev              # Start Vite dev server
npm run build            # Build for production
npm run build:ssr        # Build with SSR support

# Code Quality
npm run lint             # Run ESLint
npm run format           # Format with Prettier
npm run format:check     # Check formatting

# Type Checking
npm run type-check       # Run TypeScript compiler
```

## 🤝 Contributing

### Git Workflow
1. Create feature branch: `git checkout -b feature/listing-crud`
2. Make changes and commit: `git commit -m "Add listing CRUD operations"`
3. Push branch: `git push origin feature/listing-crud`
4. Create pull request

### Code Standards
- Follow PSR-12 for PHP code
- Use ESLint and Prettier for JavaScript/Vue
- Write tests for new features
- Update documentation as needed

## 🆘 Troubleshooting

### Common Issues

**Vite manifest error:**
```bash
npm run build
# or
npm run dev
```

**Database connection issues:**
```bash
# Check .env file
# Ensure database exists
# Run migrations
php artisan migrate
```

**Permission issues:**
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

**Node modules issues:**
```bash
# Clear npm cache
npm cache clean --force
# Reinstall dependencies
rm -rf node_modules package-lock.json
npm install
```

## 📞 Support

- **Documentation**: Check `PROJECT_OVERVIEW.md` for detailed project information
- **Issues**: Create GitHub issues for bugs or feature requests
- **Discussions**: Use GitHub discussions for questions and ideas

Happy coding! 🏁
