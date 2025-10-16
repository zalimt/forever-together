# Single Kids Post Template

## Overview
This template displays individual Kids post pages with a beautiful, engaging layout that encourages donations and showcases each child's story.

## Template File
- **File**: `single-kids.php`
- **Styles**: `scss/single-kids.scss` → compiled to `css/single-kids.css`

## Features

### 1. **Hero Section**
- Full-width header with the child's photo as background
- Kid's name (from ACF field `kids_card_name`)
- Kid's age
- Status badge (In Need of Help, Awaiting Treatment, We Helped)
- Back button to return to previous page

### 2. **Progress Section**
- Large elephant progress indicator showing fundraising progress
- Collected amount vs Required amount in prominent boxes
- Visual progress bar
- Remaining amount calculation
- Large "Donate Now" call-to-action button

### 3. **Main Content**
- **Diagnosis Section**: Highlighted box displaying the child's diagnosis
- **Story Section**: Full biography/story from post content editor
- **Call-to-Action Card**: Prominent donation prompt with gradient background

### 4. **Sidebar (Related Kids)**
- Shows 3 random kids who are "In Need of Help"
- Each related kid card shows:
  - Photo
  - Name
  - Age
  - Progress bar with funding percentage
  - "Learn More" button
- General donation call-to-action for those who want to help multiple children

## How It Works

### Automatic Template Selection
WordPress automatically uses `single-kids.php` when displaying any Kids post. No configuration needed!

### Data Flow
1. **Kid's Name**: Uses ACF field `kids_card_name` (falls back to post title if empty)
2. **Kid's Photo**: Uses ACF field `kid_card_image` (falls back to featured image if empty)
3. **Status**: Determined by the Kid Category taxonomy
4. **Biography**: Uses the post content from WordPress editor
5. **All other fields**: Pulled from ACF custom fields

### Status Badge Colors
- **In Need of Help**: Red badge
- **Awaiting Treatment**: Yellow badge
- **We Helped**: Green badge

## Layout Structure

```
┌─────────────────────────────────────────┐
│         Hero Section (with image)       │
│     Kid's Name, Age, Status Badge       │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│        Progress Section (centered)      │
│    Elephant Graphic, Amounts, Progress  │
│           Donate Button                 │
└─────────────────────────────────────────┘

┌───────────────────────────┬─────────────┐
│  Main Content             │  Sidebar    │
│  - Diagnosis Box          │  - Related  │
│  - Kid's Story           │    Kids     │
│  - CTA Card              │  - General  │
│                          │    Donation │
└───────────────────────────┴─────────────┘
```

## Customization

### Changing Layout
Edit `single-kids.php` to modify the structure and content sections.

### Changing Styles
Edit `scss/single-kids.scss` and run `bash build.sh` to recompile.

### Changing Related Kids Count
In `single-kids.php`, find this line:
```php
'posts_per_page' => 3,
```
Change `3` to any number you want.

### Changing Related Kids Criteria
Currently shows random kids with "In Need of Help" status. To change:
```php
// In single-kids.php, around line 250
'tax_query' => array(
    array(
        'taxonomy' => 'kid_category',
        'field' => 'name',
        'terms' => 'In Need of Help', // Change this
    ),
),
'orderby' => 'rand' // Change to 'date' for newest first
```

## Responsive Design

The template is fully responsive:
- **Desktop (>1024px)**: Two-column layout with sidebar
- **Tablet (768-1024px)**: Single column, full-width content
- **Mobile (<768px)**: Optimized for small screens with adjusted font sizes and spacing

## Dependencies

### Required ACF Fields (on Kids post type)
- `kids_card_name` (textarea)
- `kid_card_image` (image)
- `kid_age` (textarea)
- `kid_diagnosis` (textarea)
- `collected_amount` (number)
- `required_amount` (number)
- `donate_btn_link` (textarea)

### Required Taxonomies
- `kid_category` with these terms:
  - "In Need of Help"
  - "Awaiting Treatment"
  - "We Helped"

### Required Images
Elephant progress SVGs (0-11):
- `/images/elephant-progress-bar-0.svg` through `/images/elephant-progress-bar-11.svg`

## Enqueuing Styles

The styles are automatically enqueued only on single Kids posts via `functions.php`:
```php
if (is_singular('kids')) {
    wp_enqueue_style('together-forever-single-kids', ...);
}
```

This ensures the CSS only loads when needed, improving site performance.

## Building/Compiling

To compile SCSS to CSS after making changes:
```bash
cd /path/to/theme
bash build.sh
```

Or compile just the single-kids styles:
```bash
sass scss/single-kids.scss css/single-kids.css --style=compressed --source-map
```

## Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## SEO Benefits
- Individual URLs for each child
- Proper heading structure (H1, H2, H3)
- Meta description from post excerpt
- Featured images for social sharing
- Semantic HTML5 markup

## Accessibility
- Semantic HTML elements
- Proper heading hierarchy
- Alt text for images
- Color contrast ratios meet WCAG standards
- Keyboard navigation support

## Performance Optimizations
- CSS only loads on Kids post pages
- Images use responsive sizing
- Minimal JavaScript (only for back button)
- Compressed CSS output
- CSS source maps for debugging

## Future Enhancements Ideas
- Share buttons for social media
- Print-friendly version
- Photo gallery for multiple images
- Timeline of treatment progress
- Donor thank you section
- Comments/messages of support
- Progress history chart
- Video embed support
- PDF download of kid's story

## Troubleshooting

### Styles not showing?
1. Make sure you ran `bash build.sh`
2. Check that `css/single-kids.css` exists
3. Clear browser cache and WordPress cache
4. Verify `functions.php` has the enqueue code

### Related kids not showing?
1. Make sure you have other Kids posts published
2. Verify they have the "In Need of Help" category assigned
3. Check that the category name matches exactly

### Progress bar not displaying correctly?
1. Verify `collected_amount` and `required_amount` fields are filled
2. Check that elephant SVG files exist in `/images/` folder
3. Make sure values are numbers, not text

### Back button not working?
1. This uses browser history - it may not work if the page was accessed directly
2. Alternative: Change to a hardcoded link in `single-kids.php`

## Related Files
- `single-kids.php` - Template file
- `scss/single-kids.scss` - Styles source
- `css/single-kids.css` - Compiled styles
- `functions.php` - Enqueue function
- `build.sh` - Build script

## Need Help?
Check the WordPress Codex for template hierarchy:
https://developer.wordpress.org/themes/basics/template-hierarchy/

