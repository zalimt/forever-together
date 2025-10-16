#!/bin/bash

# Together Forever Theme Build Script
# This script compiles SCSS files to CSS

echo "🎨 Building Together Forever Theme..."

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing dependencies..."
    npm install
fi

# Create css directory if it doesn't exist
mkdir -p css

# Compile SCSS files
echo "🔨 Compiling SCSS files..."

# Compile root.scss to root.css
echo "  → Compiling root.scss..."
sass scss/root.scss css/root.css --style=compressed --source-map

# Compile main.scss to main.css
echo "  → Compiling main.scss..."
sass scss/main.scss css/main.css --style=compressed --source-map

# Compile single-kids.scss to single-kids.css
echo "  → Compiling single-kids.scss..."
sass scss/single-kids.scss css/single-kids.css --style=compressed --source-map

echo "✅ Build complete!"
echo ""
echo "📁 Generated files:"
echo "  - css/root.css"
echo "  - css/main.css"
echo "  - css/single-kids.css"
echo "  - css/root.css.map"
echo "  - css/main.css.map"
echo "  - css/single-kids.css.map"
echo ""
echo "🚀 Your theme is ready to use!"
