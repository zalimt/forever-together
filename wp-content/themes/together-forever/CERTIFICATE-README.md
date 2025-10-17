# Certificate Page

The Certificate page allows visitors to create and purchase gift certificates for charitable donations.

## Template File

- **Template Name**: `certificate.php`
- **Template Name in WordPress**: "Certificate"
- **SCSS File**: `scss/certificate.scss`
- **Compiled CSS**: `css/certificate.css`

## How to Use

### 1. Create a New Page in WordPress

1. Go to **Pages → Add New** in WordPress admin
2. Give it a title like "Gift Certificate" or "Certificate"
3. In the **Page Attributes** panel on the right, select **Template: Certificate**
4. Publish the page

### 2. Add Certificate Images

Place your certificate design images in:
```
/images/certificates/
```

See `/images/certificates/README.md` for naming conventions and specifications.

### 3. Icons

The page uses four icons for the step-by-step process:
- `images/certificate-icons/document.svg` - Choose certificate design
- `images/certificate-icons/gift-box.svg` - Write benefactor's name
- `images/certificate-icons/email.svg` - Include e-mail address
- `images/certificate-icons/valid.svg` - Enter amount and payment

## Features

### Certificate Slider

- Navigate through different certificate designs
- Click prev/next arrows to browse
- Automatically updates preview with beneficiary name and amount

### Certificate Form

The form includes:
- **Beneficiary Name*** (required) - Who the certificate is for
- **From** (optional) - Additional "from" text
- **Giver Name*** (required) - Who is giving the certificate
- **Recipient's Email*** (required) - Where to send the certificate
- **Amount Selection** - Preset amounts (25€, 50€, 100€, 250€, 500€, 1000€) or custom amount

### Form Validation

- All required fields are validated
- Email format is checked
- Visual error indicators appear for invalid fields

## Styling

The page uses the theme's color scheme:
- **Primary Color**: `--tf-purple` (#5C2483)
- **Secondary Color**: `--tf-pink` (#951B81)
- **Gradient**: `--tf-purple-gradient` (purple to pink)

### Key Style Features

- Gradient title and slogan text
- Hover effects on step cards
- Smooth slider transitions
- Responsive design for all screen sizes
- Custom radio button styling with gradient
- Form validation states

## Customization

### Changing Certificate Designs

Edit `certificate.php` and modify the `.certificate-blank` divs in the slider section. Each certificate should have:

```html
<div class="certificate-blank" data-certname="Design-Name">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/certificates/Design-Name.jpg" alt="Design Name">
    <div class="certificate-beneficiary [color-class]">Beneficiary Name</div>
    <div class="certificate-from-who [color-class]">From Giver</div>
    <div class="certificate-price--block [position-class] [color-class]">
        <div class="certificate-price--wrap">
            <div class="certificate-price">25</div>
            <div class="certificate-price--currency">€</div>
        </div>
    </div>
</div>
```

### Color Classes

Available text color classes:
- `crimson-red`
- `violet`
- `rusty-nail`
- `brick-red`
- `gold`
- `silver`
- `tundora`
- `red-ribbon`

### Price Position Classes

- Default (centered)
- `price-half-left` (30% from left)
- `price-left` (25% from left)

### Changing Amount Options

Edit the radio button section in `certificate.php` to add/remove/modify amount options.

### Payment Integration

Currently, the form shows a console.log and alert on submission. To integrate payment:

1. Add your payment processor script (Stripe, PayPal, etc.)
2. Modify the `$('#certificateForm').on('submit')` handler in the JavaScript section
3. Send form data to your payment endpoint
4. Handle the certificate generation and email sending

## Responsive Breakpoints

- **Desktop**: Full width, side-by-side layout
- **Tablet** (< 1024px): Stacked layout, slider below text
- **Mobile** (< 768px): Optimized spacing and font sizes
- **Small Mobile** (< 480px): Compact layout, full-width buttons

## Development

To modify the styles:

1. Edit `scss/certificate.scss`
2. Run `npm run build` to compile
3. The compiled CSS will be in `css/certificate.css`

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Dependencies

- jQuery 3.6.0 (loaded in the template)
- Theme's main stylesheet
- Font: Avenir Next Cyr (from theme)

