import { NewsTicker } from './ticker';
import { AudioReader } from './audio-reader';
import { ReadingProgress } from './reading-progress';
import { ThemeManager } from './theme';
import { ShareManager } from './share-modal';
import { SettingsLivePreview } from './settings-preview';
import { WeatherWidget } from './weather';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Breaking News Ticker & Live Clock
    new NewsTicker();

    // 2. Initialize Audio Narration Player
    new AudioReader();

    // 3. Reading Progress Scroll Bar
    new ReadingProgress();

    // 4. Dark / Light Theme Manager
    new ThemeManager();

    // 5. Social Share & Bookmarks
    new ShareManager();

    // 6. Admin Settings Live Preview
    new SettingsLivePreview();

    // 7. Dynamic Weather & Live Time Widget
    new WeatherWidget();

    // 8. Newsletter AJAX Subscription Handler
    initNewsletterForms();

    // Mobile Navigation Drawer Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu-drawer');
    const mobileMenuClose = document.getElementById('mobile-menu-close');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
        });
    }

    if (mobileMenuClose && mobileMenu) {
        mobileMenuClose.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    }

    // Mobile Search Bar Toggle
    const mobileSearchToggle = document.getElementById('mobile-search-toggle');
    const mobileSearchDropdown = document.getElementById('mobile-search-dropdown');
    const mobileSearchClose = document.getElementById('mobile-search-close');
    const mobileSearchInput = document.getElementById('mobile-search-input') as HTMLInputElement | null;

    if (mobileSearchToggle && mobileSearchDropdown) {
        mobileSearchToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = mobileSearchDropdown.classList.contains('hidden');
            if (isHidden) {
                mobileSearchDropdown.classList.remove('hidden');
                setTimeout(() => mobileSearchInput?.focus(), 50);
            } else {
                mobileSearchDropdown.classList.add('hidden');
            }
        });
    }

    if (mobileSearchClose && mobileSearchDropdown) {
        mobileSearchClose.addEventListener('click', () => {
            mobileSearchDropdown.classList.add('hidden');
        });
    }
});

function initNewsletterForms(): void {
    const forms = document.querySelectorAll<HTMLFormElement>('.newsletter-subscribe-form');
    forms.forEach((form) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const emailInput = form.querySelector<HTMLInputElement>('input[type="email"]');
            const submitBtn = form.querySelector<HTMLButtonElement>('button[type="submit"]');
            const email = emailInput?.value.trim();

            if (!email) return;

            const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'कृपया प्रतीक्षा करें...';
            }

            try {
                const actionUrl = form.getAttribute('action') || '/newsletter/subscribe';
                const csrfInput = form.querySelector<HTMLInputElement>('input[name="_token"]');
                const csrfToken = csrfInput ? csrfInput.value : '';

                const response = await fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email }),
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showToast(data.message || 'धन्यवाद! आपका ईमेल न्यूज़लेटर के लिए पंजीकृत हो गया है।', 'success');
                    if (emailInput) emailInput.value = '';
                } else {
                    const msg = data.errors?.email ? data.errors.email[0] : (data.message || 'पंजीकरण में त्रुटि हुई।');
                    showToast(msg, 'error');
                }
            } catch (err) {
                showToast('सर्वर से संपर्क करने में असमर्थ। कृपया पुनः प्रयास करें।', 'error');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            }
        });
    });
}

function showToast(message: string, type: 'success' | 'error' = 'success'): void {
    const existing = document.getElementById('global-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'global-toast';
    toast.className = `fixed bottom-6 right-6 z-50 px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 text-xs font-bold transition-all transform duration-300 translate-y-4 opacity-0 ${
        type === 'success' 
            ? 'bg-emerald-600 text-white shadow-emerald-500/20' 
            : 'bg-rose-600 text-white shadow-rose-500/20'
    }`;

    toast.innerHTML = `
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${type === 'success' 
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>' 
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>'
            }
        </svg>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-4', 'opacity-0');
    });

    setTimeout(() => {
        toast.classList.add('translate-y-4', 'opacity-0');
        setTimeout(() => toast.remove(), 350);
    }, 4500);
}

