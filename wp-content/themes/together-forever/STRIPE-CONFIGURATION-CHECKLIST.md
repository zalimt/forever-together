# ✅ Stripe Configuration Checklist

## Step-by-Step Setup for Test Mode

---

### 1️⃣ Get Stripe Test Keys

- [ ] Go to: https://dashboard.stripe.com/test/apikeys
- [ ] Copy **Publishable key** (starts with `pk_test_`)
- [ ] Copy **Secret key** (starts with `sk_test_`)
- [ ] Keep these handy for Step 3

**Your keys will look like:**
```
Publishable key: pk_test_51ABC...xyz
Secret key: sk_test_51ABC...xyz
```

---

### 2️⃣ Create Webhook

- [ ] Go to: https://dashboard.stripe.com/test/webhooks
- [ ] Click **"Add endpoint"**
- [ ] Enter your webhook URL:
  ```
  http://forever-together.local/wp-json/tf/v1/stripe-webhook
  ```
  *(Replace with your actual Local domain)*

- [ ] Click **"Select events"**
- [ ] Search and select: `checkout.session.completed`
- [ ] Click **"Add events"** → **"Add endpoint"**
- [ ] Click on the newly created webhook
- [ ] Click **"Reveal"** next to "Signing secret"
- [ ] Copy the **Webhook Signing Secret** (starts with `whsec_`)

**Webhook secret will look like:**
```
whsec_ABC123xyz...
```

---

### 3️⃣ Configure WordPress

- [ ] Log in to WordPress admin
- [ ] Go to **Settings → Stripe Settings**
  - If you don't see this menu, refresh the page or check that the system files are loaded
- [ ] Enter your keys:
  - **Publishable Key**: `pk_test_...`
  - **Secret Key**: `sk_test_...`
  - **Webhook Secret**: `whsec_...`
- [ ] Click **"Save Settings"**

---

### 4️⃣ Install Stripe PHP SDK

**Method 1: Local Shell (Recommended)**
```bash
# Open Local by Flywheel
# Right-click site → "Open Site Shell"
cd app/public/wp-content/themes/together-forever
composer require stripe/stripe-php
```

**Method 2: Manual Install**
- See `STRIPE-MANUAL-INSTALL.md` for instructions

**Method 3: Skip for Now**
- The system will work in demo mode
- Install when ready for real testing

---

### 5️⃣ Enable Live Stripe Code

- [ ] Open: `inc/stripe-integration.php`
- [ ] Find function: `tf_create_stripe_session()`
- [ ] **Uncomment** lines 30-53 (the Stripe code):

**Change from:**
```php
// \Stripe\Stripe::setApiKey($stripe_secret_key);
// 
// $session = \Stripe\Checkout\Session::create([
//     ...
// ]);
```

**To:**
```php
\Stripe\Stripe::setApiKey($stripe_secret_key);

$session = \Stripe\Checkout\Session::create([
    ...
]);
```

- [ ] **Comment out** or delete lines 60-69 (demo mode)
- [ ] Save the file

---

### 6️⃣ Test the System

#### Test Purchase
- [ ] Visit your Certificate page
- [ ] Fill out the form:
  - Beneficiary: Test User
  - Giver: Your Name
  - Email: youremail@example.com
  - Amount: 50€
- [ ] Click "Proceed to Payment"
- [ ] Should redirect to Stripe checkout page
- [ ] Use test card: `4242 4242 4242 4242`
  - Expiry: Any future date (e.g., 12/25)
  - CVC: Any 3 digits (e.g., 123)
  - ZIP: Any 5 digits (e.g., 12345)
- [ ] Complete payment
- [ ] Should redirect back to your site
- [ ] Check email for certificate code

#### Test Webhook
- [ ] Go to: https://dashboard.stripe.com/test/webhooks
- [ ] Click on your webhook
- [ ] Check "Events" tab for successful deliveries
- [ ] Should see `checkout.session.completed` event

#### Test Activation
- [ ] Visit: /activate-certificate
- [ ] Enter the code from email
- [ ] Should activate successfully
- [ ] Try using same code again
- [ ] Should show "already activated" error

#### Test Admin Panel
- [ ] Go to: WordPress Admin → **Certificates**
- [ ] Should see the test purchase
- [ ] Verify all details are correct

---

## 🎯 What to Check

### ✅ Success Indicators

**After purchase:**
- ✅ Redirected to Stripe checkout
- ✅ Payment processed successfully  
- ✅ Email received with code
- ✅ Code saved to database
- ✅ Visible in admin panel

**Webhook working:**
- ✅ Green checkmarks in Stripe webhook logs
- ✅ Certificate created after payment
- ✅ Email sent automatically

**Activation working:**
- ✅ Valid codes activate
- ✅ Invalid codes show error
- ✅ Used codes cannot be reused

---

## ❌ Troubleshooting

### Issue: "Stripe is not configured"
**Fix:** Check that API keys are saved in Settings → Stripe Settings

### Issue: No redirect to Stripe
**Fix:** 
- Check browser console for errors
- Verify publishable key is correct
- Check that Stripe PHP SDK is installed

### Issue: Payment works but no certificate created
**Fix:**
- Check webhook is configured
- Verify webhook secret is correct
- Check webhook logs in Stripe dashboard
- Look for errors in PHP error logs

### Issue: Webhook failing
**Fix:**
- Ensure URL is accessible (test in browser)
- Check webhook secret matches
- Verify event type is `checkout.session.completed`
- Try re-creating the webhook

### Issue: Email not sending
**Fix:**
- Install WP Mail SMTP plugin
- Check spam folder
- Verify email address is correct

---

## 📊 Stripe Test Cards

Use these for testing:

**Successful payment:**
```
Card: 4242 4242 4242 4242
Expiry: Any future date
CVC: Any 3 digits
```

**Payment requires authentication (3D Secure):**
```
Card: 4000 0025 0000 3155
```

**Payment declined:**
```
Card: 4000 0000 0000 0002
```

**Insufficient funds:**
```
Card: 4000 0000 0000 9995
```

More test cards: https://stripe.com/docs/testing

---

## 🎉 When Everything Works

You should be able to:
1. Purchase certificates with real Stripe checkout
2. Receive codes via email automatically
3. Activate codes on activation page
4. View all purchases in admin panel
5. See webhook events in Stripe dashboard

---

## 🔄 Switch to Live Mode (When Ready)

When ready for real payments:

1. Get **live** API keys from: https://dashboard.stripe.com/apikeys
2. Update Settings → Stripe Settings with live keys
3. Create new webhook for **live** mode
4. Test with small real purchase
5. Monitor for issues

**Live keys start with:**
- `pk_live_...`
- `sk_live_...`

---

## 📞 Need Help?

If stuck on any step:
1. Check browser console for errors
2. Check PHP error logs
3. Review webhook logs in Stripe
4. Verify all keys are correct
5. Try in demo mode first

---

**Current Status:** System is ready for Stripe configuration!

Follow this checklist step-by-step and you'll be accepting payments in no time! 🚀

