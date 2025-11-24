# Archive Product Page - Complete Dependency Map

## TL;DR - The 3 Files You Need to Know

| # | File | Type | Purpose | Issues |
|---|------|------|---------|--------|
| 1️⃣ | `woocommerce/archive-product.php` | Template | Initial page structure, enqueues CSS/JS | Sort form submits page instead of AJAX |
| 2️⃣ | `assets/js/shop.js` | JavaScript | Handles filter UI interactions, sends AJAX | Missing orderby parameter |
| 3️⃣ | `inc/shop-ajax.php` | PHP Handler | Processes AJAX requests, returns filtered products | Multiple query bugs, rating filter broken |

---

## File-by-File Breakdown

### 1️⃣ woocommerce/archive-product.php (389 lines)

**Role:** The HTML template for the shop page

**What it contains:**
- Page header with title and breadcrumb
- Sort/filter bar with dropdown
- Sidebar with category/brand/rating/price/availability filters
- Mobile filter drawer
- Product grid
- Pagination

**How it enqueues JavaScript:**
```php
// Lines 38-39: Load shop styles
wp_enqueue_style( 'kt-shop-layout', get_stylesheet_directory_uri() . '/assets/css/shop-layout.css', ... );

// Lines 40-41: Load shop JavaScript
wp_enqueue_script( 'kt-shop-js', get_stylesheet_directory_uri() . '/assets/js/shop.js', array( 'jquery' ), '1.0', true );

// Lines 43-48: Pass PHP data to JavaScript
wp_localize_script( 'kt-shop-js', 'ktShopAjax', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
    'nonce'   => wp_create_nonce( 'kt_filter_nonce' ),
) );

// Lines 50-54: Pass price data to JavaScript
wp_localize_script( 'kt-shop-js', 'ktPriceRange', array(
    'min' => (int) $min_price,
    'max' => (int) $max_price,
) );
```

**Issues:**
- Line 110: Sort dropdown has `onchange="this.form.submit()"` → causes page reload
- Should use AJAX instead

**Dependencies on this file:**
- WooCommerce global functions
- Global `$wp_query` object
- Product taxonomies (product_cat, pa_brand)

---

### 2️⃣ assets/js/shop.js (374 lines)

**Role:** Client-side filter logic and AJAX coordinator

**What it does:**
```javascript
// Lines 5-25: Mobile drawer toggle
$(document).on('click', '#kt-open-filters', function() { ... });

// Lines 28-30: Brand pill toggle
$(document).on('click', '.kt-pill', function() {
    $(this).toggleClass('kt-pill-active');
    applyFiltersAjax();
});

// Lines 33-35: Rating filter
$(document).on('change', 'input[name="rating"]', function() {
    applyFiltersAjax();
});

// Lines 38-40: Category filter
$(document).on('change', '.kt-category-filter', function() {
    applyFiltersAjax();
});

// Lines 43-45: Availability filter
$(document).on('change', '.kt-availability-filter', function() {
    applyFiltersAjax();
});

// Lines 48-127: Price range slider with tooltip
$priceRange.on('input', function() { ... });
$priceRange.on('change', function() { ... });

// Lines 130-132: Apply filters button
$(document).on('click', '#kt-apply-filters', function() {
    applyFiltersAjax();
});

// Lines 135-167: Clear filters button
$(document).on('click', '#kt-clear-filters', function() {
    // Reset all filters and reload
});

// Lines 170-195: Search functionality
$(document).on('keyup', '#kt-product-search', function() { ... });
$(document).on('click', '.kt-search-btn', function() { ... });

// Lines 198-237: Add to cart AJAX
$(document).on('click', '.ajax_add_to_cart', function() { ... });

// Lines 240-371: MAIN FILTER FUNCTION
var currentFilters = { ... };
function applyFiltersAjax(search, page, orderby) {
    // 1. Collect filter values
    // 2. Build filterData object
    // 3. Send AJAX POST to shop-ajax.php
    // 4. Receive JSON response
    // 5. Update DOM with results
    // 6. Update pagination
}

// Lines 313-341: Pagination link handler
$(document).on('click', '.kt-page-link', function() { ... });
```

**Issues:**
- Missing `orderby` parameter in multiple places
- `currentFilters` object doesn't track sort order
- `filterData` doesn't include `orderby` when sending to server
- No listener for sort dropdown change

**Sends to:** `inc/shop-ajax.php` via AJAX action `kt_filter_products`

**Receives from:** `inc/shop-ajax.php` (JSON response with products HTML + pagination)

---

### 3️⃣ inc/shop-ajax.php (283 lines)

**Role:** AJAX endpoint that filters and returns products

**What it does:**

```php
// Lines 12-26: Receive and sanitize filter parameters
$categories = isset( $_POST['categories'] ) ? ... : '';
$brands = isset( $_POST['brands'] ) ? ... : '';
$min_rating = isset( $_POST['min_rating'] ) ? ... : 0;
$max_price = isset( $_POST['max_price'] ) ? ... : '';
$search = isset( $_POST['search'] ) ? ... : '';
$paged = isset( $_POST['paged'] ) ? ... : 1;
// MISSING: $orderby parameter

// Lines 29-31: Parse comma-separated values
$cat_array = explode( ',', $categories );
$brands_array = explode( ',', $brands );
$availability_array = explode( ',', $availability );

// Lines 34-39: Build WP_Query args
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => get_option( 'posts_per_page' ),
    'paged'          => $paged,
    'orderby'        => 'date',  // HARDCODED - not flexible
    'order'          => 'DESC',
);

// Lines 44-45: Add search
if ( ! empty( $search ) ) {
    $args['s'] = $search;
}

// Lines 48-72: Build tax_query for categories and brands
$tax_query = array();
if ( ! empty( $cat_array ) ) {
    $tax_query[] = array(
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => $cat_array,
        'operator' => 'IN',
    );
}
if ( ! empty( $brands_array ) ) {
    $tax_query[] = array(
        'taxonomy' => 'pa_brand',
        'field'    => 'slug',
        'terms'    => $brands_array,
        'operator' => 'IN',
    );
}

// Lines 74-80: Build meta_query for price
$meta_query = array();
if ( ! empty( $max_price ) ) {
    $meta_query[] = array(
        'key'     => '_price',
        'value'   => (float) $max_price,
        'compare' => '<=',
        'type'    => 'NUMERIC',
    );
}

// Lines 82-106: Build meta_query for stock status (BROKEN)
if ( ! empty( $availability_array ) ) {
    $stock_query = array();
    foreach ( $availability_array as $status ) {
        if ( $status === 'in-stock' ) {
            $stock_query[] = array(
                'key'   => '_stock_status',
                'value' => 'instock',
            );
        }
    }
    if ( ! empty( $stock_query ) ) {
        // WRONG: Malformed meta_query structure
        $meta_query[] = array_merge( $stock_query, array( 'relation' => 'OR' ) );
    }
}

// Lines 115-150: Query and render products
$query = new WP_Query( $args );
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
        global $product;
        
        // Get product data
        $rating = $product->get_average_rating();
        
        // WRONG: continue doesn't filter, just skips rendering
        if ( $min_rating > 0 && $rating < $min_rating ) {
            continue;
        }
        
        // Render product card HTML
        ...
    }
}

// Lines 250-275: Calculate pagination (WRONG)
$total_products = $query->found_posts;  // WRONG: DB count, not filtered
$per_page = get_option( 'posts_per_page' );
$max_pages = ceil( $total_products / $per_page );

// Lines 276-280: Return JSON
wp_send_json_success( array(
    'html' => $html,
    'pagination' => $pagination_html,
    'total_products' => $total_products,
    'total_pages' => $max_pages,
    'current_page' => $paged,
) );

// Lines 281-282: Register AJAX actions
add_action( 'wp_ajax_kt_filter_products', 'kt_filter_products_ajax' );
add_action( 'wp_ajax_nopriv_kt_filter_products', 'kt_filter_products_ajax' );
```

**Issues:**
- Line 25: Missing `$orderby` extraction
- Line 39: `'orderby' => 'date'` hardcoded instead of using parameter
- Lines 48-106: `meta_query` structure is malformed (especially stock)
- Lines 163-167: Rating filter uses `continue` (doesn't actually filter)
- Lines 250: Uses `$query->found_posts` instead of count of filtered posts
- Array handling doesn't use `array_filter()`

**Receives from:** `assets/js/shop.js` via AJAX POST

**Returns to:** `assets/js/shop.js` as JSON

---

## Data Flow Path

```
User does: Click category checkbox
    ↓
JavaScript detects event
    └─→ shop.js line 38-40 listener triggered
    ↓
JavaScript collects filter values
    └─→ shop.js lines 262-280 (applyFiltersAjax function)
    ├─ categories: ['electronics']
    ├─ brands: ['kachotech']
    ├─ availability: ['in-stock']
    ├─ min_rating: 4
    ├─ max_price: 5000
    ├─ search: 'heater'
    ├─ paged: 1
    └─ orderby: ??? (MISSING)
    ↓
JavaScript sends AJAX POST
    └─→ shop.js line 284-302
    POST /wp-admin/admin-ajax.php
    Data: {
        action: 'kt_filter_products',
        categories: 'electronics',
        brands: 'kachotech',
        ... other filters ...,
        orderby: ??? MISSING,
        nonce: '...'
    }
    ↓
WordPress receives request
    └─→ Routes to shop-ajax.php function
    ↓
shop-ajax.php extracts parameters
    └─→ Lines 18-26
    PHP receives:
    - $_POST['categories']
    - $_POST['brands']
    - etc.
    - $_POST['orderby'] ??? MISSING
    ↓
shop-ajax.php builds WP_Query
    └─→ Lines 34-125
    Creates query with:
    - tax_query (categories, brands)
    - meta_query (price, stock)
    - orderby (hardcoded 'date')
    ↓
shop-ajax.php executes query
    └─→ Line 127
    Database returns matching products
    ↓
shop-ajax.php applies rating filter
    └─→ Lines 135-140 (WRONG - uses continue)
    ↓
shop-ajax.php renders HTML
    └─→ Lines 142-240
    Generates product card markup
    ↓
shop-ajax.php calculates pagination
    └─→ Lines 249-265 (WRONG - uses DB count)
    ↓
shop-ajax.php sends JSON response
    └─→ Line 280-287
    Returns:
    {
        success: true,
        data: {
            html: "...",
            pagination: "...",
            total_products: 12
        }
    }
    ↓
JavaScript receives response
    └─→ shop.js line 305-325 (success callback)
    ↓
JavaScript updates DOM
    └─→ shop.js lines 307-340
    - Replace product HTML
    - Replace pagination HTML
    - Update product count
    ↓
User sees filtered results
```

---

## Dependencies Diagram (Extended)

```
archive-product.php
├── Enqueues CSS
│   └── shop-layout.css (styling)
│
├── Enqueues JavaScript
│   └── shop.js (filter logic)
│
├── Localizes Data to JavaScript
│   ├── ktShopAjax.ajaxurl
│   ├── ktShopAjax.nonce
│   ├── ktPriceRange.min
│   └── ktPriceRange.max
│
├── Includes PHP Logic
│   └── WooCommerce functions
│       ├── woocommerce_page_title()
│       ├── get_terms() for categories
│       ├── wc_get_product()
│       └── paginate_links()
│
└── Initial Page Data
    ├── Global $wp_query
    ├── Global $product
    └── Hardcoded filter UI

shop.js (Frontend)
├── Listens for filter changes
├── Builds filter object
├── Sends AJAX to WordPress
├── Processes response JSON
├── Updates product grid
└── Updates pagination

shop-ajax.php (Backend)
├── Receives AJAX request
├── Sanitizes parameters
├── Queries database
├── Filters results
├── Renders HTML
├── Calculates pagination
└── Returns JSON

Additional Dependencies
├── jQuery (for event handling)
├── WooCommerce (for product functions)
├── WordPress (for AJAX routing)
└── FontAwesome (for icons)
```

---

## Summary: What Files Do What

| Component | File | Responsibility |
|-----------|------|-----------------|
| **Template** | `archive-product.php` | Display initial page structure, enqueue assets |
| **Styling** | `shop-layout.css` | Style all shop page elements |
| **Frontend Logic** | `shop.js` | Listen to user interactions, send AJAX |
| **Backend Logic** | `shop-ajax.php` | Process filters, query database, return results |
| **Icons** | FontAwesome CDN | Provide search and filter icons |
| **Framework** | WordPress AJAX | Route requests between frontend and backend |
| **E-commerce** | WooCommerce | Provide product functions and data |

---

## What Needs to Change (Exact Locations)

### In `inc/shop-ajax.php`:
- Add `$orderby` extraction (line 25)
- Use `$orderby` in query instead of hardcoding (line 39)
- Fix meta_query structure for stock (lines 97-106)
- Replace rating filter continue with array_filter (lines 135-140)
- Fix pagination count (line 250)

### In `assets/js/shop.js`:
- Add sort dropdown listener (after line 237)
- Add `orderby` to `currentFilters` (line 250)
- Add `orderby` to `filterData` (line 287)

### In `woocommerce/archive-product.php`:
- Remove `onchange="this.form.submit()"` (line 110)

---

## ✨ Key Takeaway

Your archive product page depends on **3 main files**:
1. **Template** (`archive-product.php`) - Sets up the page
2. **Frontend** (`shop.js`) - Handles user clicks
3. **Backend** (`shop-ajax.php`) - Processes and filters

All three must work together. Fixing one file alone won't solve the issues!
