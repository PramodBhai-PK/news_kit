/**
 * Reading Progress Indicator Bar
 */
export class ReadingProgress {
    private barEl: HTMLElement | null;

    constructor() {
        this.barEl = document.getElementById('reading-progress-bar');
        if (this.barEl) {
            this.init();
        }
    }

    private init(): void {
        window.addEventListener('scroll', () => {
            const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            if (docHeight <= 0) return;
            const scrolled = (window.scrollY / docHeight) * 100;
            if (this.barEl) {
                this.barEl.style.width = `${Math.min(100, Math.max(0, scrolled))}%`;
            }
        }, { passive: true });
    }
}
