# Fix 404 Error for Kids Posts

## The Problem
You're getting a 404 error when trying to access Kids post URLs like:
`http://forever-together.local/kids/mukhammadayub-jaloliddinov/`

This happens because WordPress needs to "flush" its rewrite rules after we added the new custom post type.

## Quick Fix (Recommended)

### Method 1: WordPress Admin
1. Go to **WordPress Admin Dashboard**
2. Navigate to **Settings → Permalinks**
3. Click **"Save Changes"** (don't change anything, just save)
4. This will flush the rewrite rules and fix the URLs

### Method 2: Theme Activation
1. Go to **Appearance → Themes**
2. Switch to a different theme temporarily
3. Switch back to "Together Forever" theme
4. This will trigger the rewrite rules flush

## Why This Happens

When we register a new custom post type (`kids`) in WordPress, it needs to:
1. Register the post type
2. Update the rewrite rules (URL structure)
3. Tell WordPress how to handle URLs like `/kids/post-name/`

The rewrite rules need to be "flushed" (refreshed) so WordPress knows about the new URL structure.

## What I've Added to Fix This

I've updated `functions.php` with automatic rewrite rule flushing:

```php
/**
 * Flush rewrite rules when needed
 */
function together_forever_force_flush_rewrite_rules() {
    $version = get_option('together_forever_rewrite_rules_version', '1.0');
    $current_version = '1.1'; // Increment this when you need to flush rules
    
    if (version_compare($version, $current_version, '<')) {
        flush_rewrite_rules();
        update_option('together_forever_rewrite_rules_version', $current_version);
    }
}
add_action('init', 'together_forever_force_flush_rewrite_rules', 99);
```

This will automatically flush the rules when needed.

## Alternative: Manual Flush via Code

If the above doesn't work, you can add this temporary code to your theme:

```php
// Add this to functions.php temporarily, then remove after one page load
flush_rewrite_rules();
```

**Important**: Remove this line after the first page load!

## Test After Fix

After flushing rewrite rules:
1. Go to **Kids → All Kids** in WordPress admin
2. Click on a Kids post
3. The URL should work: `yoursite.com/kids/post-name/`
4. You should see the beautiful single kids template

## Permalink Structure

The Kids posts will use this URL structure:
- **Base**: `/kids/` (from post type registration)
- **Individual post**: `/kids/post-name/`
- **Category archive**: `/kids/category/category-name/`

## If Still Not Working

1. **Check if post exists**: Make sure the Kids post is published (not draft)
2. **Check slug**: Verify the post slug in the WordPress editor
3. **Check .htaccess**: Make sure your .htaccess file is writable
4. **Clear cache**: Clear any caching plugins
5. **Try different permalink structure**: Go to Settings → Permalinks and try "Post name"

## Common Issues

### 1. Post Status
Make sure your Kids post is **Published**, not Draft or Private.

### 2. Slug Issues
In the WordPress editor, check the "Permalink" section below the title. Make sure it shows:
`yoursite.com/kids/your-post-name`

### 3. Server Configuration
Some servers need extra configuration for custom post types. Contact your hosting provider if issues persist.

## Success Indicators

After the fix works, you should see:
- ✅ Kids post URLs load without 404
- ✅ Beautiful single kids template displays
- ✅ Progress bars and donation buttons work
- ✅ Related kids show in sidebar

## Need Help?

If you're still having issues:
1. Try Method 1 above (Settings → Permalinks → Save)
2. Check if the Kids post is published
3. Verify the URL structure in WordPress admin
4. Clear browser cache and try again

The rewrite rules flush is a one-time fix - once done, all Kids posts will work perfectly!
