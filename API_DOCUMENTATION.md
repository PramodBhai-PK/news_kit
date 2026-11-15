# NewsKit Portal & Mobile REST API (v1)

Welcome to the **NewsKit REST API** documentation. This API powers mobile applications (Flutter for Android & iOS, React Native) and external frontend integrations.

---

## 🚀 Quick Overview

- **Base URL:** `https://your-domain.com/api/v1`
- **Current Version:** `v1`
- **Data Format:** JSON (`application/json`)
- **Authentication:** Bearer Token via Laravel Sanctum
- **Postman Collection:** Ready-to-import `newskit_api_v1.postman_collection.json` included in root.

---

## 🌐 Localization & Multi-Language Support

NewsKit supports 4 international languages out of the box:
- **English (`en`)** (Default)
- **Hindi (`hi`)**
- **Urdu (`ur`)** (RTL)
- **Arabic (`ar`)** (RTL)

You can pass the desired locale via:
1. **Query Parameter:** `?lang=hi`
2. **HTTP Header:** `X-App-Locale: hi`
3. **Accept-Language Header:** `Accept-Language: hi`

---

## 🔒 Authentication

Most read endpoints are **public**. Protected user endpoints require a Bearer token in the request header:

```http
Authorization: Bearer <your_personal_access_token>
Accept: application/json
```

---

## 📑 API Endpoints Summary

| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :---: |
| `GET` | `/api/v1/config` | Global app settings, branding, theme, AdMob IDs, WhatsApp QR | No |
| `GET` | `/api/v1/articles` | Paginated articles with category, location, and search filters | No |
| `GET` | `/api/v1/articles/breaking` | Top 10 breaking news items | No |
| `GET` | `/api/v1/articles/trending` | Top 10 trending news items | No |
| `GET` | `/api/v1/articles/featured` | Top 10 editorial featured stories | No |
| `GET` | `/api/v1/articles/videos` | Video bulletins (YouTube & MP4 streams) | No |
| `GET` | `/api/v1/articles/{slug}` | Single article details with related news and audio TTS text | No |
| `GET` | `/api/v1/articles/{slug}/comments` | Approved article comments | No |
| `POST` | `/api/v1/articles/{slug}/comments` | Post a reader comment | No |
| `GET` | `/api/v1/categories` | List all active categories with article counts | No |
| `GET` | `/api/v1/categories/{slug}` | Category details + paginated category news | No |
| `GET` | `/api/v1/locations/states` | List states with city counts | No |
| `GET` | `/api/v1/locations/states/{slug}` | State details with its cities | No |
| `GET` | `/api/v1/locations/cities` | List cities (filterable by state) | No |
| `GET` | `/api/v1/locations/cities/{slug}` | City details + hyperlocal news feed | No |
| `POST` | `/api/v1/citizen-tips` | Submit breaking news tip with photo/video upload | No |
| `POST` | `/api/v1/newsletter/subscribe` | Subscribe email to newsletter | No |
| `GET` | `/api/v1/ads` | Active banner/sidebar ads | No |
| `POST` | `/api/v1/ads/{id}/click` | Track ad click | No |
| `POST` | `/api/v1/auth/register` | Register new user account | No |
| `POST` | `/api/v1/auth/login` | Login and receive Sanctum Bearer token | No |
| `GET` | `/api/v1/auth/me` | Get profile and digital press card | **Yes** |
| `POST` | `/api/v1/auth/logout` | Revoke active access token | **Yes** |

---

## 📡 Detailed Endpoint Specifications

### 1. App Configuration (`GET /api/v1/config`)
Retrieves site branding, theme colors, social channels, WhatsApp community card data, and AdMob banner IDs.

**Sample Response (`200 OK`):**
```json
{
  "status": "success",
  "data": {
    "app_name": "NewsKit Portal",
    "app_tagline": "Independent, Accurate & Fast Breaking News",
    "short_name": "NK",
    "logo_url": "https://example.com/images/logo.svg",
    "favicon_url": "https://example.com/images/favicon.svg",
    "theme": {
      "primary_color": "#E11D48",
      "accent_color": "#F59E0B",
      "dark_bg": "#0F172A"
    },
    "features": {
      "breaking_ticker": true,
      "audio_reader": true,
      "comments": true,
      "newsletter": true,
      "hyperlocal": true,
      "citizen_journalism": true
    },
    "whatsapp_channel": {
      "enabled": true,
      "channel_url": "https://whatsapp.com/channel/newskit",
      "followers_count": "250,000+",
      "qr_code_url": null
    },
    "localization": {
      "default_locale": "en",
      "current_locale": "en",
      "rtl_locales": ["ar", "ur"],
      "supported_locales": {
        "en": {"name": "English", "dir": "ltr"},
        "hi": {"name": "Hindi", "dir": "ltr"},
        "ur": {"name": "Urdu", "dir": "rtl"},
        "ar": {"name": "Arabic", "dir": "rtl"}
      }
    },
    "monetization": {
      "admob_enabled": false,
      "admob_banner_android": "",
      "admob_banner_ios": ""
    }
  }
}
```

---

### 2. Articles List (`GET /api/v1/articles`)

**Query Parameters:**
- `per_page`: Number of articles per page (Default: `12`, Max: `50`)
- `category`: Category slug or numeric ID
- `state`: State slug or numeric ID (Hyperlocal)
- `city`: City slug or numeric ID (Hyperlocal)
- `type`: Filter by `breaking`, `featured`, `trending`, or `video`
- `search`: Search keywords in title and body
- `tag`: Filter by tag slug

**Sample Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Global Technology Summit 2026: Innovations in AI Announced",
      "slug": "global-technology-summit-2026",
      "excerpt": "Leaders gather to showcase next-generation breakthroughs.",
      "featured_image": "https://example.com/storage/articles/tech.jpg",
      "image_caption": "Keynote presentation at summit",
      "has_video": false,
      "video_type": "none",
      "video_url": null,
      "youtube_id": null,
      "category": {
        "id": 2,
        "name": "Technology",
        "slug": "technology",
        "color": "#3B82F6"
      },
      "location": {
        "badge": "New York",
        "state": "New York",
        "city": "New York City"
      },
      "author": {
        "id": 1,
        "name": "Jane Doe",
        "designation": "Chief Tech Editor",
        "avatar": null
      },
      "is_breaking": false,
      "is_featured": true,
      "is_trending": true,
      "views_count": 1420,
      "reading_time": 4,
      "published_at": "2026-11-07T12:00:00+00:00",
      "published_at_human": "2 hours ago",
      "share_url": "https://example.com/article/global-technology-summit-2026"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 12,
    "total": 58
  },
  "links": {
    "prev": null,
    "next": "https://example.com/api/v1/articles?page=2"
  }
}
```

---

### 3. Article Detail (`GET /api/v1/articles/{slug}`)
Returns full story HTML, plus **`clean_text`** specifically designed for mobile **Audio TTS (Text-to-Speech)** playback, tags, custom ad blocks, and related stories.

---

### 4. Citizen Journalism / Breaking News Tip (`POST /api/v1/citizen-tips`)
Allows public app users to submit eyewitness reports directly with photo or video attachments.

**Request Type:** `multipart/form-data`

| Field | Type | Required | Description |
| :--- | :--- | :---: | :--- |
| `title` | string | **Yes** | Headline of what happened |
| `description` | string | **Yes** | Full ground report description |
| `location_name` | string | No | Neighborhood, landmark or road |
| `state_id` | integer | No | State ID |
| `city_id` | integer | No | City ID |
| `witness_name` | string | No | Citizen reporter's name |
| `witness_phone` | string | No | Citizen reporter's phone |
| `witness_email` | string | No | Citizen reporter's email |
| `is_anonymous` | boolean | No | `1` to keep identity confidential |
| `media` | file | No | Image or Video file (Max: 50MB) |

---

### 5. Authentication Flow (Sanctum Tokens)

#### User Registration:
`POST /api/v1/auth/register`
```json
{
  "name": "Alex Smith",
  "email": "alex@example.com",
  "password": "securepassword123",
  "phone": "+1234567890",
  "device_name": "iPhone 16 Pro"
}
```

#### User Login:
`POST /api/v1/auth/login`
```json
{
  "email": "alex@example.com",
  "password": "securepassword123",
  "device_name": "iPhone 16 Pro"
}
```

**Response (`200 OK`):**
```json
{
  "status": "success",
  "message": "Login successful.",
  "data": {
    "token": "1|n1M0f...sanctum_token...",
    "token_type": "Bearer",
    "user": {
      "id": 5,
      "name": "Alex Smith",
      "email": "alex@example.com",
      "role": "reporter",
      "press_card": null
    }
  }
}
```

---

## 🛠️ Postman Collection Usage

1. Open **Postman**.
2. Click **Import** (top left).
3. Select `newskit_api_v1.postman_collection.json` from the repository root.
4. Set the `base_url` variable to your local or staging server (e.g., `http://localhost:8000/api/v1`).
5. All 23 requests are pre-configured with query parameters and sample JSON bodies ready to execute.
