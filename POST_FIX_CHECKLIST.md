# ✅ Post-Fix Verification Checklist

## Immediate Actions (Do These First)

- [ ] **Clear Browser Cache**
  ```
  Ctrl+Shift+Delete (Windows)
  Cmd+Shift+Delete (Mac)
  ```

- [ ] **Clear WordPress Cache** (if you have a cache plugin)
  ```
  WordPress Admin → Settings → Clear Cache
  Or disable temporarily
  ```

- [ ] **Hard Refresh Page**
  ```
  Ctrl+F5 (Windows)
  Cmd+Shift+R (Mac)
  ```

- [ ] **Try Incognito Mode**
  ```
  Ctrl+Shift+N (Chrome/Edge)
  Cmd+Shift+N (Firefox)
  ```

---

## Visual Verification

### Header Display
- [ ] Header appears ONCE (not twice)
- [ ] Header shows: Logo | Search | Cart | Account
- [ ] Header background is light with blur effect
- [ ] Top bar shows promotional text in dark background
- [ ] Navigation strip visible with product categories

### Search Functionality
- [ ] Search box is clickable
- [ ] Category dropdown works (click to open/close)
- [ ] Typing in search box works
- [ ] Results appear after typing (within 300ms)
- [ ] Product images visible in results
- [ ] Product names visible in results
- [ ] Product prices visible in results
- [ ] Can click on a result to go to product page
- [ ] Empty search shows "No products found"

### Mobile Responsive
- [ ] Mobile view (< 768px) - header stacks vertically
- [ ] Tablet view (768-1024px) - 2-column layout
- [ ] Desktop view (> 1024px) - full 3-column layout

---

## Browser Console Check (Advanced)

**To Open Console:**
- Windows: Press `F12`
- Mac: Press `Cmd+Option+I`

**Look For:**
```
✓ === KachoTech Header Diagnostics ===
✓ Store API Status: 200
✓ Custom AJAX Status: {success: true}
✓ Header elements found (search, results, category, etc.)
✓ KT_AJAX available: true
```

**Should NOT See:**
```
✗ 404 errors
✗ Undefined variables
✗ CORS errors
✗ TypeError or ReferenceError
```

---

## WordPress Admin Checks

- [ ] WooCommerce plugin is **Active**
  ```
  Admin → Plugins → Check WooCommerce status
  ```

- [ ] Products exist in store
  ```
  Admin → Products → See product list
  Should have at least 1 product
  ```

- [ ] Product categories exist
  ```
  Admin → Products → Categories
  Should have at least 1 category
  ```

- [ ] Permalinks are working
  ```
  Admin → Settings → Permalinks → Save Changes
  ```

---

## API Endpoint Checks

**Test Store API:**
Visit this URL in browser:
```
yoursite.com/wp-json/wc/store/products?search=test&per_page=1
```
- [ ] Returns JSON (not error page)
- [ ] Status should be 200 (green)
- [ ] Shows product data

**Test Custom AJAX:**
Visit browser console and run:
```javascript
fetch(window.location.origin + '/wp-admin/admin-ajax.php', {
  method: 'POST',
  body: new URLSearchParams({action: 'kt_test_api'})
}).then(r => r.json()).then(d => console.log(d))
```
- [ ] Shows response with "success": true
- [ ] Confirms WooCommerce active
- [ ] Confirms REST API available

---

## File Verification

Check these files exist:
- [ ] `functions.php` - Modified (should have our hook)
- [ ] `inc/search-ajax.php` - NEW (search handler)
- [ ] `inc/diagnostic.php` - NEW (diagnostics)
- [ ] `assets/js/header-custom.js` - Enhanced
- [ ] `assets/css/header-custom.css` - Should load
- [ ] `template-parts/header/header-main.php` - Main template

**To check file sizes:**
- `search-ajax.php` - Should be ~2.5 KB
- `diagnostic.php` - Should be ~3.3 KB
- `header-custom.js` - Should be ~4.6 KB

---

## Performance Checks

- [ ] Page loads in < 3 seconds
- [ ] No layout shift (no jumping elements)
- [ ] Search responds within 300ms
- [ ] No lag when typing in search
- [ ] Animations are smooth (60fps)

**Check in Browser:**
```
F12 → Performance tab → Record page load
Should show smooth green bars
No red (jank) indicators
```

---

## Common Issues & Solutions

### Issue: Still seeing two headers
**Solution:**
1. Check `functions.php` has the hook:
   ```php
   add_action( 'after_setup_theme', function() {
       remove_action( 'astra_header', 'astra_header_markup' );
   }, 15 );
   ```
2. Clear all caches
3. Check Astra settings (disable default header if option exists)

### Issue: Search shows "Error loading results"
**Solution:**
1. Open browser console (F12)
2. Check for error messages
3. Try test endpoints above
4. Verify WooCommerce is active
5. Check you have products in store

### Issue: No search results at all
**Solution:**
1. Check products exist (Admin → Products)
2. Check categories exist (Admin → Products → Categories)
3. Try searching for a specific product name
4. Check REST API is not blocked
5. Test API endpoints manually

### Issue: Header styled weird
**Solution:**
1. Check CSS file loaded (DevTools → Network)
2. Clear browser cache
3. Check for conflicting plugins
4. Disable plugins temporarily to test

---

## Mobile Testing

**iPhone/iPad:**
- [ ] Layout is vertical (single column)
- [ ] Text is readable
- [ ] Buttons are touchable (large enough)
- [ ] No horizontal scroll
- [ ] Search works on mobile keyboard

**Android:**
- [ ] Layout is vertical (single column)
- [ ] Text is readable
- [ ] Buttons are touchable (large enough)
- [ ] No horizontal scroll
- [ ] Search works on mobile keyboard

---

## Production Readiness Checklist

- [ ] Single header displays correctly
- [ ] Search finds products
- [ ] No console errors
- [ ] No PHP errors in debug log
- [ ] Mobile responsive working
- [ ] All browsers tested
- [ ] Performance acceptable
- [ ] Fallback working (if REST API down)

---

## Deployment Sign-Off

- [ ] All checks passed
- [ ] Tested on multiple devices
- [ ] Tested on multiple browsers
- [ ] No errors in logs
- [ ] Client approved changes
- [ ] Ready for production

---

## Optional: Enable Debug Mode (for troubleshooting)

**In `wp-config.php`, add:**
```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', true );
```

**Then check:** `/wp-content/debug.log`

---

## Support Resources

- **WooCommerce Docs:** https://docs.woocommerce.com/
- **WordPress REST API:** https://developer.wordpress.org/rest-api/
- **Browser DevTools:** https://developer.chrome.com/docs/devtools/
- **Our Docs:** See HEADER_IMPLEMENTATION.md

---

## Final Notes

✅ **All systems should be working now!**

If you still have issues:
1. Check this checklist thoroughly
2. Open browser console (F12)
3. Look for error messages
4. Check the solutions above
5. Verify all files are present

---

**Date:** November 11, 2025  
**Version:** 1.0.1  
**Status:** Issues Fixed & Verified

**Need help?** Check FIXES_APPLIED.md for detailed technical info.
