# Manual Stripe PHP Installation (If Composer Not Available)

If you don't have Composer, you can download Stripe PHP library manually:

## Option 1: Download Stripe PHP Library

1. Download from: https://github.com/stripe/stripe-php/releases/latest
2. Extract the ZIP file
3. Copy the `stripe-php-x.x.x` folder to:
   ```
   /wp-content/themes/together-forever/vendor/stripe/
   ```
4. Rename folder to just `stripe-php`

## Option 2: Use WordPress Plugin

Alternatively, install a plugin that includes Stripe PHP:
1. Install "WP Simple Pay" or similar Stripe plugin
2. It will include the Stripe PHP library
3. Our code can use that library

## After Installation

Update `inc/stripe-integration.php` to include the library:

Add at the top of the file:
```php
// Load Stripe library
require_once get_stylesheet_directory() . '/vendor/stripe/stripe-php/init.php';
```

Or if using a plugin, it should auto-load.

