# 🎁 Certificate Payment & Redemption System - Complete Setup Guide

This guide will walk you through setting up the complete certificate purchase, payment, and activation system for your Together Forever website.

---

## 📋 Table of Contents

1. [System Overview](#system-overview)
2. [What Was Created](#what-was-created)
3. [Quick Start (Testing Mode)](#quick-start-testing-mode)
4. [Production Setup (Stripe Integration)](#production-setup-stripe-integration)
5. [Database Structure](#database-structure)
6. [Admin Features](#admin-features)
7. [How It Works](#how-it-works)
8. [Testing the System](#testing-the-system)
9. [Troubleshooting](#troubleshooting)

---

## 🎯 System Overview

The certificate system allows customers to:
1. Purchase gift certificates with fixed or custom amounts
2. Pay via Stripe (or demo mode for testing)
3. Receive a unique certificate code via email
4. Activate the certificate using the code
5. Code becomes inactive after activation (one-time use)

---

## 📦 What Was Created

### Core Files

#### 1. Database & Backend (`inc/certificate-system.php`)
- Creates custom database table: `wp_tf_certificates`
- Generates unique certificate codes (format: `TF-XXXX-XXXX-XXXX`)
- Handles certificate activation/deactivation
- Sends confirmation emails
- Admin panel to view all certificates

#### 2. Stripe Integration (`inc/stripe-integration.php`)
- Stripe Checkout integration
- Webhook handler for payment confirmation
- Settings page for API keys
- Demo mode for testing without Stripe

#### 3. Page Templates
- **certificate.php** - Purchase page with payment form
- **activate-certificate.php** - Activation page for redeeming codes

#### 4. Stylesheets
- **certificate.scss** - Certificate purchase page styles
- **activate-certificate.scss** - Activation page styles

#### 5. Database Table
- Automatically created on theme activation
- Stores: codes, amounts, emails, payment info, status, timestamps

---

## 🚀 Quick Start (Testing Mode)

### Step 1: Verify Installation

All files should be in place. No additional installation needed!

### Step 2: Create WordPress Pages

#### Create Certificate Purchase Page
1. Go to **Pages → Add New**
2. Title: "Gift Certificate" or "Certificate"
3. Template: Select **"Certificate"**
4. Publish

#### Create Activation Page
1. Go to **Pages → Add New** 
2. Title: "Activate Certificate"
3. Template: Select **"Activate Certificate"**
4. URL slug: `activate-certificate` (important!)
5. Publish

### Step 3: Test in Demo Mode

The system works in **DEMO MODE** by default (no Stripe setup needed).

1. Visit your Certificate page
2. Fill out the form:
   - Beneficiary: John Doe
   - Giver: Jane Smith
   - Email: your@email.com
   - Amount: Select 50€
3. Click "Proceed to Payment"
4. You'll see a demo mode message
5. Click "Yes" to simulate payment
6. You'll get a certificate code (e.g., `TF-A1B2-C3D4-E5F6`)
7. An email will be sent to the specified address

### Step 4: Test Activation

1. Visit your Activate Certificate page
2. Enter the code you received
3. Enter your email (optional)
4. Click "Activate Certificate"
5. Code should activate successfully
6. Try using the same code again - it should show "already activated"

---

## 💳 Production Setup (Stripe Integration)

### Prerequisites

- Stripe account ([Create one here](https://dashboard.stripe.com/register))
- Composer installed on your server (for Stripe PHP SDK)

### Step 1: Install Stripe PHP SDK

```bash
cd /path/to/wp-content/themes/together-forever
composer require stripe/stripe-php
```

### Step 2: Configure Stripe in WordPress

1. Go to **Settings → Stripe Settings** in WordPress admin
2. Get your API keys from [Stripe Dashboard](https://dashboard.stripe.com/apikeys)
3. Enter:
   - **Publishable Key** (starts with `pk_`)
   - **Secret Key** (starts with `sk_`)

### Step 3: Set Up Stripe Webhook

1. Go to [Stripe Webhooks](https://dashboard.stripe.com/webhooks)
2. Click "Add endpoint"
3. Enter webhook URL (shown in Settings):
   ```
   https://yoursite.com/wp-json/tf/v1/stripe-webhook
   ```
4. Select event: **`checkout.session.completed`**
5. Copy the **Signing Secret** (starts with `whsec_`)
6. Paste it in **Settings → Stripe Settings → Webhook Secret**
7. Save settings

### Step 4: Update Stripe Integration Code

The code is ready to work with Stripe but commented out for safety. To enable:

1. Open `inc/stripe-integration.php`
2. Find the `tf_create_stripe_session()` function
3. Uncomment the Stripe code (lines with `\Stripe\Stripe::setApiKey...`)
4. Comment out or remove the demo mode code

---

## 🗄️ Database Structure

### Table: `wp_tf_certificates`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `certificate_code` | varchar(20) | Unique code (TF-XXXX-XXXX-XXXX) |
| `beneficiary_name` | varchar(255) | Who the certificate is for |
| `beneficiary_from` | varchar(255) | Optional "from" message |
| `giver_name` | varchar(255) | Who purchased it |
| `recipient_email` | varchar(255) | Where to send the certificate |
| `amount` | decimal(10,2) | Amount in euros |
| `payment_intent_id` | varchar(255) | Stripe payment ID |
| `stripe_session_id` | varchar(255) | Stripe session ID |
| `status` | varchar(20) | active or inactive |
| `created_at` | datetime | Purchase timestamp |
| `activated_at` | datetime | Activation timestamp |
| `activated_by_email` | varchar(255) | Who activated it |

---

## 🎛️ Admin Features

### View All Certificates

Go to **Certificates** in WordPress admin menu to see:
- All purchased certificates
- Filter by status (Active/Inactive)
- View details: code, amounts, emails, dates
- Export data (via standard WP exports)

### Stripe Settings

Go to **Settings → Stripe Settings** to:
- Configure API keys
- Set webhook secret
- View webhook URL
- See setup instructions

---

## 🔄 How It Works

### Purchase Flow

```
1. Customer fills out form on Certificate page
   ↓
2. Clicks "Proceed to Payment"
   ↓
3. System creates Stripe checkout session
   ↓
4. Customer redirects to Stripe payment page
   ↓
5. Customer completes payment
   ↓
6. Stripe sends webhook to your site
   ↓
7. System generates unique code
   ↓
8. Code saved to database (status: active)
   ↓
9. Email sent to customer with code
```

### Activation Flow

```
1. Customer receives email with code
   ↓
2. Visits Activate Certificate page
   ↓
3. Enters code (auto-formatted)
   ↓
4. System validates code
   ↓
5. If valid & active: Mark as inactive
   ↓
6. Show certificate details
   ↓
7. Code cannot be reused
```

---

## 🧪 Testing the System

### Test Scenarios

#### ✅ Valid Purchase
1. Fill form with valid data
2. Should create certificate
3. Should send email
4. Should save to database

#### ✅ Valid Activation
1. Use code from purchase
2. Should activate successfully
3. Should update database
4. Should show certificate details

#### ❌ Invalid Code
1. Enter non-existent code
2. Should show "Code not found"

#### ❌ Already Activated
1. Use same code twice
2. Should show "Already activated"

#### ✅ Email Delivery
1. Check spam folder
2. Verify code in email
3. Test activation link

### Stripe Test Cards

When testing with Stripe:

**Successful Payment:**
- Card: `4242 4242 4242 4242`
- Expiry: Any future date
- CVC: Any 3 digits

**Failed Payment:**
- Card: `4000 0000 0000 0002`

---

## 🐛 Troubleshooting

### Certificate Page Issues

**Page shows 404 or "Template Not Found"**
- Make sure `certificate.php` is in theme root
- Try re-saving the page in WordPress admin

**Form doesn't submit**
- Check browser console for errors
- Verify jQuery is loading
- Check nonce validation

**Email not sending**
- Test WordPress email with WP Mail SMTP plugin
- Check spam folder
- Verify PHP mail() is working on server

### Activation Page Issues

**Code not validating**
- Verify code format: `TF-XXXX-XXXX-XXXX`
- Check database for code existence
- Try uppercase/lowercase

**AJAX errors**
- Check admin-ajax.php is accessible
- Verify nonce in browser console
- Check PHP error logs

### Stripe Integration Issues

**"Stripe is not configured" error**
- Go to Settings → Stripe Settings
- Verify API keys are saved
- Check keys don't have extra spaces

**Payments not creating certificates**
- Check webhook is configured
- Verify webhook secret is correct
- Check webhook logs in Stripe dashboard
- View PHP error logs

**Webhook failing**
- Ensure URL is publicly accessible
- Check SSL certificate is valid
- Verify event type is `checkout.session.completed`

### Database Issues

**Table not created**
- Deactivate and reactivate theme
- Or run: `tf_create_certificates_table()` in PHP

**Codes not saving**
- Check database write permissions
- View PHP error logs
- Verify table exists in phpMyAdmin

---

## 💰 Payment Amounts

### Default Amounts

- 25€
- 50€
- 100€
- 250€
- 500€
- 1000€
- Custom amount

### Changing Amounts

Edit `certificate.php` and modify the radio buttons:

```php
<label class="radio-custom">
    <input type="radio" name="amount" value="YOUR_AMOUNT">
    <span class="radio-label">YOUR_AMOUNT €</span>
</label>
```

---

## 📧 Email Customization

### Email Template

Edit the `tf_send_certificate_email()` function in `inc/certificate-system.php`.

### Change Email Content
- Modify HTML in the `$message` variable
- Change colors, text, or layout
- Add your logo

### Change "From" Email
Update the headers:
```php
'From: Your Name <email@yourdomain.com>'
```

---

## 🔒 Security Features

✅ **Nonce validation** on all AJAX requests  
✅ **Sanitization** of all user inputs  
✅ **Unique code generation** prevents duplicates  
✅ **One-time use** codes can't be reused  
✅ **Stripe webhook signature** verification  
✅ **SQL injection** prevention with prepared statements  
✅ **XSS protection** with escaping functions  

---

## 📊 Reports & Analytics

### View Statistics

In **Certificates** admin page, you can see:
- Total certificates sold
- Active vs inactive codes
- Revenue (total amounts)
- Popular amount selections

### Export Data

1. Go to **Tools → Export**
2. Select "All content" or custom export
3. Certificate data will be included

---

## 🎨 Customization

### Change Colors

Edit `scss/certificate.scss` and `scss/activate-certificate.scss`:

```scss
// Find and modify:
--tf-purple: #5C2483;
--tf-pink: #951B81;
```

### Change Layout

Edit the template files:
- `certificate.php` - Purchase form
- `activate-certificate.php` - Activation form

### Add Fields

1. Add field to `certificate.php` form
2. Update JavaScript to capture value
3. Modify database save function
4. Update email template

---

## 🚀 Going Live Checklist

Before launching to production:

- [ ] Install Stripe PHP SDK via Composer
- [ ] Configure Stripe API keys in Settings
- [ ] Set up Stripe webhook
- [ ] Test full purchase flow with test card
- [ ] Test email delivery
- [ ] Test code activation
- [ ] Verify codes become inactive after use
- [ ] Check admin panel shows purchases
- [ ] Test on mobile devices
- [ ] Set up email monitoring
- [ ] Configure backup system for database
- [ ] Test error handling
- [ ] Review email spam score
- [ ] Set up analytics tracking

---

## 📞 Support & Resources

### Documentation
- **Stripe Checkout**: https://stripe.com/docs/payments/checkout
- **Stripe Webhooks**: https://stripe.com/docs/webhooks
- **WordPress AJAX**: https://codex.wordpress.org/AJAX_in_Plugins

### Testing Tools
- **Stripe Test Cards**: https://stripe.com/docs/testing
- **Email Tester**: https://www.mail-tester.com/
- **Webhook Tester**: https://webhook.site/

---

## 🎉 Success!

Your certificate system is now ready to use! Test thoroughly in demo mode before enabling Stripe for production.

**Questions?** Check the troubleshooting section or review the code comments in the PHP files.

---

**Version**: 1.0.0  
**Last Updated**: October 2025  
**Theme**: Together Forever

