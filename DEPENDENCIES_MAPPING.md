# Archive Product Page - Dependencies & Modifications Map

## Current Archive Product Page Structure

### Main Template File:
```
woocommerce/archive-product.php (389 lines)
```

---

## Direct Dependencies (Files Attached to Archive Page)

### 1. **JavaScript Files** (Enqueued in archive-product.php)

#### ✅ **assets/js/shop.js** 
- **Status**: NEEDS MODIFICATION ✏️
- **Purpose**: Handles all filter logic, AJAX requests, and UI interactions
- **Enqueued at**: Line ~39 in archive-product.php
- **Issues to Fix**:
  - Missing `orderby` parameter in function and AJAX data
  - Price calculation formula
  - Filter state management

#### ✅ **assets/js/global-loader.js**
- **Status**: No changes needed
- **Purpose**: Global page loader for entire site
- **Enqueued at**: `inc/enqueue.php` line 97

---

### 2. **CSS Files** (Enqueued in archive-product.php)

#### ✅ **assets/css/shop-layout.css**
- **Status**: No changes needed
- **Purpose**: Shop page styling (layout, cards, sidebar, etc.)
- **Enqueued at**: Line ~38 in archive-product.php

#### ✅ **FontAwesome CDN**
- **Status**: No changes needed
- **Purpose**: Icons for search button, filters
- **Enqueued at**: Line ~59 in archive-product.php
- **URL**: `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css`

---

### 3. **Backend Handler File** (Processes AJAX)

#### ✅ **inc/shop-ajax.php**
- **Status**: NEEDS MODIFICATION ✏️
- **Purpose**: AJAX endpoint that processes filter parameters and returns filtered products
- **Registered Actions**:
  - `wp_ajax_kt_filter_products` (logged-in users)
  - `wp_ajax_nopriv_kt_filter_products` (guests)
- **Current Issues**:
  - Missing `orderby` parameter handling
  - Rating filter uses `continue` statement (doesn't actually filter)
  - Meta query structure is malformed
  - Pagination calculation incorrect
  - Stock status filter logic broken
  - Empty array handling

---

## Dependency Flow Diagram

```
woocommerce/archive-product.php (Main Template)
│
├─── FRONTEND DEPENDENCIES
│    │
│    ├─ assets/css/shop-layout.css
│    │   └─ Styles the entire shop page layout
│    │
│    ├─ assets/js/shop.js ⚠️ [NEEDS FIX]
│    │   ├─ Handles filter interactions (checkboxes, price slider)
│    │   ├─ Triggers AJAX calls
│    │   ├─ Processes server response
│    │   └─ Updates product grid and pagination
│    │
│    ├─ assets/js/global-loader.js
│    │   └─ Site-wide loader script
│    │
│    ├─ FontAwesome CDN
│    │   └─ Icons and styling
│    │
│    └─ WooCommerce Scripts (auto-enqueued)
│        ├─ wc-add-to-cart
│        ├─ jQuery
│        └─ jQuery-UI
│
└─── BACKEND DEPENDENCIES
     │
     └─ inc/shop-ajax.php ⚠️ [NEEDS FIX]
         └─ Receives AJAX data from shop.js
             ├─ Builds WP_Query with filters
             ├─ Filters products
             ├─ Generates HTML
             ├─ Calculates pagination
             └─ Returns JSON response
```

---

## Files That Need Modifications

### Priority 1 - CRITICAL (Must Fix)

#### **1. `inc/shop-ajax.php`** ⚠️ BACKEND HANDLER
**What to fix:**
- Add `orderby` parameter support
- Fix rating filter (use proper PHP filtering)
- Fix meta_query structure (stock availability)
- Correct pagination calculation
- Add proper array handling and defaults

**Lines to modify:** 
- Line 25: Add `$orderby` variable
- Lines 35-45: Restructure query args initialization
- Lines 47-105: Fix tax_query and meta_query building
- Lines 107-150: Fix rating filter implementation
- Lines 251-280: Fix pagination calculation

**Current Code Issues:**
```php
// WRONG - doesn't filter properly
if ( $min_rating > 0 && $rating < $min_rating ) {
    continue;  // ❌ This only skips rendering, doesn't filter
}

// WRONG - malformed meta_query
$meta_query[] = array_merge( $stock_query, array( 'relation' => 'OR' ) );

// WRONG - uses DB count instead of filtered count
$total_products = $query->found_posts;
```

---

#### **2. `assets/js/shop.js`** ⚠️ FRONTEND HANDLER
**What to fix:**
- Add orderby parameter to AJAX call
- Add orderby to currentFilters object
- Update price calculation logic
- Add orderby change event listener

**Lines to modify:**
- Line ~240: Add new sort change listener
- Line ~250-260: Update currentFilters object
- Line ~287: Add orderby to AJAX data
- Function signature: Add orderby parameter

**Current Code Issues:**
```javascript
// MISSING orderby in currentFilters
var currentFilters = {
    categories: '',
    availability: '',
    brands: '',
    min_rating: 0,
    max_price: 0,
    search: ''
    // ❌ Missing: orderby
};

// MISSING orderby in AJAX data
var filterData = {
    action: 'kt_filter_products',
    categories: currentFilters.categories,
    // ... other fields
    // ❌ Missing: orderby
    paged: page,
    nonce: ktShopAjax.nonce
};
```

---

#### **3. `woocommerce/archive-product.php`** ⚠️ TEMPLATE FILE
**What to fix:**
- Remove `onchange="this.form.submit()"` from sort dropdown
- Ensure proper default value for sort order

**Lines to modify:**
- Lines 104-113: Remove form submit on sort change (will use AJAX instead)

**Current Code Issues:**
```php
// PROBLEM - old way of handling sort
<select name="orderby" onchange="this.form.submit()">
    <!-- This page reloads instead of using AJAX -->
</select>
```

---

## Summary Table

| File | Location | Status | Issues | Modifications Needed |
|------|----------|--------|--------|----------------------|
| `shop.js` | `assets/js/` | 🔴 Critical | Missing orderby param, price calc bugs | Add orderby support to AJAX & filter state |
| `shop-ajax.php` | `inc/` | 🔴 Critical | Multiple query bugs, wrong filter logic | Restructure queries, fix pagination, add orderby |
| `archive-product.php` | `woocommerce/` | 🟡 Important | Form submit behavior | Remove onchange submit, keep AJAX |
| `shop-layout.css` | `assets/css/` | ✅ OK | None | No changes needed |
| `global-loader.js` | `assets/js/` | ✅ OK | None | No changes needed |
| `enqueue.php` | `inc/` | ✅ OK | None | No changes needed |
| `functions.php` | `astra-child/` | ✅ OK | None | No changes needed |

---

## Modification Checklist

### Phase 1: Backend Fixes (inc/shop-ajax.php)
- [ ] Add orderby parameter extraction
- [ ] Implement orderby switch statement for price/date/featured sorting
- [ ] Replace rating filter continue with proper array filtering
- [ ] Restructure meta_query for proper stock filtering
- [ ] Fix pagination using filtered count, not DB count
- [ ] Add proper array sanitization with array_filter()

### Phase 2: Frontend Updates (assets/js/shop.js)
- [ ] Add orderby to currentFilters object
- [ ] Add sort dropdown change listener
- [ ] Update applyFiltersAjax function signature to accept orderby
- [ ] Add orderby to filterData object
- [ ] Add orderby to AJAX POST data

### Phase 3: Template Updates (woocommerce/archive-product.php)
- [ ] Remove onchange="this.form.submit()" from sort dropdown
- [ ] Ensure default sort value is 'date'

---

## Testing Requirements After Modifications

- [ ] Sort by Featured → works via AJAX
- [ ] Sort by Latest → works via AJAX
- [ ] Sort by Price (Low to High) → works via AJAX
- [ ] Sort by Price (High to Low) → works via AJAX
- [ ] Sort persists during pagination
- [ ] Rating filter removes out-of-range products
- [ ] Stock filter shows correct products
- [ ] Price filter works correctly
- [ ] Category filter works correctly
- [ ] Multiple filters work together
- [ ] Pagination displays correct product count
- [ ] No page reload on sort change

---

## Architecture Notes

**Why files are separated:**
- `archive-product.php`: Template markup and initial page load
- `shop.js`: Client-side filter UI and AJAX coordination
- `shop-ajax.php`: Server-side logic for filtering and querying
- `shop-layout.css`: Styling for all shop components
- `inc/enqueue.php`: Central script/style registration

**AJAX Flow:**
1. User interacts with filters in browser
2. `shop.js` collects filter values
3. `shop.js` sends AJAX POST to `admin-ajax.php` with action `kt_filter_products`
4. WordPress routes to handler in `shop-ajax.php`
5. Handler queries database, applies filters
6. Handler returns JSON with HTML + pagination
7. `shop.js` updates DOM with response

---

## ⚡ Quick Start - Make These Changes First:

1. **`inc/shop-ajax.php`**: Add orderby parameter and fix query structure
2. **`assets/js/shop.js`**: Add orderby to AJAX call
3. **`woocommerce/archive-product.php`**: Remove onchange attribute

This will fix ~90% of your filter issues!
