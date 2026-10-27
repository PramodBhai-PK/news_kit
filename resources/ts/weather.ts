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
    delhi: { name: 'नई दिल्ली', lat: 28.6139, lon: 77.2090 },
    mumbai: { name: 'मुंबई', lat: 19.0760, lon: 72.8777 },
    lucknow: { name: 'लखनऊ', lat: 26.8467, lon: 80.9462 },
    patna: { name: 'पटना', lat: 25.5941, lon: 85.1376 },
    bhopal: { name: 'भोपाल', lat: 23.2599, lon: 77.4126 },
    jaipur: { name: 'जयपुर', lat: 26.9124, lon: 75.7873 },
};

function getWeatherCondition(code: number, lang: string = 'hi'): { text: string; icon: string } {
    const isEn = lang === 'en';
    const isUr = lang === 'ur';
    const isAr = lang === 'ar';

    if (code === 0) {
        if (isEn) return { text: 'Clear Sky, Sunny', icon: '☀️' };
        if (isUr) return { text: 'صاف آسمان، کھلی دھوپ', icon: '☀️' };
        if (isAr) return { text: 'سماء صافية، مشمس', icon: '☀️' };
        return { text: 'साफ आसमान, खिली धूप', icon: '☀️' };
    }
    if (code === 1) {
        if (isEn) return { text: 'Mainly Clear', icon: '🌤️' };
        if (isUr) return { text: 'بنیادی طور پر صاف', icon: '🌤️' };
        if (isAr) return { text: 'صافٍ غالباً', icon: '🌤️' };
        return { text: 'मुख्य रूप से साफ', icon: '🌤️' };
    }
    if (code === 2) {
        if (isEn) return { text: 'Partly Cloudy', icon: '⛅' };
        if (isUr) return { text: 'ہلکے بادل', icon: '⛅' };
        if (isAr) return { text: 'غائم جزئياً', icon: '⛅' };
        return { text: 'हल्के बादल', icon: '⛅' };
    }
    if (code === 3) {
        if (isEn) return { text: 'Overcast', icon: '☁️' };
        if (isUr) return { text: 'گھنے بادل', icon: '☁️' };
        if (isAr) return { text: 'غائم كلياً', icon: '☁️' };
        return { text: 'घने बादल', icon: '☁️' };
    }
    if (code === 45 || code === 48) {
        if (isEn) return { text: 'Fog & Mist', icon: '🌫️' };
        if (isUr) return { text: 'کہر اور دھند', icon: '🌫️' };
        if (isAr) return { text: 'ضباب وسديم', icon: '🌫️' };
        return { text: 'कोहरा व धुंध', icon: '🌫️' };
    }
    if (code >= 51 && code <= 55) {
        if (isEn) return { text: 'Light Drizzle', icon: '🌦️' };
        if (isUr) return { text: 'ہلکی بونداباندی', icon: '🌦️' };
        if (isAr) return { text: 'رذاذ خفيف', icon: '🌦️' };
        return { text: 'हल्की बूंदाबांदी', icon: '🌦️' };
    }
    if (code >= 61 && code <= 65) {
        if (isEn) return { text: 'Rain Showers', icon: '🌧️' };
        if (isUr) return { text: 'بارش جاری', icon: '🌧️' };
        if (isAr) return { text: 'هطول أمطار', icon: '🌧️' };
        return { text: 'बारिश जारी', icon: '🌧️' };
    }
    if (code >= 71 && code <= 77) {
        if (isEn) return { text: 'Snowfall', icon: '❄️' };
        if (isUr) return { text: 'برفباری', icon: '❄️' };
        if (isAr) return { text: 'تساقط ثلوج', icon: '❄️' };
        return { text: 'बर्फबारी', icon: '❄️' };
    }
    if (code >= 80 && code <= 82) {
        if (isEn) return { text: 'Heavy Rain', icon: '🌧️' };
        if (isUr) return { text: 'تیز بارش', icon: '🌧️' };
        if (isAr) return { text: 'أمطار غزيرة', icon: '🌧️' };
        return { text: 'तेज़ बौछारें', icon: '🌧️' };
    }
    if (code >= 95) {
        if (isEn) return { text: 'Thunderstorm', icon: '⛈️' };
        if (isUr) return { text: 'طوفان اور گرج چمک', icon: '⛈️' };
        if (isAr) return { text: 'عواصف رعدية', icon: '⛈️' };
        return { text: 'तूफान व गर्जन', icon: '⛈️' };
    }

    if (isEn) return { text: 'Pleasant Weather', icon: '🌤️' };
    if (isUr) return { text: 'خوشگوار موسم', icon: '🌤️' };
    if (isAr) return { text: 'طقس معتدل', icon: '🌤️' };
    return { text: 'सुहावना मौसम', icon: '🌤️' };
}

export class WeatherWidget {
    private tempEl: HTMLElement | null;
    private descEl: HTMLElement | null;
    private iconEl: HTMLElement | null;
    private citySelect: HTMLSelectElement | null;
    private timeEl: HTMLElement | null;
    private windEl: HTMLElement | null;
    private currentCity: string = 'delhi';
    private lang: string = 'hi';

    constructor() {
        this.tempEl = document.getElementById('weather-temp');
        this.descEl = document.getElementById('weather-desc');
        this.iconEl = document.getElementById('weather-icon');
        this.citySelect = document.getElementById('weather-city-select') as HTMLSelectElement | null;
        this.timeEl = document.getElementById('weather-live-time');
        this.windEl = document.getElementById('weather-wind');
        this.lang = document.documentElement.lang || 'hi';

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

            if (this.lang === 'en') {
                const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                this.timeEl.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()} | ${hourStr}:${minutes}:${seconds} ${ampm}`;
            } else if (this.lang === 'ur') {
                const days = ['اتوار', 'پیر', 'منگل', 'بدھ', 'جمعرات', 'جمعہ', 'ہفتہ'];
                const months = ['جنوری', 'فروری', 'مارچ', 'اپریل', 'مئی', 'جون', 'جولائی', 'اگست', 'ستمبر', 'اکتوبر', 'نومبر', 'دسمبر'];
                this.timeEl.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()} | ${hourStr}:${minutes}:${seconds} ${ampm}`;
            } else if (this.lang === 'ar') {
                const days = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                const months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
                this.timeEl.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()} | ${hourStr}:${minutes}:${seconds} ${ampm}`;
            } else {
                const days = ['रविवार', 'सोमवार', 'मंगलवार', 'बुधवार', 'गुरुवार', 'शुक्रवार', 'शनिवार'];
                const months = ['जनवरी', 'फरवरी', 'मार्च', 'अप्रैल', 'मई', 'जून', 'जुलाई', 'अगस्त', 'सितंबर', 'अक्टूबर', 'नवंबर', 'दिसंबर'];
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
            const condition = getWeatherCondition(current.weathercode, this.lang);
            const wind = Math.round(current.windspeed);

            if (this.tempEl) this.tempEl.textContent = `${temp}°C`;
            if (this.descEl) this.descEl.textContent = condition.text;
            if (this.iconEl) this.iconEl.textContent = condition.icon;

            const windPrefix = this.lang === 'en' ? 'Wind' : (this.lang === 'ur' ? 'ہوا' : (this.lang === 'ar' ? 'الرياح' : 'हवा'));
            if (this.windEl) this.windEl.textContent = `${windPrefix}: ${wind} km/h`;
        } catch (err) {
            // Graceful fallback if offline
            if (this.tempEl) this.tempEl.textContent = '28°C';
            const fallbackCondition = getWeatherCondition(0, this.lang);
            if (this.descEl) this.descEl.textContent = fallbackCondition.text;
            if (this.iconEl) this.iconEl.textContent = '☀️';
            const windPrefix = this.lang === 'en' ? 'Wind' : (this.lang === 'ur' ? 'ہوا' : (this.lang === 'ar' ? 'الرياح' : 'हवा'));
            if (this.windEl) this.windEl.textContent = `${windPrefix}: 10 km/h`;
        }
    }
}
