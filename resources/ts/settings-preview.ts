/**
 * Live Branding & Settings Preview Controller
 * Updates the preview card in real-time as the admin modifies site name, logo, or theme colors.
 */
export class SettingsLivePreview {
    private siteNameInput: HTMLInputElement | null;
    private taglineInput: HTMLInputElement | null;
    private primaryColorInput: HTMLInputElement | null;
    private accentColorInput: HTMLInputElement | null;
    private logoUrlInput: HTMLInputElement | null;

    private previewTitle: HTMLElement | null;
    private previewTagline: HTMLElement | null;
    private previewLogo: HTMLImageElement | null;
    private previewBadge: HTMLElement | null;
    private previewCard: HTMLElement | null;

    constructor() {
        this.siteNameInput = document.getElementById('input_site_name') as HTMLInputElement | null;
        this.taglineInput = document.getElementById('input_site_tagline') as HTMLInputElement | null;
        this.primaryColorInput = document.getElementById('input_primary_color') as HTMLInputElement | null;
        this.accentColorInput = document.getElementById('input_accent_color') as HTMLInputElement | null;
        this.logoUrlInput = document.getElementById('input_logo_url') as HTMLInputElement | null;

        this.previewTitle = document.getElementById('preview-site-name');
        this.previewTagline = document.getElementById('preview-site-tagline');
        this.previewLogo = document.getElementById('preview-logo-img') as HTMLImageElement | null;
        this.previewBadge = document.getElementById('preview-badge');
        this.previewCard = document.getElementById('preview-card');

        this.init();
    }

    private init(): void {
        if (!this.previewTitle) return;

        this.siteNameInput?.addEventListener('input', () => {
            if (this.previewTitle && this.siteNameInput) {
                this.previewTitle.textContent = this.siteNameInput.value || 'The LallanTop News';
            }
        });

        this.taglineInput?.addEventListener('input', () => {
            if (this.previewTagline && this.taglineInput) {
                this.previewTagline.textContent = this.taglineInput.value || 'बेबाक, सटीक और सबसे तेज़ ख़बरें';
            }
        });

        this.logoUrlInput?.addEventListener('input', () => {
            if (this.previewLogo && this.logoUrlInput && this.logoUrlInput.value) {
                this.previewLogo.src = this.logoUrlInput.value;
            }
        });

        const updateColors = () => {
            const primary = this.primaryColorInput?.value || '#E11D48';
            const accent = this.accentColorInput?.value || '#F59E0B';

            document.documentElement.style.setProperty('--brand-primary', primary);
            document.documentElement.style.setProperty('--brand-accent', accent);

            if (this.previewBadge) {
                this.previewBadge.style.backgroundColor = primary;
            }
        };

        this.primaryColorInput?.addEventListener('input', updateColors);
        this.accentColorInput?.addEventListener('input', updateColors);
    }
}
