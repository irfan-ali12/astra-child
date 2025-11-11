/**
 * KachoTech Custom Header - Search and Category Dropdown Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  const SITE_URL = window.location.origin;
  
  // WooCommerce Store API endpoint
  const WC_API_URL = SITE_URL + '/wp-json/wc/store/products';
  
  // Custom AJAX endpoint (fallback)
  const CUSTOM_AJAX_URL = window.location.href.includes('wp-admin') ? 
    ajaxurl || (SITE_URL + '/wp-admin/admin-ajax.php') :
    SITE_URL + '/wp-admin/admin-ajax.php';
  
  // CATEGORY DROPDOWN
  const catToggle = document.getElementById('kt-category-toggle');
  const catDropdown = document.getElementById('kt-category-dropdown');
  const catLabel = document.getElementById('kt-category-label');
  const catSlugInput = document.getElementById('kt-category-slug');

  if (catToggle && catDropdown) {
    catToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      catDropdown.style.display = (catDropdown.style.display === 'block') ? 'none' : 'block';
    });

    catDropdown.addEventListener('click', (e) => {
      const li = e.target.closest('li');
      if (!li) return;
      const slug = li.getAttribute('data-slug') || '';
      if (catSlugInput) catSlugInput.value = slug;
      if (catLabel) catLabel.textContent = li.textContent.trim();
      catDropdown.style.display = 'none';
    });

    document.addEventListener('click', (e) => {
      if (!catDropdown.contains(e.target) && !catToggle.contains(e.target)) {
        catDropdown.style.display = 'none';
      }
    });
  }

  // LIVE SEARCH (AJAX)
  const searchInput = document.getElementById('kt-search-input');
  const searchResults = document.getElementById('kt-search-results');
  const searchSubmit = document.getElementById('kt-search-submit');

  let searchTimer = null;

  function renderResults(items) {
    if (!searchResults) return;
    
    searchResults.innerHTML = '';
    if (!items || !items.length) {
      searchResults.innerHTML = '<div class="search-results-empty">No products found.</div>';
      searchResults.style.display = 'block';
      return;
    }

    items.forEach(p => {
      let price = '';
      let img = '';
      
      // Handle different API response formats
      if (p.prices && p.prices.price) {
        const priceNum = typeof p.prices.price === 'string' ? parseFloat(p.prices.price) : p.prices.price / 100;
        price = (p.prices.currency_symbol || 'Rs. ') + priceNum.toLocaleString();
      } else if (p.price) {
        const priceNum = typeof p.price === 'string' ? parseFloat(p.price) : p.price;
        price = 'Rs. ' + priceNum.toLocaleString();
      }
      
      if (p.images && p.images[0]) {
        img = p.images[0].src;
      } else if (p.image && p.image.src) {
        img = p.image.src;
      }

      const div = document.createElement('div');
      div.className = 'search-results-item';
      div.innerHTML = `
        ${img ? `<img src="${img}" alt="${p.name}">` : `<div style="width:32px;height:32px;background:#f3f4f6;border-radius:8px;"></div>`}
        <div>
          <div class="search-results-item-title">${p.name}</div>
          ${price ? `<div class="search-results-item-price">${price}</div>` : ''}
        </div>
      `;
      div.addEventListener('click', () => {
        const url = p.permalink || p.url || (SITE_URL + '/?p=' + p.id);
        window.location.href = url;
      });
      searchResults.appendChild(div);
    });
    searchResults.style.display = 'block';
  }

  function doSearch(q) {
    if (!searchResults || !q || q.length < 2) {
      if (searchResults) searchResults.style.display = 'none';
      return;
    }

    const cat = catSlugInput ? catSlugInput.value : '';
    const params = new URLSearchParams();
    params.append('search', q);
    params.append('per_page', '8');
    
    if (cat) {
      params.append('category', cat);
    }

    const endpoint = WC_API_URL + '?' + params.toString();
    
    fetch(endpoint)
      .then(r => {
        if (!r.ok) {
          throw new Error('API returned ' + r.status);
        }
        return r.json();
      })
      .then(data => {
        // Handle different response formats
        let items = [];
        
        if (Array.isArray(data)) {
          items = data;
        } else if (data.products && Array.isArray(data.products)) {
          items = data.products;
        } else if (data.data && Array.isArray(data.data)) {
          items = data.data;
        } else {
          console.error('Unexpected API response format:', data);
          throw new Error('Invalid response format');
        }
        
        renderResults(items);
      })
      .catch(err => {
        console.error('REST API error, trying custom AJAX:', err);
        
        // Fallback to custom AJAX endpoint
        const formData = new FormData();
        formData.append('action', 'kt_product_search');
        formData.append('search', q);
        formData.append('per_page', '8');
        if (cat) {
          formData.append('category', cat);
        }
        
        // Get nonce from localized data if available
        const nonce = typeof KT_AJAX !== 'undefined' && KT_AJAX.nonce ? KT_AJAX.nonce : '';
        if (nonce) {
          formData.append('nonce', nonce);
        }
        
        fetch(CUSTOM_AJAX_URL, {
          method: 'POST',
          body: formData,
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            renderResults(data.data);
          } else {
            throw new Error('AJAX error');
          }
        })
        .catch(ajaxErr => {
          console.error('AJAX fallback also failed:', ajaxErr);
          if (searchResults) {
            searchResults.innerHTML = '<div class="search-results-empty">Error loading results. Please try again.</div>';
            searchResults.style.display = 'block';
          }
        });
      });
  }

  if (searchInput) {
    searchInput.addEventListener('input', () => {
      const q = searchInput.value.trim();
      clearTimeout(searchTimer);
      searchTimer = setTimeout(() => doSearch(q), 300); // debounce
    });

    searchInput.addEventListener('focus', () => {
      if (searchResults && searchResults.innerHTML.trim()) {
        searchResults.style.display = 'block';
      }
    });
  }

  if (searchResults) {
    document.addEventListener('click', (e) => {
      if (!searchResults.contains(e.target) && !searchInput.contains(e.target)) {
        searchResults.style.display = 'none';
      }
    });
  }

  // Submit button: redirect to normal search page
  if (searchSubmit) {
    searchSubmit.addEventListener('click', () => {
      const q = searchInput.value.trim();
      const cat = catSlugInput ? catSlugInput.value : '';
      if (!q) return;
      const url = new URL(SITE_URL + '/?s=' + encodeURIComponent(q));
      if (cat) url.searchParams.set('product_cat', cat);
      window.location.href = url.toString();
    });
  }
});
