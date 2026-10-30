/**
 * Social Sharing and Bookmark Module (Multi-Language Aware)
 */
export class ShareManager {
    private isEn: boolean;

    constructor() {
        this.isEn = (document.documentElement.lang || 'en').startsWith('en');
        this.initCopyLink();
        this.initNativeShare();
        this.initBookmarks();
    }

    private initCopyLink(): void {
        const copyBtns = document.querySelectorAll<HTMLElement>('.btn-copy-link');
        copyBtns.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const url = btn.getAttribute('data-url') || window.location.href;
                const successMsg = btn.getAttribute('data-toast') || (this.isEn ? 'Link copied to clipboard!' : 'लिंक क्लिपबोर्ड पर कॉपी हो गया!');
                const errorMsg = this.isEn ? 'Failed to copy link.' : 'लिंक कॉपी नहीं हो सका।';
                try {
                    await navigator.clipboard.writeText(url);
                    this.showToast(successMsg);
                } catch {
                    this.showToast(errorMsg, false);
                }
            });
        });
    }

    private initNativeShare(): void {
        const nativeShareBtns = document.querySelectorAll<HTMLElement>('.btn-native-share');
        nativeShareBtns.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const title = btn.getAttribute('data-title') || document.title;
                const text = btn.getAttribute('data-text') || '';
                const url = btn.getAttribute('data-url') || window.location.href;
                const successMsg = this.isEn ? 'Link copied to clipboard!' : 'लिंक क्लिपबोर्ड पर कॉपी हो गया!';

                if (navigator.share) {
                    try {
                        await navigator.share({ title, text, url });
                    } catch {
                        // User cancelled native share sheet
                    }
                } else {
                    try {
                        await navigator.clipboard.writeText(url);
                        this.showToast(successMsg);
                    } catch {
                        this.showToast(this.isEn ? 'Failed to copy link.' : 'लिंक कॉपी नहीं हो सका।', false);
                    }
                }
            });
        });
    }

    private initBookmarks(): void {
        const bookmarkBtns = document.querySelectorAll<HTMLElement>('.btn-bookmark');
        const getBookmarks = (): string[] => {
            try {
                return JSON.parse(localStorage.getItem('newskit_bookmarks') || '[]');
            } catch {
                return [];
            }
        };

        bookmarkBtns.forEach(btn => {
            const articleId = btn.getAttribute('data-article-id');
            if (!articleId) return;

            let bookmarks = getBookmarks();
            if (bookmarks.includes(articleId)) {
                btn.classList.add('bookmarked');
            }

            btn.addEventListener('click', (e) => {
                e.preventDefault();
                bookmarks = getBookmarks();
                if (bookmarks.includes(articleId)) {
                    bookmarks = bookmarks.filter(id => id !== articleId);
                    btn.classList.remove('bookmarked');
                    this.showToast(this.isEn ? 'Bookmark removed' : 'बुकमार्क हटा दिया गया');
                } else {
                    bookmarks.push(articleId);
                    btn.classList.add('bookmarked');
                    this.showToast(this.isEn ? 'Story saved to bookmarks!' : 'ख़बर बुकमार्क में सहेजी गई!');
                }
                localStorage.setItem('newskit_bookmarks', JSON.stringify(bookmarks));
            });
        });
    }

    public showToast(message: string, isSuccess: boolean = true): void {
        let toast = document.getElementById('newskit-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'newskit-toast';
            toast.className = 'fixed bottom-6 right-6 z-[999] bg-slate-900 text-white dark:bg-white dark:text-slate-900 px-5 py-3 rounded-2xl shadow-2xl text-xs sm:text-sm font-semibold transition-all transform translate-y-12 opacity-0 flex items-center gap-2.5 border border-slate-700/50 backdrop-blur-md';
            document.body.appendChild(toast);
        }

        const icon = isSuccess
            ? `<svg class="w-4 h-4 text-emerald-400 shrink-0 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`
            : `<svg class="w-4 h-4 text-rose-400 shrink-0 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>`;

        toast.innerHTML = `${icon}<span>${message}</span>`;
        toast.classList.remove('translate-y-12', 'opacity-0');

        setTimeout(() => {
            toast?.classList.add('translate-y-12', 'opacity-0');
        }, 3200);
    }
}
