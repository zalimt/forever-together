# SCSS Development Guide

This guide explains how to work with the SCSS setup in the Together Forever theme.

## 📁 File Structure

```
together-forever/
├── scss/
│   ├── root.scss          # CSS variables, base styles, utilities
│   └── main.scss          # Component styles, layout, responsive
├── css/
│   ├── root.css           # Compiled from root.scss
│   ├── main.css           # Compiled from main.scss
│   ├── root.css.map       # Source map for root.css
│   └── main.css.map       # Source map for main.css
├── package.json           # Node.js dependencies and scripts
├── build.sh              # Build script for compilation
└── .gitignore            # Excludes compiled files from Git
```

## 🚀 Getting Started

### 1. Install Dependencies

```bash
cd wp-content/themes/together-forever
npm install
```

### 2. Build CSS Files

```bash
# Option 1: Using npm script
npm run build

# Option 2: Using build script
./build.sh

# Option 3: Manual compilation
sass scss/root.scss css/root.css --style=compressed --source-map
sass scss/main.scss css/main.css --style=compressed --source-map
```

### 3. Watch for Changes (Development)

```bash
# Watch and auto-compile on changes
npm run watch

# Or use the dev command
npm run dev
```

## 📝 SCSS Files Explained

### `root.scss`
Contains:
- **CSS Custom Properties (Variables)**: Colors, typography, spacing, etc.
- **Base Styles**: Reset, normalize, typography defaults
- **Utility Classes**: Display, text alignment, colors, spacing

### `main.scss`
Contains:
- **Layout Components**: Header, navigation, footer, main content
- **Content Components**: Entry content, blog layouts, single posts
- **Form Components**: Inputs, buttons, form styling
- **Widget Components**: Sidebar widgets, widget styling
- **Responsive Design**: Mobile-first breakpoints
- **Print Styles**: Optimized for printing

## 🎨 CSS Variables

The theme uses CSS custom properties for consistent styling:

```scss
:root {
  // Colors
  --tf-primary: #007cba;
  --tf-secondary: #6c757d;
  
  // Typography
  --tf-font-family-primary: 'Inter', sans-serif;
  --tf-font-size-base: 1rem;
  
  // Spacing
  --tf-spacing-4: 1rem;
  --tf-spacing-6: 1.5rem;
  
  // And many more...
}
```

## 🔧 Available NPM Scripts

| Script | Description |
|--------|-------------|
| `npm run build` | Compile SCSS to CSS (production) |
| `npm run build:css` | Same as build |
| `npm run watch` | Watch SCSS files and auto-compile |
| `npm run dev` | Same as watch |
| `npm run clean` | Remove compiled CSS files |
| `npm run install-deps` | Install dependencies |

## 🎯 Development Workflow

### 1. Make Changes
Edit SCSS files in the `scss/` directory:
- `root.scss` for variables and base styles
- `main.scss` for component styles

### 2. Compile
```bash
npm run watch  # For development (auto-compile)
# or
npm run build  # For production build
```

### 3. Test
The compiled CSS files are automatically enqueued by WordPress.

## 📱 Responsive Design

The theme uses a mobile-first approach:

```scss
// Base styles (mobile)
.component {
  // Mobile styles
}

// Tablet and up
@media (min-width: 768px) {
  .component {
    // Tablet styles
  }
}

// Desktop and up
@media (min-width: 992px) {
  .component {
    // Desktop styles
  }
}
```

## 🎨 Customization

### Adding New Variables
Add to `root.scss` in the `:root` selector:

```scss
:root {
  --tf-custom-color: #ff0000;
  --tf-custom-spacing: 2rem;
}
```

### Adding New Components
Add to `main.scss`:

```scss
/* ==========================================================================
   Custom Component
   ========================================================================== */

.custom-component {
  // Your styles here
  background-color: var(--tf-primary);
  padding: var(--tf-spacing-4);
  
  &:hover {
    background-color: var(--tf-primary-dark);
  }
}
```

### Using Variables
Reference variables in your SCSS:

```scss
.my-element {
  color: var(--tf-primary);
  font-size: var(--tf-font-size-lg);
  margin: var(--tf-spacing-4);
  border-radius: var(--tf-border-radius);
}
```

## 🔍 Source Maps

Source maps are generated for easier debugging:
- `root.css.map` - Maps to `root.scss`
- `main.css.map` - Maps to `main.scss`

Enable source maps in your browser's developer tools to debug SCSS directly.

## 🚀 Production Build

For production, use compressed CSS:

```bash
npm run build
```

This creates minified CSS files without source maps for better performance.

## 🐛 Troubleshooting

### SCSS Not Compiling
1. Check if Sass is installed: `sass --version`
2. Reinstall dependencies: `npm install`
3. Check file paths in `package.json` scripts

### CSS Not Loading
1. Ensure CSS files exist in `css/` directory
2. Check WordPress enqueue in `functions.php`
3. Clear any caching plugins

### Variables Not Working
1. Ensure variables are defined in `:root` in `root.scss`
2. Check CSS file order (root.css loads before main.css)
3. Verify variable names match exactly

## 📚 Resources

- [Sass Documentation](https://sass-lang.com/documentation)
- [CSS Custom Properties](https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties)
- [WordPress Child Themes](https://developer.wordpress.org/themes/advanced-topics/child-themes/)

## 🤝 Contributing

1. Make changes to SCSS files
2. Compile with `npm run build`
3. Test your changes
4. Commit both SCSS and compiled CSS files
5. Submit a pull request

---

**Note**: Always compile SCSS files before committing to ensure the CSS is up to date!
