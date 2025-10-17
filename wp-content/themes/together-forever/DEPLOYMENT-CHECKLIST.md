# 🚀 Deploy Front Page Changes to Live - Checklist

## Quick Checklist for Uploading front-page.php Changes

### Before Upload
- [ ] Test changes on local development site
- [ ] Verify all links and functionality work
- [ ] Check browser console for JavaScript errors
- [ ] Review responsive design (mobile, tablet, desktop)

### Upload to Live Server
- [ ] Upload updated `functions.php` to `/wp-content/themes/together-forever/`
- [ ] Upload updated `front-page.php` to `/wp-content/themes/together-forever/`
- [ ] Upload `clear-front-page-cache.php` to root directory (first time only)
- [ ] Upload `clear-cache-now.php` to root directory (if updated)

### Clear Cache (Choose One)

#### Option 1: Automatic (Wait)
- [ ] Wait 60 seconds after uploading
- [ ] System will auto-detect and clear cache

#### Option 2: Manual Instant (Recommended)
- [ ] Visit: `https://yourlivesite.com/clear-front-page-cache.php`
- [ ] Verify all checks show ✅ green
- [ ] Look for any error messages

#### Option 3: WordPress Admin
- [ ] Log in to WordPress Admin
- [ ] Go to **WP Fastest Cache** menu
- [ ] Click **"Delete Cache"** button

### Verify Changes
- [ ] Hard refresh browser: `Ctrl+Shift+R` (Win) or `Cmd+Shift+R` (Mac)
- [ ] Check front page - changes should appear
- [ ] Test in **incognito/private window** to rule out browser cache
- [ ] Test on mobile device
- [ ] Check browser console for errors

### If Changes Don't Appear
- [ ] Run `clear-front-page-cache.php` again and check output
- [ ] Verify file was actually uploaded (check modified date)
- [ ] Clear CDN cache if using Cloudflare, etc.
- [ ] Try different browser
- [ ] Check WordPress debug log for PHP errors

### Final Checks
- [ ] All content displays correctly
- [ ] All images load
- [ ] All links work
- [ ] Responsive design works on mobile
- [ ] No JavaScript errors in console

## Quick Commands

**Clear cache manually:**
```
https://yourlivesite.com/clear-front-page-cache.php
```

**Quick clear:**
```
https://yourlivesite.com/clear-cache-now.php
```

**Hard refresh browser:**
- Windows/Linux: `Ctrl + Shift + R`
- Mac: `Cmd + Shift + R`

## Files to Upload (First Time Only)

These files include the cache fix and need to be uploaded once:

```
✅ wp-content/themes/together-forever/functions.php (modified)
✅ clear-front-page-cache.php (new)
✅ clear-cache-now.php (updated)
```

## Files to Upload (Every Update)

When you update the front page, only upload:

```
✅ wp-content/themes/together-forever/front-page.php
```

Then clear cache using one of the methods above.

## Common Issues

### Issue: "Changes work locally but not live"
**Solution:** WP Fastest Cache is serving old HTML. Run cache clearing script.

### Issue: "Script shows all ✅ but changes still don't appear"
**Solution:** Clear your browser cache (`Ctrl+Shift+R`) or test in incognito.

### Issue: "Access denied when running script"
**Solution:** Log in to WordPress admin first. Scripts require admin access.

### Issue: "File uploaded but shows old content"
**Solution:** Check file modified date on server. Upload might have failed.

### Issue: "Changes appear then disappear"
**Solution:** Cache is being regenerated. Disable WP Fastest Cache temporarily.

## Pro Tips

1. **Bookmark cache clearing URLs** for quick access
2. **Always test in incognito** to verify cache is truly cleared
3. **Make small changes** and test frequently rather than big changes all at once
4. **Keep backup** of working version before making major changes
5. **Use version control (Git)** to track changes
6. **Deploy during off-peak hours** if making major changes

## Need Help?

📚 **Full Documentation:** `FRONT-PAGE-CACHE-FIX.md`  
⚡ **Quick Reference:** `CACHE-QUICK-REFERENCE.md`  
🔧 **Implementation Details:** `CACHE-FIX-README.md`

---

**Remember:** The fix is automatic (60 seconds), but manual clearing is instant!


