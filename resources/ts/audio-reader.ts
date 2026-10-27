/**
 * News Audio Narration Player (Web Speech API)
 * Allows readers to listen to the news article with high quality text-to-speech.
 */
export class AudioReader {
    private playBtn: HTMLButtonElement | null;
    private statusText: HTMLElement | null;
    private waveEl: HTMLElement | null;
    private articleContentEl: HTMLElement | null;
    private synth: SpeechSynthesis | null = null;
    private utterance: SpeechSynthesisUtterance | null = null;
    private isPlaying: boolean = false;
    private isPaused: boolean = false;

    constructor() {
        this.playBtn = document.getElementById('audio-play-btn') as HTMLButtonElement | null;
        this.statusText = document.getElementById('audio-status-text');
        this.waveEl = document.getElementById('audio-wave');
        this.articleContentEl = document.getElementById('article-content-body');

        if (typeof window !== 'undefined' && 'speechSynthesis' in window) {
            this.synth = window.speechSynthesis;
        }

        this.init();
    }

    private init(): void {
        if (!this.playBtn || !this.articleContentEl || !this.synth) return;

        this.playBtn.addEventListener('click', () => {
            if (this.isPlaying) {
                if (this.isPaused) {
                    this.resume();
                } else {
                    this.pause();
                }
            } else {
                this.play();
            }
        });
    }

    private getHindiOrIndianVoice(): SpeechSynthesisVoice | null {
        if (!this.synth) return null;
        const voices = this.synth.getVoices();
        // Priority: hi-IN -> en-IN -> any Hindi -> first available
        return (
            voices.find(v => v.lang.includes('hi') || v.lang.includes('hi-IN')) ||
            voices.find(v => v.lang.includes('en-IN')) ||
            voices[0] ||
            null
        );
    }

    private play(): void {
        if (!this.synth || !this.articleContentEl) return;

        this.synth.cancel(); // Stop any pending speech

        const text = this.articleContentEl.innerText.trim();
        if (!text) return;

        this.utterance = new SpeechSynthesisUtterance(text);
        this.utterance.rate = 0.95; // Natural news delivery pace
        this.utterance.pitch = 1.0;

        const voice = this.getHindiOrIndianVoice();
        if (voice) {
            this.utterance.voice = voice;
        }

        this.utterance.onstart = () => {
            this.isPlaying = true;
            this.isPaused = false;
            this.updateUI('playing');
        };

        this.utterance.onend = () => {
            this.isPlaying = false;
            this.isPaused = false;
            this.updateUI('idle');
        };

        this.utterance.onerror = () => {
            this.isPlaying = false;
            this.isPaused = false;
            this.updateUI('idle');
        };

        this.synth.speak(this.utterance);
    }

    private pause(): void {
        if (!this.synth) return;
        this.synth.pause();
        this.isPaused = true;
        this.updateUI('paused');
    }

    private resume(): void {
        if (!this.synth) return;
        this.synth.resume();
        this.isPaused = false;
        this.updateUI('playing');
    }

    private updateUI(state: 'playing' | 'paused' | 'idle'): void {
        if (!this.playBtn) return;

        if (state === 'playing') {
            this.playBtn.innerHTML = `
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                <span>रोकें (Pause)</span>
            `;
            if (this.statusText) this.statusText.textContent = 'ख़बर पढ़ी जा रही है...';
            if (this.waveEl) this.waveEl.classList.remove('hidden');
        } else if (state === 'paused') {
            this.playBtn.innerHTML = `
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                <span>जारी रखें (Resume)</span>
            `;
            if (this.statusText) this.statusText.textContent = 'ऑडियो रुका हुआ है';
        } else {
            this.playBtn.innerHTML = `
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                <span>ख़बर सुनें (Audio)</span>
            `;
            if (this.statusText) this.statusText.textContent = 'ऑडियो स्टोरी सुनें';
            if (this.waveEl) this.waveEl.classList.add('hidden');
        }
    }
}
