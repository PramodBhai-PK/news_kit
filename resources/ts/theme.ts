/**
 * Dark / Light Theme Controller
 */
export class ThemeManager {
    private toggleBtn: HTMLElement | null;

    constructor() {
        this.toggleBtn = document.getElementById('theme-toggle-btn');
        this.init();
    }

    private init(): void {
        const savedTheme = localStorage.getItem('newskit_theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => {
                this.toggle();
            });
        }
    }

    public toggle(): void {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('newskit_theme', isDark ? 'dark' : 'light');
    }
}
