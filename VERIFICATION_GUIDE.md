# Verification & Testing Guide

## Quick Verification Steps

After implementing the KachoTech custom header, follow these steps to ensure everything is working correctly.

## 1. Visual Verification

### Check if header displays:
```
1. Go to your website frontend
2. Look for:
   ✓ Dark top bar with "KACHOTECH WINTER FEST"
   ✓ Header with logo, search bar, and actions
   ✓ Navigation strip with product categories
3. If not visible, check:
   - CSS file uploaded to: assets/css/header-custom.css
   - JS file uploaded to: assets/js/header-custom.js
   - PHP template at: template-parts/header/header-main.php
   - Clear WordPress cache
```

## 2. Console Verification (Browser DevTools)

### Open DevTools (Press F12)

**Check Console tab:**
```javascript
// You should NOT see any errors like:
- 404 errors for CSS/JS files
- "Cannot read property of undefined"
- "$ is not defined"
```

**Expected console output:**
```
✓ No errors
✓ RemixIcon fonts loaded
✓ Custom CSS applied
```

**Verify JavaScript loads:**
```javascript
// In Console, type:
typeof KT_AJAX

// Should return: "object"
// This confirms the script is loaded
```

## 3. Network Tab Verification

### Monitor resources loading:

```
DevTools → Network tab:

✓ header-custom.css - Status 200 (loaded successfully)
✓ header-custom.js - Status 200 (loaded successfully)
✓ remixicon.css - Status 200 (icons available)
✓ fonts.googleapis.com - Status 200 (Poppins font loaded)

Any 404 errors = File is missing or path is incorrect
```

## 4. Search Functionality Test

### Test 1: Basic Search
```
1. Click in the search box
2. Type: "heater" (or any product in your store)
3. Wait 300ms for results
4. Verify:
   ✓ Results appear below search box
   ✓ Product images visible
   ✓ Product names visible
   ✓ Prices displayed
```

### Test 2: Category Filter
```
1. Click "All Categories" dropdown
2. Select "Heaters" (or any category)
3. Type in search box
4. Verify:
   ✓ Only products from selected category appear
   ✓ Category label updated
```

### Test 3: Empty Results
```
1. Type: "xyzabc12345" (non-existent product)
2. Verify:
   ✓ "No products found." message appears
   ✓ No JavaScript errors
```

### Test 4: Search Submit
```
1. Type: "heater" in search box
2. Click "Search" button
3. Verify:
   ✓ Page navigates to search results page
   ✓ URL contains: /?s=heater
```

## 5. WooCommerce Integration Test

### Test Cart Count:
```
1. Go to any product
2. Add to cart (1 item)
3. Scroll to header
4. Verify:
   ✓ Cart count badge shows "1"
   ✓ Badge is red (primary color)
```

### Test Account Link:
```
1. If NOT logged in:
   ✓ Header shows "Login / Register"
   ✓ Clicking opens login page

2. If logged in:
   ✓ Header shows "My Account"
   ✓ Clicking opens my account page
```

### Test Cart Link:
```
1. Click "Cart" button
2. Verify:
   ✓ Navigates to cart page
   ✓ Shows correct cart contents
   ✓ Cart count matches
```

## 6. Responsive Design Test

### Mobile (< 768px):
```
1. Press F12, then Ctrl+Shift+M (toggle device toolbar)
2. Set width to 375px (iPhone)
3. Verify:
   ✓ Top bar is vertical
   ✓ Header stacks properly
   ✓ Search bar is usable
   ✓ Cart button touchable (44px minimum)
   ✓ No horizontal scroll
```

### Tablet (768px - 1024px):
```
1. Set width to 800px
2. Verify:
   ✓ Layout adapts correctly
   ✓ Navigation is visible
   ✓ Spacing is appropriate
```

### Desktop (> 1024px):
```
1. Set width to 1200px
2. Verify:
   ✓ Full 3-column layout
   ✓ All elements visible
   ✓ Hover effects work
```

## 7. Browser Compatibility Test

### Test on different browsers:

**Chrome:**
```
✓ Visit site in Chrome
✓ Open DevTools (F12)
✓ Check Console for errors
✓ Verify: No errors, styles apply correctly
```

**Firefox:**
```
✓ Visit site in Firefox
✓ Open DevTools (F12)
✓ Check Console for errors
✓ Verify: Styling matches Chrome
```

**Safari:**
```
✓ Visit site in Safari
✓ Open Develop menu (Cmd+Option+U)
✓ Check Web Inspector
✓ Verify: All functionality works
```

**Mobile Chrome:**
```
✓ Visit site on Android device
✓ Check: Layout looks good
✓ Verify: Touch interactions work
```

**Mobile Safari:**
```
✓ Visit site on iPhone
✓ Check: Layout looks good
✓ Verify: Touch interactions work
```

## 8. PHP/WordPress Verification

### Check WordPress Error Log:

```php
// In wp-config.php, verify:
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG', true);

// Check log file for errors:
/wp-content/debug.log

// Should contain NO errors related to:
- header-main.php
- header-custom.css
- header-custom.js
```

### Verify Enqueue:

```php
// Add to header.php temporarily:
<?php
if (is_front_page()) {
    global $wp_scripts;
    error_log('Registered Scripts: ' . print_r($wp_scripts->registered, true));
}
?>

// Check debug.log to verify:
✓ 'kachotech-header-styles' is registered
✓ 'kachotech-header-script' is registered
```

## 9. Performance Verification

### Check Page Load Speed:

```
Using Google PageSpeed Insights (https://pagespeed.web.dev/):

✓ Input your domain
✓ Verify:
  - No critical CSS/JS errors
  - Assets loading properly
  - Performance score maintained
  - No new errors introduced
```

### Check Waterfall:

```
DevTools → Network tab:

✓ Sort by type
✓ Verify:
  - CSS loads before JS
  - Images load efficiently
  - No render-blocking resources
```

## 10. Functionality Checklist

### Create this test plan:

```
□ Header displays on all pages
□ Search box is functional
□ Category dropdown works
□ Search returns results
□ Category filter works
□ Empty search message shows
□ Cart count updates
□ My Account link works
□ Cart link works
□ Mobile layout responsive
□ Tablet layout responsive
□ Desktop layout responsive
□ No console errors
□ No PHP errors
□ Links navigate correctly
□ Images load properly
```

## 11. Troubleshooting Guide

### If header is NOT visible:

```bash
# Check 1: Verify template part is called
# In header.php, look for:
<?php get_template_part( 'template-parts/header/header', 'main' ); ?>

# Check 2: Verify CSS is enqueued
# In browser: View Page Source (Ctrl+U)
# Look for: <link rel="stylesheet" href=".../header-custom.css">

# Check 3: Verify JS is enqueued
# In browser: View Page Source
# Look for: <script src=".../header-custom.js"></script>

# Check 4: Clear cache
# WordPress Admin → Settings → Clear Cache (if plugin installed)
# Or manually clear: wp-content/cache/

# Check 5: Check file permissions
$ ls -la assets/css/header-custom.css
# Should show: -rw-r--r-- (644)

$ ls -la assets/js/header-custom.js
# Should show: -rw-r--r-- (644)
```

### If search is NOT working:

```bash
# Check 1: Verify WooCommerce is active
# WordPress Admin → Plugins → Check WooCommerce status

# Check 2: Verify REST API is working
# In browser, visit: /wp-json/wc/store/products
# Should return JSON product data

# Check 3: Check AJAX request in DevTools
# DevTools → Network tab
# Type in search box
# Look for XHR request to: /wp-json/wc/store/products
# Status should be 200
# Response should contain product data

# Check 4: Check console for errors
# DevTools → Console
# Any errors should be visible here

# Check 5: Verify product categories exist
# WordPress Admin → Products → Categories
# Should have at least 1 category with products
```

### If cart count is NOT updating:

```bash
# Check 1: Add product to cart
# DevTools → Network tab
# Look for AJAX request
# Should show cart updated

# Check 2: Verify WooCommerce.js is loaded
# View Page Source
# Look for: /js/frontend/cart.min.js

# Check 3: Check WooCommerce settings
# WordPress Admin → WooCommerce → Settings → General
# Verify: Cart & Checkout pages are set
```

## 12. API Endpoint Testing

### Test WooCommerce Store API directly:

```bash
# In browser console, test:
fetch('/wp-json/wc/store/products?search=heater&per_page=5')
  .then(r => r.json())
  .then(d => console.log(d))

# Expected output:
[
  {
    id: 123,
    name: "Electric Heater",
    prices: { price: "2500.00", currency_symbol: "Rs. " },
    images: [ { src: "..." } ],
    permalink: "..."
  },
  ...
]
```

## 13. Staging Environment Testing

Before deploying to production:

```
1. Copy website to staging environment
2. Run full verification suite on staging
3. Test with production data (if available)
4. Monitor error logs for 24 hours
5. Verify with team before production deployment
```

## 14. Production Monitoring

After deploying to production:

```
Monitoring for first 24 hours:

□ Check error logs hourly
□ Monitor page load speeds
□ Check user feedback
□ Monitor search usage
□ Verify cart functionality
□ Check for 404 errors
□ Monitor server resources
□ Verify backups are running
```

## Quick Reference: File Locations

```
astra-child/
├── assets/
│   ├── css/
│   │   └── header-custom.css ✓
│   └── js/
│       └── header-custom.js ✓
├── template-parts/
│   └── header/
│       └── header-main.php ✓
├── inc/
│   └── enqueue.php ✓
└── header.php (includes header-main.php)
```

## Quick Reference: Browser DevTools Checks

```
Console (F12):
✓ No errors
✓ No warnings about missing files
✓ KT_AJAX is defined

Network Tab (F12):
✓ All CSS/JS files: 200 status
✓ No 404 errors
✓ Load time < 2 seconds

Sources Tab (F12):
✓ header-custom.js is present
✓ Can set breakpoints
✓ Can debug search function

Performance Tab (F12):
✓ Rendering time < 16ms per frame
✓ No jank or stuttering
✓ Smooth animations
```

## Conclusion

If all checks pass:
✅ **Implementation is successful!**
✅ **Ready for production deployment!**

If any checks fail:
❌ Check troubleshooting section
❌ Review error messages carefully
❌ Check file permissions
❌ Verify plugin/theme versions
❌ Clear all caches
❌ Try in incognito mode
