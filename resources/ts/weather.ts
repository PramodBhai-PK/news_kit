/**
 * Live Dynamic Weather & Time Controller
 * Powered by Open-Meteo Free Forecast API & Browser Intl
 */

interface CityConfig {
    name: string;
    lat: number;
    lon: number;
}

const CITIES: Record<string, CityConfig> = {
    delhi: { name: 'New Delhi', lat: 28.6139, lon: 77.2090 },
    mumbai: { name: 'Mumbai', lat: 19.0760, lon: 72.8777 },
    lucknow: { name: 'Lucknow', lat: 26.8467, lon: 80.9462 },
    patna: { name: 'Patna', lat: 25.5941, lon: 85.1376 },
    bhopal: { name: 'Bhopal', lat: 23.2599, lon: 77.4126 },
    jaipur: { name: 'Jaipur', lat: 26.9124, lon: 75.7873 },
};

function getWeatherCondition(code: number): { text: string; icon: string } {
    if (code === 0) return { text: 'Clear Sky, Sunny', icon: '☀️' };
    if (code === 1) return { text: 'Mainly Clear', icon: '🌤️' };
    if (code === 2) return { text: 'Partly Cloudy', icon: '⛅' };
    if (code === 3) return { text: 'Overcast', icon: '☁️' };
    if (code === 45 || code === 48) return { text: 'Fog & Mist', icon: '🌫️' };
    if (code >= 51 && code <= 55) return { text: 'Light Drizzle', icon: '🌦️' };
    if (code >= 61 && code <= 65) return { text: 'Rain Showers', icon: '🌧️' };
    if (code >= 71 && code <= 77) return { text: 'Snowfall', icon: '❄️' };
    if (code >= 80 && code <= 82) return { text: 'Heavy Rain', icon: '🌧️' };
    if (code >= 95) return { text: 'Thunderstorm', icon: '⛈️' };

    return { text: 'Pleasant Weather', icon: '🌤️' };
}

export class WeatherWidget {
    private tempEl: HTMLElement | null;
    private descEl: HTMLElement | null;
    private iconEl: HTMLElement | null;
    private citySelect: HTMLSelectElement | null;
    private timeEl: HTMLElement | null;
    private windEl: HTMLElement | null;
    private currentCity: string = 'delhi';
    private lang: string = 'en';

    constructor() {
        this.tempEl = document.getElementById('weather-temp');
        this.descEl = document.getElementById('weather-desc');
        this.iconEl = document.getElementById('weather-icon');
        this.citySelect = document.getElementById('weather-city-select') as HTMLSelectElement | null;
        this.timeEl = document.getElementById('weather-live-time');
        this.windEl = document.getElementById('weather-wind');
        this.lang = document.documentElement.lang || 'en';

        if (this.tempEl || this.timeEl) {
            this.init();
        }
    }

    private init(): void {
        this.startLiveClock();
        this.fetchWeather(this.currentCity);

        if (this.citySelect) {
            this.citySelect.addEventListener('change', (e) => {
                const target = e.target as HTMLSelectElement;
                this.currentCity = target.value;
                this.fetchWeather(this.currentCity);
            });
        }
    }

    private startLiveClock(): void {
        const update = () => {
            if (!this.timeEl) return;
            const now = new Date();

            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            const hourStr = String(hours).padStart(2, '0');

            try {
                const datePart = new Intl.DateTimeFormat(this.lang, {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                }).format(now);
                this.timeEl.textContent = `${datePart} | ${hourStr}:${minutes}:${seconds} ${ampm}`;
            } catch {
                const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                this.timeEl.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()} | ${hourStr}:${minutes}:${seconds} ${ampm}`;
            }
        };

        update();
        setInterval(update, 1000);
    }

    public async fetchWeather(cityKey: string): Promise<void> {
        const city = CITIES[cityKey] || CITIES.delhi;

        try {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${city.lat}&longitude=${city.lon}&current_weather=true&hourly=relativehumidity_2m`;
            const res = await fetch(url);
            if (!res.ok) throw new Error('Weather API error');

            const data = await res.json();
            const current = data.current_weather;
            if (!current) return;

            const temp = Math.round(current.temperature);
            const condition = getWeatherCondition(current.weathercode);
            const wind = Math.round(current.windspeed);

            if (this.tempEl) this.tempEl.textContent = `${temp}°C`;
            if (this.descEl) this.descEl.textContent = condition.text;
            if (this.iconEl) this.iconEl.textContent = condition.icon;

            const windPrefix = this.windEl?.getAttribute('data-label') || 'Wind';
            if (this.windEl) this.windEl.textContent = `${windPrefix}: ${wind} km/h`;
        } catch {
            // Graceful fallback if offline
            if (this.tempEl) this.tempEl.textContent = '28°C';
            const fallbackCondition = getWeatherCondition(0);
            if (this.descEl) this.descEl.textContent = fallbackCondition.text;
            if (this.iconEl) this.iconEl.textContent = '☀️';
            const windPrefix = this.windEl?.getAttribute('data-label') || 'Wind';
            if (this.windEl) this.windEl.textContent = `${windPrefix}: 10 km/h`;
        }
    }
}
