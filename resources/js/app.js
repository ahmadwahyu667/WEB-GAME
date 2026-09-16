/* =================================================================
   GameMarket Indonesia — Main JavaScript
   ================================================================= */

import './bootstrap';

// ---- Scroll Reveal (IntersectionObserver) ----
function initScrollReveal() {
    const reveals = document.querySelectorAll('.reveal');
    if (!reveals.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    reveals.forEach(el => observer.observe(el));
}

// ---- Navbar Scroll Effect ----
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    function updateNavbar() {
        if (window.scrollY > 50) {
            navbar.classList.remove('navbar--transparent');
            navbar.classList.add('navbar--solid');
        } else {
            navbar.classList.add('navbar--transparent');
            navbar.classList.remove('navbar--solid');
        }
    }

    updateNavbar();
    window.addEventListener('scroll', updateNavbar, { passive: true });

    // Mobile menu toggle
    const toggle = document.querySelector('.navbar__toggle');
    const menu = document.querySelector('.navbar__menu');
    const backdrop = document.querySelector('.navbar__menu-backdrop');

    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            menu.classList.toggle('active');
            backdrop?.classList.toggle('active');
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
        });

        backdrop?.addEventListener('click', () => {
            menu.classList.remove('active');
            backdrop.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
}

// ---- Toast Notifications ----
window.showToast = function(message, type = 'success', duration = 3000) {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ'
    };

    toast.innerHTML = `<span>${icons[type] || ''}</span><span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('toast-exit');
        setTimeout(() => toast.remove(), 300);
    }, duration);
};

// ---- Banner Carousel ----
function initBannerCarousel() {
    const slides = document.querySelectorAll('.banner-slide');
    const dots = document.querySelectorAll('.banner-dot');
    if (!slides.length) return;

    let current = 0;
    let interval;

    function showSlide(index) {
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));
        slides[index]?.classList.add('active');
        dots[index]?.classList.add('active');
        current = index;
    }

    function nextSlide() {
        showSlide((current + 1) % slides.length);
    }

    function startAutoplay() {
        interval = setInterval(nextSlide, 5000);
    }

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            clearInterval(interval);
            showSlide(i);
            startAutoplay();
        });
    });

    showSlide(0);
    startAutoplay();
}

// ---- Quantity Selector ----
function initQuantitySelectors() {
    document.querySelectorAll('.qty-selector').forEach(selector => {
        const input = selector.querySelector('input');
        const minusBtn = selector.querySelector('[data-qty="minus"]');
        const plusBtn = selector.querySelector('[data-qty="plus"]');
        const max = parseInt(input?.getAttribute('max') || '99');

        minusBtn?.addEventListener('click', () => {
            let val = parseInt(input.value) || 1;
            if (val > 1) {
                input.value = val - 1;
                input.dispatchEvent(new Event('change'));
            }
        });

        plusBtn?.addEventListener('click', () => {
            let val = parseInt(input.value) || 1;
            if (val < max) {
                input.value = val + 1;
                input.dispatchEvent(new Event('change'));
            }
        });

        input?.addEventListener('change', () => {
            let val = parseInt(input.value) || 1;
            val = Math.max(1, Math.min(val, max));
            input.value = val;
        });
    });
}

// ---- Product Tabs ----
function initProductTabs() {
    document.querySelectorAll('.product-tabs__btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.tab;
            const parent = btn.closest('.product-tabs');

            parent.querySelectorAll('.product-tabs__btn').forEach(b => b.classList.remove('active'));
            parent.querySelectorAll('.product-tabs__content').forEach(c => c.classList.remove('active'));

            btn.classList.add('active');
            parent.querySelector(`[data-tab-content="${target}"]`)?.classList.add('active');
        });
    });
}

// ---- Tab Filters (Popular Products) ----
function initTabFilters() {
    document.querySelectorAll('[data-tab-filter]').forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            const filter = tab.dataset.tabFilter;
            const parent = tab.closest('.section');

            parent.querySelectorAll('[data-tab-filter]').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const cards = parent.querySelectorAll('[data-game]');
            cards.forEach(card => {
                if (filter === 'all' || card.dataset.game === filter) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
}

// ---- Cart AJAX ----
window.addToCart = async function(arg1, arg2 = 1) {
    let productId = null;
    let quantity = 1;
    let triggerBtn = null;

    // Support both direct addToCart(productId, quantity) and form submit onsubmit="addToCart(event, this)"
    if (arg1 && (arg1.preventDefault || arg1 instanceof Event || (arg2 && arg2.tagName === 'FORM'))) {
        if (arg1.preventDefault) {
            arg1.preventDefault();
        }
        const form = (arg2 && arg2.tagName === 'FORM') ? arg2 : arg1.target;
        const formData = new FormData(form);
        productId = parseInt(formData.get('product_id'));
        quantity = parseInt(formData.get('quantity') || 1);
        triggerBtn = form.querySelector('button[type="submit"]');
    } else {
        productId = parseInt(arg1);
        quantity = typeof arg2 === 'number' ? arg2 : 1;
    }

    if (!productId || isNaN(productId)) {
        console.error('Invalid product ID for addToCart');
        return;
    }

    if (quantity < 1 || isNaN(quantity)) {
        quantity = 1;
    }

    let originalBtnContent = null;
    if (triggerBtn) {
        originalBtnContent = triggerBtn.innerHTML;
        triggerBtn.innerHTML = '...';
        triggerBtn.disabled = true;
    }

    try {
        const response = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity })
        });

        if (response.status === 401) {
            showToast('Silakan login terlebih dahulu untuk menambah produk ke keranjang.', 'info');
            return { success: false, unauthenticated: true };
        }

        const data = await response.json();

        if (data.success) {
            showToast(data.message || 'Produk ditambahkan ke keranjang!', 'success');
            if (data.cart_count !== undefined) {
                updateCartCount(data.cart_count);
            }
        } else {
            showToast(data.message || 'Gagal menambahkan ke keranjang.', 'error');
        }

        return data;
    } catch (err) {
        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
        return { success: false };
    } finally {
        if (triggerBtn && originalBtnContent !== null) {
            triggerBtn.innerHTML = originalBtnContent;
            triggerBtn.disabled = false;
        }
    }
};

function updateCartCount(count) {
    const badge = document.querySelector('.navbar__cart-count');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

// ---- Wishlist Toggle ----
window.toggleWishlist = async function(productId, btn) {
    try {
        const response = await fetch(`/wishlist/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();

        if (data.success) {
            btn?.classList.toggle('active');
            showToast(data.message, 'success');
        }
    } catch (err) {
        showToast('Terjadi kesalahan.', 'error');
    }
};

// ---- Search Debounce ----
function initSearch() {
    const searchInput = document.querySelector('.navbar__search-input');
    if (!searchInput) return;

    let debounceTimer;

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const query = searchInput.value.trim();
            if (query.length >= 2) {
                window.location.href = `/products?q=${encodeURIComponent(query)}`;
            }
        }, 500);
    });

    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            clearTimeout(debounceTimer);
            const query = searchInput.value.trim();
            if (query) {
                window.location.href = `/products?q=${encodeURIComponent(query)}`;
            }
        }
    });
}

// ---- Particles ----
function initParticles() {
    const container = document.querySelector('.particles');
    if (!container) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    for (let i = 0; i < 15; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDuration = (Math.random() * 10 + 8) + 's';
        particle.style.animationDelay = (Math.random() * 8) + 's';
        particle.style.width = (Math.random() * 3 + 1) + 'px';
        particle.style.height = particle.style.width;
        container.appendChild(particle);
    }
}

// ---- Countdown Timer ----
window.initCountdown = function(elementId, expiresAt) {
    const el = document.getElementById(elementId);
    if (!el) return;

    const expiry = new Date(expiresAt).getTime();

    function update() {
        const now = Date.now();
        const diff = expiry - now;

        if (diff <= 0) {
            el.innerHTML = '<span class="countdown__segment"><span class="countdown__value" style="color: var(--text-danger);">00:00</span><span class="countdown__label">Kedaluwarsa</span></span>';
            // Reload to show expired state
            setTimeout(() => window.location.reload(), 2000);
            return;
        }

        const minutes = Math.floor(diff / 60000);
        const seconds = Math.floor((diff % 60000) / 1000);

        el.innerHTML = `
            <span class="countdown__segment">
                <span class="countdown__value">${String(minutes).padStart(2, '0')}</span>
                <span class="countdown__label">Menit</span>
            </span>
            <span class="countdown__segment">
                <span class="countdown__value">:</span>
            </span>
            <span class="countdown__segment">
                <span class="countdown__value">${String(seconds).padStart(2, '0')}</span>
                <span class="countdown__label">Detik</span>
            </span>
        `;

        requestAnimationFrame(() => setTimeout(update, 1000));
    }

    update();
};

// ---- Payment Status Check ----
window.checkPaymentStatus = async function(orderId) {
    try {
        const response = await fetch(`/payment/${orderId}/check`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();

        if (data.status === 'paid') {
            window.location.href = `/payment/success/${orderId}`;
        } else if (data.status === 'expired') {
            window.location.reload();
        } else if (data.status === 'failed') {
            window.location.href = `/payment/failed/${orderId}`;
        }

        return data;
    } catch (err) {
        console.error('Payment check error:', err);
    }
};

// ---- Modal ----
window.openModal = function(modalId) {
    document.getElementById(modalId)?.classList.add('active');
    document.body.style.overflow = 'hidden';
};

window.closeModal = function(modalId) {
    document.getElementById(modalId)?.classList.remove('active');
    document.body.style.overflow = '';
};

// ---- Admin Sidebar Toggle (Mobile) ----
function initAdminSidebar() {
    const toggle = document.querySelector('.admin-sidebar-toggle');
    const sidebar = document.querySelector('.admin-sidebar');

    toggle?.addEventListener('click', () => {
        sidebar?.classList.toggle('active');
    });
}

// ---- Initialize ----
document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initNavbar();
    initBannerCarousel();
    initQuantitySelectors();
    initProductTabs();
    initTabFilters();
    initSearch();
    initParticles();
    initAdminSidebar();
});
