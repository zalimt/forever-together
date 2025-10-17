# 🚀 Certificate System - Quick Start Guide

## Immediate Next Steps (5 Minutes)

### 1. Create Two WordPress Pages

**Certificate Purchase Page:**
```
Pages → Add New
Title: Gift Certificate
Template: Certificate
Publish
```

**Activation Page:**
```
Pages → Add New
Title: Activate Certificate
URL: activate-certificate
Template: Activate Certificate  
Publish
```

### 2. Test the System (Demo Mode - No Stripe Needed)

1. Visit your Certificate page
2. Fill in the form:
   - Beneficiary: John Doe
   - Giver: Your Name
   - Email: youremail@example.com
   - Amount: 50€
3. Click "Proceed to Payment"
4. Click "Yes" to simulate payment
5. You'll get a code like: `TF-A1B2-C3D4-E5F6`
6. Check your email for confirmation

3. Test Activation:
   - Visit Activate Certificate page
   - Enter the code
   - Click Activate
   - Code should activate and become inactive

---

## File Structure

```
together-forever/
├── certificate.php                    ✅ Purchase page template
├── activate-certificate.php           ✅ Activation page template
├── inc/
│   ├── certificate-system.php         ✅ Core system
│   └── stripe-integration.php         ✅ Payment handling
├── scss/
│   ├── certificate.scss               ✅ Purchase page styles
│   └── activate-certificate.scss      ✅ Activation page styles
├── css/
│   ├── certificate.css                ✅ Compiled (auto-generated)
│   └── activate-certificate.css       ✅ Compiled (auto-generated)
└── functions.php                      ✅ Updated with includes
```

---

## System Flow

### Purchase:
```
Form → Payment → Code Generated → Email Sent → Database (Active)
```

### Activation:
```
Enter Code → Validate → Mark Inactive → Show Certificate → Code Expired
```

---

## Features

✅ **Fixed amounts**: 25€, 50€, 100€, 250€, 500€, 1000€  
✅ **Custom amount** option  
✅ **Unique codes**: Format `TF-XXXX-XXXX-XXXX`  
✅ **Email delivery**: Automatic with formatted HTML  
✅ **One-time use**: Codes expire after activation  
✅ **Admin panel**: View all certificates  
✅ **Demo mode**: Test without Stripe  
✅ **Stripe ready**: Full payment integration available  

---

## Admin Features

### View Certificates
```
WordPress Admin → Certificates
```
See all purchases, filter by status, view details.

### Configure Stripe (Optional)
```
WordPress Admin → Settings → Stripe Settings
```
Add API keys and webhook secret for live payments.

---

## Payment Options

### Demo Mode (Current)
- No Stripe setup needed
- Simulates payments
- Perfect for testing
- Creates real codes & emails

### Production Mode (Requires Setup)
1. Install Stripe PHP SDK: `composer require stripe/stripe-php`
2. Add API keys in Settings → Stripe Settings
3. Configure webhook
4. Uncomment Stripe code in `stripe-integration.php`

---

## Database

**Table**: `wp_tf_certificates`  
**Created**: Automatically on theme activation  
**Location**: Your WordPress database  

Check in phpMyAdmin:
```sql
SELECT * FROM wp_tf_certificates;
```

---

## Testing Checklist

- [ ] Create both WordPress pages
- [ ] Test certificate purchase (demo mode)
- [ ] Check email received
- [ ] Test code activation
- [ ] Try activating same code twice (should fail)
- [ ] Check admin Certificates page
- [ ] Test invalid code
- [ ] Test on mobile

---

## Common Issues & Fixes

**"Template Not Found"**
→ Re-save the WordPress page

**Email not received**
→ Check spam folder, install WP Mail SMTP plugin

**Code not working**
→ Verify format: `TF-XXXX-XXXX-XXXX` (uppercase)

**Form not submitting**
→ Check browser console for errors

---

## Customization

### Change Colors
Edit `scss/certificate.scss`:
```scss
--tf-purple: #5C2483;  // Your color here
--tf-pink: #951B81;    // Your color here
```
Run: `npm run build`

### Change Amounts
Edit `certificate.php`, modify radio buttons.

### Change Email Template
Edit `inc/certificate-system.php`, function `tf_send_certificate_email()`.

---

## Going Live with Stripe

When ready for real payments:

1. **Install Stripe SDK**:
   ```bash
   composer require stripe/stripe-php
   ```

2. **Configure Keys**:
   - Get from https://dashboard.stripe.com/apikeys
   - Add in Settings → Stripe Settings

3. **Set Up Webhook**:
   - URL: `https://yoursite.com/wp-json/tf/v1/stripe-webhook`
   - Event: `checkout.session.completed`
   - Get signing secret

4. **Update Code**:
   - Edit `inc/stripe-integration.php`
   - Uncomment Stripe code in `tf_create_stripe_session()`

5. **Test**:
   - Use test card: `4242 4242 4242 4242`
   - Verify code created
   - Check webhook logs in Stripe

---

## Key Files to Know

**Main Logic**: `inc/certificate-system.php`
- Database functions
- Code generation
- Email sending
- Activation logic

**Payment**: `inc/stripe-integration.php`
- Stripe checkout
- Webhook handler
- Settings page

**Templates**:
- `certificate.php` - Purchase form
- `activate-certificate.php` - Activation form

---

## Support

📖 **Full Guide**: See `CERTIFICATE-SYSTEM-SETUP-GUIDE.md`  
🔍 **Database**: Check `wp_tf_certificates` table  
⚙️ **Settings**: WordPress Admin → Settings → Stripe Settings  
📊 **Admin**: WordPress Admin → Certificates  

---

## Quick Reference

### Certificate Code Format
```
TF-A1B2-C3D4-E5F6
```
- Always starts with `TF-`
- 3 groups of 4 characters
- Uppercase letters and numbers
- Dashes separate groups

### Status Values
- **active** = Can be activated
- **inactive** = Already used

### Amount Format
- Stored as decimal(10,2)
- Display as: €XX.XX
- Accept any positive number

---

**You're all set!** 🎉

Start by creating the two WordPress pages and test in demo mode.

For full Stripe integration, see the complete setup guide.

