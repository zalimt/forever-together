# Certificate Page Installation Guide

This guide will help you set up and use the new Certificate page on your Together Forever website.

## ✅ What Has Been Created

### 1. Template Files
- ✅ `certificate.php` - Main WordPress template file
- ✅ `scss/certificate.scss` - Stylesheet source file  
- ✅ `css/certificate.css` - Compiled stylesheet (auto-generated)
- ✅ `css/certificate.css.map` - Source map (auto-generated)

### 2. Image Assets
- ✅ `images/certificate-icons/document.svg` - Icon for "Choose design" step
- ✅ `images/certificate-icons/gift-box.svg` - Icon for "Write name" step
- ✅ `images/certificate-icons/email.svg` - Icon for "Email address" step
- ✅ `images/certificate-icons/valid.svg` - Icon for "Payment" step
- ✅ `images/certificates/placeholder.svg` - Placeholder certificate design
- ✅ `images/certificates/*.jpg` - Sample certificate placeholders

### 3. Documentation
- ✅ `CERTIFICATE-README.md` - Main documentation
- ✅ `CERTIFICATE-INSTALLATION-GUIDE.md` - This file
- ✅ `images/certificates/README.md` - Certificate image guide

## 🚀 Quick Start (5 Steps)

### Step 1: Verify Files
All files should already be in place. You can verify by checking:
```
wp-content/themes/together-forever/
├── certificate.php ✓
├── scss/certificate.scss ✓
├── css/certificate.css ✓
├── images/
│   ├── certificate-icons/ ✓
│   │   ├── document.svg
│   │   ├── gift-box.svg
│   │   ├── email.svg
│   │   └── valid.svg
│   └── certificates/ ✓
│       ├── placeholder.svg
│       └── [your certificate images]
```

### Step 2: Create the Page in WordPress

1. Log in to WordPress Admin
2. Go to **Pages → Add New**
3. Page Title: "Gift Certificate" (or your preferred title)
4. In **Page Attributes** (right sidebar):
   - **Template**: Select "Certificate"
5. Click **Publish**

### Step 3: Add Your Certificate Images (Optional)

Replace the placeholder images with your actual certificate designs:

1. Prepare your certificate images:
   - Format: JPG or PNG
   - Recommended size: 800x600px (4:3 ratio)
   - File size: Under 500KB each

2. Upload to: `images/certificates/`
   
3. Use these naming conventions:
   - `Easter-01-eng.jpg`
   - `Birthday-01-eng.jpg`
   - `Christmas-01-eng.jpg`
   - `Simple-01-eng.jpg`
   - etc.

4. See `images/certificates/README.md` for complete list

### Step 4: Test the Page

1. Visit your new certificate page
2. Test the slider navigation (prev/next arrows)
3. Try filling out the form
4. Check responsive design on mobile

### Step 5: Configure Payment (Important!)

⚠️ **The form currently shows an alert on submission. You need to integrate payment processing.**

To add payment:

1. Choose a payment provider (Stripe, PayPal, etc.)
2. Edit `certificate.php`
3. Find the form submission handler (around line 250)
4. Replace the console.log and alert with your payment integration
5. Add backend processing for certificate generation and email delivery

## 🎨 Design Features

The certificate page uses your theme's design system:

### Colors
- **Primary Purple**: `#5C2483`
- **Pink**: `#951B81`  
- **Gradient**: Purple to Pink
- **Text**: `#333`

### Typography
- **Font**: Avenir Next Cyr (from your theme)
- **Title**: 42px (gradient text)
- **Body**: 16px

### Components
- Gradient decorative lines
- Hover effects on step cards
- Animated slider with arrows
- Custom radio buttons with gradient
- Responsive form layout
- Form validation with error states

## 📱 Responsive Design

The page is fully responsive:
- **Desktop** (1400px+): Side-by-side layout
- **Tablet** (768px-1024px): Stacked layout
- **Mobile** (<768px): Optimized spacing
- **Small Mobile** (<480px): Compact layout

## 🔧 Customization

### Change Text Content

Edit `certificate.php` and modify the text in the `.certificate-text` section.

### Add/Remove Amount Options

In `certificate.php`, find the radio buttons section and add/remove options:

```php
<label class="radio-custom">
    <input type="radio" name="amount" value="YOUR_AMOUNT">
    <span class="radio-label">YOUR_AMOUNT €</span>
</label>
```

### Modify Styles

1. Edit `scss/certificate.scss`
2. Run `npm run build` in the theme directory
3. Your changes will compile to `css/certificate.css`

### Add More Certificate Designs

1. Add image to `images/certificates/`
2. Edit `certificate.php`
3. Add a new `.certificate-blank` div in the slider
4. Follow the existing pattern

## 🐛 Troubleshooting

### Page Shows "Template Not Found"
- Make sure `certificate.php` is in the theme root directory
- Try re-saving the page in WordPress admin

### Images Not Showing
- Check file paths in `certificate.php`
- Verify images exist in `images/certificates/` and `images/certificate-icons/`
- Check file permissions

### Styles Not Applied
- Run `npm run build` in theme directory
- Clear browser cache
- Check if `certificate.css` exists in `css/` folder
- Verify `main.scss` imports `certificate.scss`

### Slider Not Working
- Check browser console for JavaScript errors
- Verify jQuery is loaded (it's included in the template)
- Make sure multiple certificate images exist

## 📋 Next Steps

1. ✅ Create the page in WordPress
2. ⚠️ **Upload your real certificate designs**
3. ⚠️ **Integrate payment processing**
4. ⚠️ **Set up certificate email delivery**
5. ⚠️ **Test the complete flow**
6. ✅ Add page to your navigation menu
7. ✅ Promote on your website

## 💡 Tips

- Test the form thoroughly before going live
- Use high-quality certificate images
- Consider adding a preview feature before payment
- Set up email templates for certificate delivery
- Add tracking/analytics to monitor usage
- Consider adding a gallery of all certificate designs

## 🔗 Related Files

- See `CERTIFICATE-README.md` for detailed documentation
- See `images/certificates/README.md` for image specifications
- Main theme styles: `scss/main.scss`
- Root variables: `scss/root.scss`

## 📞 Support

If you encounter issues:
1. Check this guide first
2. Review `CERTIFICATE-README.md`
3. Check browser console for errors
4. Verify all files are in place
5. Test on different browsers

---

**Created**: October 2025  
**Version**: 1.0.0  
**Theme**: Together Forever

