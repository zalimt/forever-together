# Single Post Template

This document explains the custom single post template for the Together Forever theme.

## Files Created

1. **single.php** - Custom template for single blog posts
2. **scss/single.scss** - Styles for the single post template
3. **css/main.css** - Updated compiled CSS (includes single post styles)

## Features

### 🎨 Design Elements

- **Hero Section with Featured Image**
  - Full-width featured image background
  - Purple/pink gradient overlay
  - Large, readable white title
  - Back button and category tags
  - Responsive height adjustment

- **Main Content Area**
  - Clean white card design
  - Generous padding for readability
  - Purple accent colors
  - Optimized typography (18px base font size)
  - Beautiful content formatting:
    - Styled headings (H1-H6)
    - Formatted lists (UL, OL)
    - Code blocks with syntax highlighting
    - Blockquotes with purple accent border
    - Linked content with hover effects
    - Responsive images

- **Sidebar - Related Posts**
  - Sticky positioning (stays visible when scrolling)
  - Shows 3 related posts from same category
  - Each related post shows:
    - Featured image (if available)
    - Category tags
    - Post title
    - Excerpt (15 words)
    - "Read Article" button
  - Clean card design with hover effects

### 🎯 Layout Structure

```
┌─────────────────────────────────────────┐
│     Hero Section (Featured Image)       │
│   • Title                               │
│   • Back Button + Categories            │
└─────────────────────────────────────────┘
┌───────────────────┬─────────────────────┐
│   Main Content    │   Related Posts     │
│                   │   (Sticky Sidebar)  │
│   • Post Date     │                     │
│   • Content       │   • Post 1          │
│   • Tags          │   • Post 2          │
│                   │   • Post 3          │
└───────────────────┴─────────────────────┘
```

## Color Scheme

All colors match the Together Forever brand:

- **Primary Purple**: `#5C2483`
- **Secondary Pink**: `#951B81`
- **Text Gray**: `#333`
- **Background**: `#f8f6ff` (light purple)
- **White Cards**: `#ffffff`
- **Category Tags**: `#f0e6f5` (light purple background)

## Typography

- **Font Family**: Avenir Next Cyr (consistent with theme)
- **Main Content**: 18px (optimized for reading)
- **Headings**: Bold, purple color
- **Line Height**: 1.8 (comfortable reading)

## Responsive Breakpoints

The template is fully responsive:

- **Desktop** (> 992px): 2-column layout (content + sidebar)
- **Tablet** (768px - 992px): Single column, sidebar below content
- **Mobile** (< 768px): Optimized font sizes and spacing
- **Small Mobile** (< 480px): Further optimization for small screens

## Usage

### Automatic Application

This template automatically applies to:
- All single blog posts
- Posts from "News" category
- Posts from "Events" category
- Any other standard WordPress post

### Featured Images

For the best results:
1. Add a featured image to your post
2. Recommended size: **1920x600px** or larger
3. The image will be used as the hero background
4. If no featured image is set, the title will display without the hero section

### Related Posts

The sidebar automatically shows:
- Posts from the same category as the current post
- Maximum of 3 related posts
- Randomly ordered for variety
- Excludes the current post

## Content Formatting Examples

### Headings
Use standard WordPress headings - they'll be styled automatically in purple.

### Blockquotes
Create quotes using the WordPress editor - they'll have a purple left border.

### Code
Inline code and code blocks are styled with purple backgrounds.

### Images
Add images through the WordPress editor - they'll be responsive and rounded.

### Lists
Both numbered and bulleted lists are properly styled with spacing.

## Customization

### Change Number of Related Posts

Edit `single.php` line 85:
```php
'posts_per_page' => 3,  // Change this number
```

### Change Hero Image Height

Edit `scss/single.scss` line 15:
```scss
min-height: 500px;  // Change this value
```

Then recompile:
```bash
cd /Users/zalimtsorionov/Local\ Sites/forever-together/app/public/wp-content/themes/together-forever
npm run build
```

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Tips for Best Results

1. **Always add featured images** to posts for the hero section
2. **Use headings** to structure content (improves readability)
3. **Break up text** with images, blockquotes, and lists
4. **Assign categories** to posts for better related post suggestions
5. **Write excerpts** for better appearance in related posts section

## Troubleshooting

### No related posts showing?
- Make sure posts have categories assigned
- Check that there are other posts in the same category
- Ensure posts are published (not drafts)

### Hero section not showing?
- Check if the post has a featured image
- Featured image should be uploaded through WordPress media library

### Styles not applying?
- Clear WordPress cache
- Regenerate CSS: `npm run build`
- Hard refresh browser (Ctrl/Cmd + Shift + R)

