# 🎯 SIMPLE ANSWER: Which Files & Where to Modify

## Your Archive Product Page Uses These 3 Files:

### 📄 File 1: `woocommerce/archive-product.php` 
**Location:** `/wp-content/themes/astra-child/woocommerce/`
**Size:** 389 lines
**Purpose:** The main HTML page template

**What it loads:**
- CSS: `shop-layout.css`
- JS: `shop.js`
- Passes data to JavaScript: `ktShopAjax`, `ktPriceRange`

**What needs fixing:** 
```php
// Line 110 - REMOVE this:
<select name="orderby" onchange="this.form.submit()">

// Should be:
<select name="orderby">
```

**Why:** The `onchange` causes page reload. We want AJAX instead.

---

### 🔧 File 2: `assets/js/shop.js`
**Location:** `/wp-content/themes/astra-child/assets/js/`
**Size:** 374 lines
**Purpose:** Handles all filter interactions and AJAX calls

**What it does:**
- Listens for filter changes (checkboxes, sliders, buttons)
- Collects filter values
- Sends AJAX request to server
- Updates page with results

**What needs fixing:**
```javascript
// Around line 240 - ADD this:
$(document).on('change', '.kt-sort-form select[name="orderby"]', function() {
    var orderby = $(this).val();
    applyFiltersAjax(currentFilters.search || '', 1, orderby);
});

// Around line 250-260 - ADD orderby to this object:
var currentFilters = {
    categories: '',
    availability: '',
    brands: '',
    min_rating: 0,
    max_price: 0,
    search: '',
    orderby: 'date'  // ← ADD THIS LINE
};

// Around line 287 - ADD orderby to this object:
var filterData = {
    action: 'kt_filter_products',
    categories: currentFilters.categories,
    availability: currentFilters.availability,
    brands: currentFilters.brands,
    min_rating: currentFilters.min_rating,
    max_price: currentFilters.max_price,
    search: currentFilters.search,
    orderby: currentFilters.orderby,  // ← ADD THIS LINE
    paged: page,
    nonce: ktShopAjax.nonce
};
```

**Why:** Server needs to know what sort order user selected.

---

### ⚙️ File 3: `inc/shop-ajax.php`
**Location:** `/wp-content/themes/astra-child/inc/`
**Size:** 283 lines
**Purpose:** Processes filter requests and returns filtered products

**What it does:**
1. Receives filter parameters from AJAX
2. Builds database query
3. Filters products
4. Returns HTML + pagination

**What needs fixing (This file has most bugs):**

```php
// Line 25 - ADD this:
$orderby = isset( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'date';

// Lines 35-45 - CHANGE hardcoded orderby:
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => intval( get_option( 'posts_per_page', 12 ) ),
    'paged'          => $paged,
    'orderby'        => 'date',  // ← WILL BE REPLACED BY SWITCH
    'order'          => 'DESC',
    'meta_query'     => array(),
    'tax_query'      => array( 'relation' => 'AND' ),  // ← ADD THIS
);

// After line 45 - ADD orderby handling:
switch ( $orderby ) {
    case 'price':
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = '_price';
        $args['order'] = 'ASC';
        break;
    case 'price-desc':
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = '_price';
        $args['order'] = 'DESC';
        break;
    case 'date':
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
        break;
    case 'menu_order':
        $args['orderby'] = 'menu_order';
        $args['order'] = 'ASC';
        break;
    default:
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
}

// Line 29-31 - ADD array filtering:
$cat_array = ! empty( $categories ) ? array_filter( array_map( 'sanitize_text_field', explode( ',', $categories ) ) ) : array();
$availability_array = ! empty( $availability ) ? array_filter( array_map( 'sanitize_text_field', explode( ',', $availability ) ) ) : array();
$brands_array = ! empty( $brands ) ? array_filter( array_map( 'sanitize_text_field', explode( ',', $brands ) ) ) : array();

// Lines 82-106 - FIX the stock filter (this is very broken):
if ( ! empty( $availability_array ) ) {
    $stock_clauses = array( 'relation' => 'OR' );
    foreach ( $availability_array as $status ) {
        if ( $status === 'in-stock' ) {
            $stock_clauses[] = array(
                'key'   => '_stock_status',
                'value' => 'instock',
            );
        } elseif ( $status === 'out-of-stock' ) {
            $stock_clauses[] = array(
                'key'   => '_stock_status',
                'value' => 'outofstock',
            );
        }
    }
    if ( count( $stock_clauses ) > 1 ) {
        $args['meta_query'][] = $stock_clauses;
    }
}

// Lines 135-140 - FIX rating filter (currently broken):
// REMOVE this broken code:
if ( $min_rating > 0 && $rating < $min_rating ) {
    continue;
}

// And add this after the query:
$filtered_posts = $query->posts;
if ( $min_rating > 0 ) {
    $filtered_posts = array_filter( $filtered_posts, function( $post ) use ( $min_rating ) {
        $product = wc_get_product( $post->ID );
        return $product && $product->get_average_rating() >= $min_rating;
    });
    $query->found_posts = count( $filtered_posts );
    $query->posts = $filtered_posts;
}

// Lines 250 - FIX pagination count:
// Change FROM:
$total_products = $query->found_posts;

// Change TO:
$total_products = count( $filtered_posts );
```

**Why:** This file builds database queries. It needs to:
- Accept the orderby parameter
- Support different sorting methods
- Actually filter products (not just hide them)
- Return correct pagination counts

---

## Quick Summary Table

| File | Problem | Fix | Lines |
|------|---------|-----|-------|
| `archive-product.php` | Sort reloads page | Remove `onchange="form.submit()"` | 110 |
| `shop.js` | Missing orderby data | Add orderby listener & to objects | 240, 250, 287 |
| `shop-ajax.php` | Multiple query bugs | Fix queries & add orderby | 25, 39, 50-90, 130-140, 250 |

---

## Files NOT in Your Template (But Still Used)

These are **NOT directly attached** to archive-product.php, but the page uses them:

### Shared Files:
- `inc/enqueue.php` - Registers theme scripts globally ✅ No changes needed
- `inc/functions.php` - Theme initialization ✅ No changes needed

### External:
- WooCommerce plugin - Provides product functions ✅ No changes needed
- WordPress core - Provides AJAX routing ✅ No changes needed
- FontAwesome CDN - Icons for search ✅ No changes needed

---

## Complete File Map

```
Your Shop Page Depends On:

archive-product.php (NEEDS SMALL FIX)
    ├─ Enqueues: shop-layout.css (no changes)
    ├─ Enqueues: shop.js (NEEDS UPDATE)
    ├─ Uses PHP: WooCommerce functions
    └─ Calls AJAX to: shop-ajax.php (NEEDS MAJOR UPDATE)

shop.js (NEEDS UPDATE)
    └─ Sends AJAX POST to: shop-ajax.php

shop-ajax.php (NEEDS MAJOR UPDATE)
    ├─ Receives data from: shop.js
    ├─ Uses: WordPress AJAX
    ├─ Uses: WooCommerce functions
    └─ Returns JSON to: shop.js

shop-layout.css (NO CHANGES)
    └─ Styles: archive-product.php markup
```

---

## The 3-Step Fix

### Step 1: Fix Backend (shop-ajax.php) ⚙️
- Add orderby parameter
- Fix query structure
- Fix rating filter
- Fix pagination

### Step 2: Fix Frontend (shop.js) 🔧
- Add sort listener
- Add orderby to filter state
- Pass orderby to AJAX

### Step 3: Fix Template (archive-product.php) 🎨
- Remove page reload behavior

---

## Testing After Fixes

After you make these changes, test:
- [ ] Sort by Featured works
- [ ] Sort by Latest works
- [ ] Sort by Price works
- [ ] Rating filter actually removes products
- [ ] Pagination shows correct count
- [ ] All filters work together
- [ ] No page reload on any interaction

---

## File Locations for Copy/Paste

```
📁 astra-child/
├── 📄 woocommerce/archive-product.php (LINE 110)
├── 📁 assets/
│   └── 📁 js/
│       └── 📄 shop.js (LINES 240, 250, 287)
└── 📁 inc/
    └── 📄 shop-ajax.php (LINES 25, 39, 50-90, 130-140, 250)
```

---

## ✨ That's It!

Fix these 3 files in these locations and your filters will work perfectly!
