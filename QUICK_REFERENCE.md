# Quick Reference: Which Files Do What

## 🎯 The 3 Key Files You Need to Fix

### File 1: `inc/shop-ajax.php` (Backend - Server-side)
**What it does:** Processes filter requests and returns filtered product HTML

**When it runs:** When user clicks filter buttons/sliders (via AJAX)

**Current bugs:**
```
- Rating filter doesn't actually filter (uses continue statement)
- Stock filter meta_query is malformed
- Missing orderby parameter
- Pagination count is wrong
- Array handling issues
```

**Fix required:** Restructure all query logic

---

### File 2: `assets/js/shop.js` (Frontend - Client-side)  
**What it does:** Handles user interactions and sends AJAX requests

**When it runs:** When user interacts with filters

**Current bugs:**
```
- Missing orderby in AJAX data
- Missing orderby in filter storage
- Missing sort dropdown listener
```

**Fix required:** Add orderby parameter support

---

### File 3: `woocommerce/archive-product.php` (Template)
**What it does:** Initial page structure and CSS/JS loading

**When it runs:** Page loads

**Current bugs:**
```
- Sort dropdown reloads page instead of using AJAX
- onchange="this.form.submit()" is wrong approach
```

**Fix required:** Remove page reload behavior

---

## 📊 Data Flow: How They Work Together

```
USER INTERACTION (Browser)
        ↓
    [Sort dropdown changed]
        ↓
shop.js captures event
        ↓
shop.js gathers filter values:
  - Selected categories
  - Selected brands
  - Price range
  - Rating filter
  - Stock status
  - Search term
  - ORDERBY value ← MISSING!
        ↓
shop.js sends AJAX POST to WordPress
        ↓
    [HTTP Request]
        ↓
WordPress routes to shop-ajax.php
    (via action: kt_filter_products)
        ↓
shop-ajax.php receives parameters
        ↓
shop-ajax.php builds WP_Query
        ↓
shop-ajax.php applies filters:
  ├─ Tax query (categories, brands)
  ├─ Meta query (price, stock)
  ├─ Rating filter (PHP-level)
  ├─ Search query
  └─ Order by ← MISSING!
        ↓
shop-ajax.php queries database
        ↓
shop-ajax.php generates HTML
  (product cards for matching items)
        ↓
shop-ajax.php calculates pagination
        ↓
shop-ajax.php returns JSON:
  {
    success: true,
    data: {
      html: "...",
      pagination: "...",
      total_products: 42,
      total_pages: 3
    }
  }
        ↓
    [HTTP Response]
        ↓
shop.js receives response
        ↓
shop.js updates DOM:
  - Replace product grid HTML
  - Replace pagination HTML
  - Update product count
        ↓
USER SEES RESULTS (Page updates via AJAX)
```

---

## 📁 File Organization

```
astra-child/
├── woocommerce/
│   └── archive-product.php ⚠️ [Enqueues CSS/JS + Initial markup]
│       ├─ Loads: shop-layout.css
│       ├─ Loads: shop.js
│       ├─ Loads: FontAwesome
│       └─ Defines: Filter UI structure
│
├── assets/
│   ├── css/
│   │   └── shop-layout.css ✅ [Styling only]
│   │
│   └── js/
│       └── shop.js ⚠️ [Handles filter interactions]
│           ├─ Listens to filter changes
│           ├─ Sends AJAX to shop-ajax.php
│           ├─ Updates DOM with results
│           └─ Manages filter state
│
└── inc/
    ├── enqueue.php ✅ [Registers scripts globally]
    ├── functions.php ✅ [Theme setup]
    └── shop-ajax.php ⚠️ [Processes AJAX filter requests]
        ├─ Receives: Filter parameters
        ├─ Executes: Database queries
        ├─ Returns: Filtered product HTML + pagination
        └─ Status: Most broken file
```

---

## 🔧 Specific Issues & Where to Fix Them

### Issue 1: Sort/Orderby Not Working
**Where:** `assets/js/shop.js` + `inc/shop-ajax.php` + `woocommerce/archive-product.php`

**What's broken:**
- Template sends form data (page reload) instead of AJAX
- JavaScript doesn't capture sort value
- Backend doesn't process orderby parameter

**Fix locations:**
1. `archive-product.php` line 110: Remove `onchange="this.form.submit()"`
2. `shop.js` line ~240: Add sort listener
3. `shop.js` line ~250: Add orderby to currentFilters
4. `shop.js` line ~287: Add orderby to filterData
5. `shop-ajax.php` line 25: Add $orderby extraction
6. `shop-ajax.php` line 50: Add orderby to query args

---

### Issue 2: Rating Filter Doesn't Actually Filter
**Where:** `inc/shop-ajax.php`

**What's broken:**
```php
// Current code (WRONG)
if ( $min_rating > 0 && $rating < $min_rating ) {
    continue;  // Just skips rendering, DB still has all products
}
```

**Fix location:**
`shop-ajax.php` after line 150

**Solution:**
```php
// Should use array_filter AFTER query
$filtered_posts = array_filter( $filtered_posts, function($post) use ($min_rating) {
    $product = wc_get_product($post->ID);
    return $product && $product->get_average_rating() >= $min_rating;
});
```

---

### Issue 3: Stock Filter Not Working
**Where:** `inc/shop-ajax.php`

**What's broken:**
```php
// Current code (WRONG - malformed meta_query)
$meta_query[] = array_merge( $stock_query, array( 'relation' => 'OR' ) );
```

**Fix location:**
`shop-ajax.php` lines 105-125

**Solution:**
```php
// Proper nested structure
$stock_clauses = array( 'relation' => 'OR' );
foreach ( $availability_array as $status ) {
    $stock_clauses[] = array(
        'key'   => '_stock_status',
        'value' => $status === 'in-stock' ? 'instock' : 'outofstock',
    );
}
if ( count( $stock_clauses ) > 1 ) {
    $args['meta_query'][] = $stock_clauses;
}
```

---

### Issue 4: Pagination Shows Wrong Product Count
**Where:** `inc/shop-ajax.php`

**What's broken:**
```php
// Current code (WRONG)
$total_products = $query->found_posts;  // DB count, not filtered count!
```

**Fix location:**
`shop-ajax.php` lines 250-260

**Solution:**
```php
// Use filtered post count
$total_products = count( $filtered_posts );
$per_page = intval( get_option( 'posts_per_page', 12 ) );
$max_pages = ceil( $total_products / $per_page );
```

---

## ✅ Changes Summary

### `inc/shop-ajax.php` (Most changes here)
- [ ] Line 25: Add `$orderby` extraction
- [ ] Line 35-50: Initialize meta_query and tax_query properly
- [ ] Lines 50-75: Add orderby switch statement
- [ ] Lines 95-130: Fix tax_query and meta_query building
- [ ] Lines 140-170: Replace rating filter with array_filter
- [ ] Lines 250-265: Fix pagination calculation

### `assets/js/shop.js` (Minor changes)
- [ ] Add sort dropdown event listener
- [ ] Add `orderby` to `currentFilters` object
- [ ] Add `orderby` to `filterData` object
- [ ] Update function signature to accept `orderby` parameter

### `woocommerce/archive-product.php` (1 line change)
- [ ] Line 110: Remove `onchange="this.form.submit()"`

---

## 🎓 Understanding the Code

**Why separate files?**
- **Separation of Concerns**: Template, styling, and logic are separate
- **Reusability**: shop.js and shop-ajax.php can be reused
- **Performance**: Only load needed JavaScript/CSS
- **Maintainability**: Easy to find and fix issues

**How WordPress AJAX works:**
1. Frontend sends POST to `wp-admin/admin-ajax.php?action=kt_filter_products`
2. WordPress looks for `add_action('wp_ajax_kt_filter_products', 'kt_filter_products_ajax')`
3. WordPress calls the registered function in `shop-ajax.php`
4. Function processes and returns JSON
5. Frontend JavaScript receives JSON and updates page

---

## 🚀 Priority Order to Fix

1. **First**: `inc/shop-ajax.php` (fixes ~70% of bugs)
2. **Second**: `assets/js/shop.js` (fixes ~20% of bugs)  
3. **Third**: `woocommerce/archive-product.php` (fixes ~10% of bugs)

All three files must be fixed for filters to work correctly!
