# Fonts Directory

This directory is for storing custom font files for the Together Forever theme.

## Supported Font Formats

- `.woff2` (recommended - best compression)
- `.woff` (good browser support)
- `.ttf` (TrueType)
- `.otf` (OpenType)

## How to Use Custom Fonts

### 1. Add Font Files
Place your font files in this directory:
```
fonts/
├── your-font-regular.woff2
├── your-font-regular.woff
├── your-font-bold.woff2
├── your-font-bold.woff
└── ...
```

### 2. Define Font Face in SCSS
Add `@font-face` declarations to your `root.scss` file:

```scss
@font-face {
  font-family: 'Your Font Name';
  src: url('../fonts/your-font-regular.woff2') format('woff2'),
       url('../fonts/your-font-regular.woff') format('woff');
  font-weight: 400;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: 'Your Font Name';
  src: url('../fonts/your-font-bold.woff2') format('woff2'),
       url('../fonts/your-font-bold.woff') format('woff');
  font-weight: 700;
  font-style: normal;
  font-display: swap;
}
```

### 3. Use Font in CSS Variables
Add your font to the CSS variables in `root.scss`:

```scss
:root {
  --tf-font-family-primary: 'Your Font Name', sans-serif;
  --tf-font-family-secondary: 'Your Secondary Font', serif;
}
```

### 4. Apply Fonts
Use the variables in your styles:

```scss
body {
  font-family: var(--tf-font-family-primary);
}

h1, h2, h3 {
  font-family: var(--tf-font-family-secondary);
}
```

## Font Loading Best Practices

1. **Use `font-display: swap`** for better performance
2. **Include fallback fonts** in your font-family declarations
3. **Preload critical fonts** in your theme's `functions.php`
4. **Use WOFF2 format** for best compression and performance

## Preloading Fonts

To preload critical fonts, add this to your `functions.php`:

```php
function together_forever_preload_fonts() {
    echo '<link rel="preload" href="' . get_stylesheet_directory_uri() . '/fonts/your-font-regular.woff2" as="font" type="font/woff2" crossorigin>';
}
add_action('wp_head', 'together_forever_preload_fonts');
```

## File Organization

Organize your fonts by family and weight:
```
fonts/
├── primary-font/
│   ├── primary-regular.woff2
│   ├── primary-regular.woff
│   ├── primary-bold.woff2
│   └── primary-bold.woff
├── secondary-font/
│   ├── secondary-regular.woff2
│   └── secondary-regular.woff
└── accent-font/
    └── accent-regular.woff2
```
