#!/bin/bash

# BuyKarting.com Code Quality Script
# Runs all code quality checks and formatting

set -e

echo "🔍 BuyKarting.com Code Quality Check"
echo "===================================="

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_status() {
    echo -e "${BLUE}→${NC} $1"
}

print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "Please run this script from the Laravel project root"
    exit 1
fi

echo ""
print_status "Running PHP code formatting (Laravel Pint)..."
if vendor/bin/pint --dirty; then
    print_success "PHP code formatted"
else
    print_error "PHP formatting failed"
    exit 1
fi

echo ""
print_status "Running JavaScript/Vue linting (ESLint)..."
if npm run lint; then
    print_success "JavaScript/Vue code linted"
else
    print_error "JavaScript/Vue linting failed"
    exit 1
fi

echo ""
print_status "Running JavaScript/Vue formatting (Prettier)..."
if npm run format; then
    print_success "JavaScript/Vue code formatted"
else
    print_error "JavaScript/Vue formatting failed"
    exit 1
fi

echo ""
print_status "Running tests..."
if php artisan test; then
    print_success "All tests passed"
else
    print_error "Tests failed"
    exit 1
fi

echo ""
print_success "All code quality checks passed! 🎉"
echo ""
echo "📋 Summary:"
echo "   ✓ PHP code formatted with Laravel Pint"
echo "   ✓ JavaScript/Vue code linted with ESLint"
echo "   ✓ JavaScript/Vue code formatted with Prettier"
echo "   ✓ All tests passed"
echo ""
echo "🚀 Code is ready for commit!"
