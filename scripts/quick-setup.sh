#!/bin/bash

# BuyKarting.com Quick Setup Script
# One-command setup for new developers

set -e

echo "🏁 BuyKarting.com Quick Setup"
echo "=============================="

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_status() {
    echo -e "${BLUE}→${NC} $1"
}

print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Please run this script from the Laravel project root"
    exit 1
fi

print_status "Installing PHP dependencies..."
composer install --no-interaction --quiet

print_status "Installing Node.js dependencies..."
npm install --silent

print_status "Setting up environment..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate --no-interaction --quiet
    print_success "Environment configured"
else
    print_warning "Environment file already exists"
fi

print_status "Setting up database..."
touch database/database.sqlite
php artisan migrate --no-interaction --quiet

print_status "Building frontend assets..."
npm run build --silent

print_success "Setup complete! 🎉"
echo ""
echo "🚀 Start development with:"
echo "   composer run dev"
echo ""
echo "📚 Read DEVELOPER_SETUP.md for detailed information"
