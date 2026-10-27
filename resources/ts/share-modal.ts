/**
 * Social Sharing and Bookmark Module
 */
export class ShareManager {
    constructor() {
        this.initCopyLink();
        this.initBookmarks();
    }

    private initCopyLink(): void {
        const copyBtns = document.querySelectorAll<HTMLElement>('.btn-copy-link');
        copyBtns.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const url = btn.getAttribute('data-url') || window.location.href;
                try {
                    await navigator.clipboard.writeText(url);
                    this.showToast('लिंक क्लिपबोर्ड पर कॉपी हो गया!');
                } catch {
                    this.showToast('लिंक कॉपी नहीं हो सका।');
                }
            });
        });

        const nativeShareBtns = document.querySelectorAll<HTMLElement>('.btn-native-share');
        nativeShareBtns.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const title = btn.getAttribute('data-title') || document.title;
                const url = btn.getAttribute('data-url') || window.location.href;
                if (navigator.share) {
                    try {
                        await navigator.share({ title, url });
                    } catch {
                        // User cancelled
                    }
                } else {
                    await navigator.clipboard.writeText(url);
                    this.showToast('लिंक क्लिपबोर्ड पर कॉपी हो गया!');
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
                    this.showToast('बुकमार्क हटा दिया गया');
                } else {
                    bookmarks.push(articleId);
                    btn.classList.add('bookmarked');
                    this.showToast('ख़बर बुकमार्क में सहेजी गई!');
                }
                localStorage.setItem('newskit_bookmarks', JSON.stringify(bookmarks));
            });
        });
    }

    private showToast(message: string): void {
        let toast = document.getElementById('newskit-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'newskit-toast';
            toast.className = 'fixed bottom-6 right-6 z-50 bg-slate-900 text-white dark:bg-white dark:text-slate-900 px-5 py-3 rounded-xl shadow-2xl text-sm font-semibold transition-all transform translate-y-12 opacity-0 flex items-center gap-2 border border-slate-700/50';
            document.body.appendChild(toast);
        }

        toast.innerHTML = `
            <svg class="w-4 h-4 text-emerald-500 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>${message}</span>
        `;
        toast.classList.remove('translate-y-12', 'opacity-0');

        setTimeout(() => {
            toast?.classList.add('translate-y-12', 'opacity-0');
        }, 3000);
    }
}
