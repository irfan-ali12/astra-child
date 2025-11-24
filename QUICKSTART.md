# 🚀 KachoTech Theme - Quick Start Guide

## ⚡ 5-Minute Activation

### Step 1: Activate Theme (1 min)
```
WordPress Admin → Appearance → Themes
→ Find "Astra Child"
→ Click "Activate"
```

### Step 2: Set Homepage (1 min)
```
WordPress Admin → Settings → Reading
→ Set "Homepage displays as" → "Static page"
→ Select any page for "Homepage"
→ Click "Save Changes"
```

### Step 3: Create Products (2 min)
```
WordPress Admin → Products → Add New
Create at least 5-10 products with:
- Title
- Description
- Price
- Featured Image (300x300px minimum)
- Assign to Category (Heaters, Electronics, or Cosmetics)
- Set Stock
- Mark some as "Featured"
```

### Step 4: View Website (1 min)
```
Visit your website frontend
You should see:
✅ Custom header with search bar
✅ Hero carousel with 3 tabs
✅ Featured products section
✅ Footer with links
```

---

## ✅ Quick Verification Checklist

### Visual Elements
- [ ] Dark header with KachoTech logo
- [ ] Search bar with category dropdown
- [ ] Cart icon with badge
- [ ] Hero carousel with 3 category tabs
- [ ] Featured products grid
- [ ] Footer section

### Functionality
- [ ] Search bar shows product suggestions
- [ ] Category filter works
- [ ] Add to Cart buttons functional
- [ ] Cart count updates
- [ ] Mobile menu opens/closes
- [ ] All links clickable

### Browser Console (F12)
- [ ] No 404 errors
- [ ] No JavaScript errors

---

## 🎯 Common Tasks

### Add Product Categories
```
WordPress Admin → Products → Categories
Click "Add New Category"
- Name: (e.g., "Heaters")
- Slug: heaters
- Description: (optional)
- Click "Add New Category"
```

### Make Product Featured
```
WordPress Admin → Products → All Products
Click product to edit
Find: "Catalog" section on right
Check: "Featured Product"
Click "Update"
```

### Change Header Logo
```
WordPress Admin → Appearance → Customize
→ Site Identity
→ Logo
→ Upload/Select image (300x80px recommended)
```

### Change Colors
Edit `style.css` in theme folder:
```css
:root {
  --kt-primary: #ff2446;    /* Main brand color */
  --kt-dark: #151821;       /* Dark backgrounds */
}
```

---

## 🔧 File Locations

| File | Purpose |
|------|---------|
| `functions.php` | Main theme functions |
| `header.php` | HTML head & navigation |
| `footer.php` | HTML footer & closing |
| `home.php` | Homepage layout |
| `assets/css/header-custom.css` | Header styling |
| `assets/css/homepage.css` | Homepage styling |
| `assets/js/kt-ajax-search.js` | Search functionality |
| `template-parts/home/` | Homepage sections |

---

## ⚠️ Common Issues & Fixes

### ❌ Header Not Showing
**Solution**: 
- Clear cache (if using cache plugin)
- Check: Dashboard → Appearance → Check CSS file status
- Hard refresh browser (Ctrl+F5)

### ❌ Search Not Working
**Solution**:
- Create 5+ products first
- Check browser console for errors (F12)
- Verify products have correct category

### ❌ Add to Cart Not Working
**Solution**:
- Ensure WooCommerce is activated
- Set product stock quantity > 0
- Hard refresh browser cache

### ❌ Images Not Showing
**Solution**:
- Upload product images (300x300px minimum)
- Use "Set Featured Image" in product editor
- Clear browser cache

---

## 📱 Mobile Testing

### Test on Different Devices
- [ ] Desktop (1920px width)
- [ ] Tablet (768px width)
- [ ] Mobile (375px width)

### Mobile Menu
- Tap hamburger icon ☰
- Menu should slide from left
- Tap close button ✕ to close

### Mobile Search
- Search bar remains visible
- Suggestions dropdown appears below search
- Category dropdown works

---

## 🔗 Useful Links

| Link | Description |
|------|---|
| WordPress Admin | `/wp-admin/` |
| Website Frontend | `/` |
| Products Page | `/shop/` (if WooCommerce shop page is created) |
| Account Page | Account page link |

---

## 💡 Pro Tips

1. **Add More Products**: The theme looks best with 12+ products
2. **Use Quality Images**: 300x300px minimum for product images
3. **Write Descriptions**: Product descriptions appear in search results
4. **Set Stock**: Products show "In Stock" or "Out of Stock" badge
5. **Enable Payment**: Configure payment methods in WooCommerce settings

---

## 🎓 Complete Feature List

✅ Custom responsive header  
✅ Live product search with AJAX  
✅ Category filtering  
✅ Hero carousel (3 tabs)  
✅ Featured products section  
✅ Product category strip  
✅ Promotional banners  
✅ Trust/perks section  
✅ Mobile sidebar navigation  
✅ Sticky header animation  
✅ Add-to-cart functionality  
✅ Cart count badge  
✅ Real-time search suggestions  
✅ Responsive design  
✅ WooCommerce integration  

---

## 🚀 You're Ready!

Your KachoTech theme is now fully installed and ready to use.

**Next Step**: Create your first products and start selling! 🎉
