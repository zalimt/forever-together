# 🚀 Live Server Deployment Guide

## Your Live Server
**URL:** `https://red-grouse-914732.hostingsite.com/`

## The Problem
- ✅ Changes work on **local** (forever-together.local)
- ❌ Changes **DON'T appear** on **live** server
- Example: Button shows "GIFT A CERTIFICATE" instead of "123Gift a Certificate"

## The Solution

### Step 1: Upload Your Files
Upload your updated `front-page.php` to your live server via:
- FTP/SFTP
- cPanel File Manager
- Your hosting control panel

### Step 2: Clear Live Server Cache
**Run this script on your live server:**
```
https://red-grouse-914732.hostingsite.com/live-cache-buster.php
```

This script will:
- Delete ALL cache files
- Update ALL timestamps
- Clear ALL cache types
- Force complete refresh

### Step 3: Clear Browser Cache
**Hard refresh your browser:**
- Windows: `Ctrl + Shift + R`
- Mac: `Cmd + Shift + R`

**Or better:** Open in **INCOGNITO/PRIVATE** window

### Step 4: Verify Changes
Your button should now show "123Gift a Certificate" instead of "GIFT A CERTIFICATE"

---

## Quick Commands

### Upload Files:
```
1. front-page.php → /wp-content/themes/together-forever/
2. functions.php → /wp-content/themes/together-forever/
3. live-cache-buster.php → / (root directory)
```

### Clear Cache:
```
https://red-grouse-914732.hostingsite.com/live-cache-buster.php
```

### Test:
```
https://red-grouse-914732.hostingsite.com/ (hard refresh)
```

---

## Why This Happens

### Local Environment:
- Cache plugins often disabled
- No aggressive caching
- Changes appear immediately

### Live Server:
- Cache plugins ACTIVE for performance
- Static HTML files served
- Must clear cache to see changes

---

## Files to Upload to Live Server

### First Time (upload once):
```
1. wp-content/themes/together-forever/functions.php (UPDATED)
2. live-cache-buster.php (NEW)
3. nuclear-cache-clear.php (NEW)
4. test-cache-fix.php (NEW)
```

### Every Update:
```
1. wp-content/themes/together-forever/front-page.php (your changes)
```

Then run: `https://red-grouse-914732.hostingsite.com/live-cache-buster.php`

---

## Workflow for Every Change

1. **Edit locally** and test ✅
2. **Upload to live server** 📤
3. **Run cache buster:** `https://red-grouse-914732.hostingsite.com/live-cache-buster.php` ⚡
4. **Hard refresh browser:** `Ctrl+Shift+R` 🔄
5. **Verify changes appear** ✅

---

## Troubleshooting

### Changes still don't appear?

1. **Check file uploaded correctly:**
   - Verify file modified date on server
   - Check file contents match local

2. **Run cache buster again:**
   - Safe to run multiple times
   - Clears everything each time

3. **Clear browser cache properly:**
   - Use incognito window
   - Or DevTools → Empty Cache and Hard Reload

4. **Check for CDN caching:**
   - If using Cloudflare, clear CDN cache too
   - Check hosting control panel

---

## Bookmark These URLs

**Cache Buster:**
```
https://red-grouse-914732.hostingsite.com/live-cache-buster.php
```

**Test Script:**
```
https://red-grouse-914732.hostingsite.com/test-cache-fix.php
```

**Your Site:**
```
https://red-grouse-914732.hostingsite.com/
```

---

## Pro Tips

1. **Always test in incognito** - bypasses all browser cache
2. **Bookmark the cache buster** - for quick access
3. **Make small changes** - easier to verify
4. **Keep backups** - before major changes
5. **Deploy during off-peak** - if making major updates

---

## Summary

**The issue:** Live server serves cached HTML files
**The solution:** Run `live-cache-buster.php` after every upload
**The result:** Changes appear immediately!

**Status:** ✅ Ready to deploy

