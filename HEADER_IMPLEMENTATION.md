# KachoTech Custom Header - Implementation Guide

## Overview
This implementation integrates a custom, modern header design into your Astra child theme with full WooCommerce support. The design includes:

- **Top Bar**: Promotional banner with contact info
- **Header**: Logo, live search with category filter, and header actions
- **Navigation**: Dynamic product category links and USP highlights
- **Search**: AJAX-powered live product search with category filtering

## Files Created/Modified

### CSS File
**Location:** `assets/css/header-custom.css`
- Complete styling for all header components
- CSS Variables for easy customization
- Fully responsive design (mobile, tablet, desktop)
- Smooth transitions and hover effects

### JavaScript File
**Location:** `assets/js/header-custom.js`
- Category dropdown toggle functionality
- Live AJAX search with debouncing (300ms)
- WooCommerce Store API integration
- Search result rendering
- Category-based filtering

### PHP Template
**Location:** `template-parts/header/header-main.php`
- WordPress integration with proper escaping
- WooCommerce cart integration
- Dynamic product category fetching
- Custom logo support
- Proper security practices (nonces, sanitization)

### Enqueue File
**Location:** `inc/enqueue.php`
- Proper CSS/JS enqueuing with dependencies
- Google Fonts (Poppins) integration
- RemixIcon library loading
- Script localization for AJAX

## Features

### 1. Live Search
- Searches products in real-time as you type
- 300ms debounce to optimize performance
- Category-based filtering
- Shows product images and prices
- Directly links to product pages

### 2. Category Dropdown
- 6 default categories with custom icons
- Easy to customize via the template
- Smooth open/close animations
- Persists selected category in search

### 3. WooCommerce Integration
- Real-time cart count display
- My Account link (shows "My Account" if logged in, "Login/Register" if not)
- Dynamic category links in navigation
- Compatible with WooCommerce Store API

### 4. Responsive Design
- Mobile-first approach
- Tablet optimizations
- Desktop enhancements
- Touch-friendly interactions

## Customization

### Change Brand Colors
Edit `assets/css/header-custom.css` and modify the CSS variables:

```css
:root {
  --kt-primary: #ff2446;              /* Primary red */
  --kt-primary-soft: rgba(255,36,70,0.10); /* Light red */
  --kt-dark: #151821;                 /* Dark text */
  --kt-text: #252732;                 /* Regular text */
  --kt-muted: #6b7280;                /* Muted text */
  --kt-soft: #f9fafc;                 /* Light background */
  --kt-border: rgba(0,0,0,0.12);     /* Border color */
}
```

### Update Promotional Text
Edit `template-parts/header/header-main.php` and modify:

```php
<span class="top-pill"><?php _e( 'KACHOTECH WINTER FEST', 'astra-child' ); ?></span>
<span><?php _e( 'Heaters, electronics & cosmetics delivered nationwide.', 'astra-child' ); ?></span>
<div><?php _e( 'WhatsApp: 03XX-XXXXXXX • Cash on Delivery Available', 'astra-child' ); ?></div>
```

### Modify Categories
Edit the category list in `template-parts/header/header-main.php`:

```php
<li data-slug="your-category-slug">
  <i class="ri-icon-name"></i> Your Category Name
</li>
```

View RemixIcon library at: https://remixicon.com/

### Change Logo
1. Upload your logo to `assets/images/kacho-tech-logo-large-size.png`
2. Or use WordPress Custom Logo feature (Customize > Site Identity > Logo)

## API Integration

### WooCommerce Store API
The search uses the WooCommerce REST API endpoint:

```
/wp-json/wc/store/products
```

**Supported Filters:**
- `search` - Product name/description search
- `per_page` - Results per page (default: 8)
- `category` - Filter by category slug (optional)

### Example API Call
```javascript
fetch('/wp-json/wc/store/products?search=heater&per_page=8&category=heaters')
  .then(r => r.json())
  .then(data => console.log(data))
```

## Browser Support

- Chrome (Latest)
- Firefox (Latest)
- Safari (Latest)
- Edge (Latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Performance Optimizations

1. **Search Debouncing**: 300ms delay prevents excessive API calls
2. **CSS Variables**: Efficient color management
3. **Minimal Dependencies**: No jQuery required (vanilla JS)
4. **Optimized Icons**: RemixIcon (CDN-hosted)
5. **Lazy Loading**: Search results only load on interaction

## Troubleshooting

### Search Not Working
1. Ensure WooCommerce plugin is active
2. Check that WooCommerce REST API is enabled
3. Verify `/wp-json/` endpoint is accessible

### Styling Issues
1. Clear browser cache (Ctrl+Shift+Del)
2. Check for conflicting CSS in other plugins
3. Use Chrome DevTools to inspect elements

### Cart Count Not Updating
1. Ensure WooCommerce is properly initialized
2. Check WC()->cart is available in your theme
3. Verify AJAX cart updates are enabled in WooCommerce

## WooCommerce Hooks

The template uses standard WooCommerce functions:
- `WC()->cart->get_cart_contents_count()` - Cart item count
- `wc_get_page_permalink( 'myaccount' )` - My Account URL
- `wc_get_cart_url()` - Cart page URL
- `is_user_logged_in()` - Check if user is logged in

## Translation Ready

All strings are properly wrapped in `_e()`, `__()` for WordPress translation support:

```php
<?php _e( 'My Account', 'astra-child' ); ?>
```

Generate `.pot` file using WordPress translation tools to make this translatable.

## Mobile Optimization

- Header scales appropriately on mobile
- Top bar becomes vertical on small screens
- Navigation strip hides on tablets/mobile for space
- Touch-friendly buttons and tap targets
- Optimized search bar for mobile input

## Security

All output is properly escaped:
- `esc_url()` for URLs
- `esc_html()` for text content
- `esc_attr()` for attributes
- WordPress nonces for AJAX operations
- Input sanitization for search queries

## Support & Updates

For issues or questions:
1. Check WooCommerce documentation: https://docs.woocommerce.com/
2. Review Astra theme docs: https://www.asthemes.com/
3. Test in a staging environment first
4. Verify all plugins are updated

---

**Version:** 1.0.0  
**Last Updated:** November 2025  
**Compatibility:** Astra Theme + WooCommerce 5.5+
