/**
 * Breaking News Ticker & Live Clock Controller
 */
export class NewsTicker {
    private tickerEl: HTMLElement | null;
    private itemsContainer: HTMLElement | null;
    private prevBtn: HTMLButtonElement | null;
    private nextBtn: HTMLButtonElement | null;
    private clockEl: HTMLElement | null;
    private currentIndex: number = 0;
    private intervalId: number | null = null;
    private items: HTMLElement[] = [];

    constructor() {
        this.tickerEl = document.getElementById('news-ticker');
        this.itemsContainer = document.getElementById('ticker-items');
        this.prevBtn = document.getElementById('ticker-prev') as HTMLButtonElement | null;
        this.nextBtn = document.getElementById('ticker-next') as HTMLButtonElement | null;
        this.clockEl = document.getElementById('live-clock');

        this.init();
    }

    private init(): void {
        this.initClock();

        if (!this.tickerEl || !this.itemsContainer) return;

        this.items = Array.from(this.itemsContainer.querySelectorAll<HTMLElement>('.ticker-item'));
        if (this.items.length <= 1) return;

        // Show first item
        this.showItem(0);

        // Auto slide every 4.5 seconds
        this.startAutoSlide();

        // Pause on hover
        this.tickerEl.addEventListener('mouseenter', () => this.stopAutoSlide());
        this.tickerEl.addEventListener('mouseleave', () => this.startAutoSlide());

        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => {
                this.stopAutoSlide();
                this.prev();
                this.startAutoSlide();
            });
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => {
                this.stopAutoSlide();
                this.next();
                this.startAutoSlide();
            });
        }
    }

    private showItem(index: number): void {
        this.items.forEach((item, i) => {
            if (i === index) {
                item.style.display = 'inline-flex';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            } else {
                item.style.display = 'none';
                item.style.opacity = '0';
                item.style.transform = 'translateY(8px)';
            }
        });
        this.currentIndex = index;
    }

    public next(): void {
        const nextIdx = (this.currentIndex + 1) % this.items.length;
        this.showItem(nextIdx);
    }

    public prev(): void {
        const prevIdx = (this.currentIndex - 1 + this.items.length) % this.items.length;
        this.showItem(prevIdx);
    }

    private startAutoSlide(): void {
        if (this.intervalId) clearInterval(this.intervalId);
        this.intervalId = window.setInterval(() => {
            this.next();
        }, 4500);
    }

    private stopAutoSlide(): void {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
    }

    private initClock(): void {
        if (!this.clockEl) return;
        const lang = document.documentElement.lang || 'hi';
        const localeMap: Record<string, string> = {
            hi: 'hi-IN',
            en: 'en-US',
            ur: 'ur-PK',
            ar: 'ar-SA'
        };
        const currentLocale = localeMap[lang] || 'hi-IN';

        const update = () => {
            const now = new Date();
            const options: Intl.DateTimeFormatOptions = {
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            this.clockEl!.textContent = now.toLocaleDateString(currentLocale, options);
        };
        update();
        setInterval(update, 1000);
    }
}
