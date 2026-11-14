<p align="center"><a href="https://myblog4u.site" target="_blank"><img src="https://myblog4u.site/img/logo2.png" width="400" alt="Myblog4u"></a></p>

<p align="center">
<a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-FF2D20?style=flat&logo=laravel&logoColor=white" alt="Laravel"></a>
<a href="https://www.php.net"><img src="https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white" alt="PHP"></a>
<a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=flat&logo=tailwindcss&logoColor=white" alt="Tailwind CSS"></a>
<a href="https://www.digitalocean.com"><img src="https://img.shields.io/badge/DigitalOcean-0080FF?style=flat&logo=digitalocean&logoColor=white" alt="DigitalOcean"></a>
<a href="https://nginx.org"><img src="https://img.shields.io/badge/Nginx-009639?style=flat&logo=nginx&logoColor=white" alt="Nginx"></a>
</p>

<h2 align="center">MyBlog4u Social Networking</h2>

## About 

MyBlog4u is a scalable, production-ready social networking platform comes with advanced features, ready for monetization, Custom Ad banners/Google AdSense, Web and Rss scraping (Setup Auto crawl settings), ultimate custom rate limiting, Full ACL, Dynamic Sitemap + RSS generation, Google Analytics GA4 Full widgets tracking sites, Custom pages creation, Image Optimization, Multi media drivers support (R2, DO spaces, S3, local ).

## Screenshots
👉 [View Screenshots](SCREENSHOTS.md)


## 🚀 Features

### 📝 Social Feed and Interactions
  
  - **Dynamic Slides:** Homepage with dynamic slides geenrated.
  - **Infinite Scroll:** Fast laod feed.
  - **Interactions:** AJAX powered Likes, comments, follow, save that updates UI withoud reload page.
  - **Nested Comments and Mentions:** Multi level comments, with @username mentions.
  - **Follow System:** Insta style, follow creators, accept followers, requested followers.
  - **Profile Visiblity:** Switch between public and private only creators with accepted follow can access posts.
  - **Activity Section:** Track the creator history comments, replies, mentions, posted.
  - **Reports:** Creators can report articles,comments,profiles based on selecting reasons or typing new reason. 

### 👤 Creator Dashboard and Editor
  
  - **Creator Dashboard:** Dasboard page with multiple stats and pages, pin unpin posts, profile views, custom social links.
  - **Rich Text Editor:** Powered by TinyMCE,responsive and sticky toolbar.
  - **Media Integration:** By TinyMCE creators can upload multiple images into arcticle editable and deletable with image zoom preview.
  - **Hashtags and Categories** Creators can write new up to 4 hashtags on article or select most popular if exists, and choose one category.
  - **Articles Submission:** Creators can create publish article to public, or save draft articles with private visibility. toggle visibility on comments thread.

### 🤖 Web Scraping
  
  - **Scraping Sources:** Add unlimited sources with data type web/rss switch, automatically detects article links, detect old articles to delete, automating logs scraping detection,  and extracting OpenGraph data (Title, Image, description Category).
  - **Background Cron:** Running Crawl in background with laravel queues with scraping frequency (15min, 2 hrs, 24 hrs ..etc ).
  - **News Feed:** News Feed with cached data with redis and pagination ajax with multi sources filtering.

### 🧑‍💼 Admin Control Panel

  - **Dashboard Pages:** Realtime charts tracking new users, published posts, Google Analytics integration with fully responsive and AJAX based.
  - **Media Setting** Easy setup custom media driver and switch between multiple media suppoted ( Local, Cloudflare R2, S3, DigitalOcean Spaces)
  - **Posts Moderation:** Full Control on posts,and enforce post submissions by auto approve posts and allow user posts going published.
  - **User Management:** Full control on users, with Full custom ACL manage custom rules, permissions , grant permissions to each rule , and grant user permissions.
  - **Notifications:** Track Creators actions by receiving notification by type, notification setting manage by notification type to allow recieving or not.
  - **Custom Pages:** Create Custom pages to global display footer ( about us, privacy ..etc).
  - **SMTP Settings:** Customizable SMTP configuration and testing mode.
  - **Backups Generator** Cronjbos for creating daily encrypting backups , and delete old backups, managable backups delete or download.
  - **Auth and Security:** Enable user registration mode with allowed certian custom domains, and enable google recaptcha by editing site and secret keys.
  - **Slides Management:** Customize Home feed by creating new slides.
  - **Monetization:** Global Ad management by placing Google AdSense code or custom banner images across different postions customizable.
  - **Reports:** Full Control Reports by type comments,posts,profiles.
  - **Hashtags and Categories:** Manage hashatgs activating or disabling spam hastags, manage categories actions.
  - **Seo Tools:** Customizable meta Seo with customizable header scripts and footer scripts.
  - **System Maintenance:** Clear all Cache, or refresh compiled views,Clear config cache,Clear route cache,Clear log.

### 🔐 Security & Notifications 

  -  **Custom Two-Factor Authentication :** enabling by scanning qrcode or type manually with confirmation code.
  -  **Confirm Password Check:** Laravel middleware for confirmimg passwords on certian actions.
  -  **Identity Check:** Creators by changing their passwords will redirect to identity check page with Otp verification.
  -  **Email and In-app Notifications:** Creators will recieve mutiple notifications type Views,Mentions,Posted,Following,Requested Following,Reports with real-time and by emails.

### 🔐 Trusted Devices & Active Sessions

  -  **Trusted Devices:** Creators can bypass twofactor by checking on trust this device for 30 days, with secure token rotation every 24hr.
  -  **Active Sessions Management:** Creators can track their active sessions, log out certian device or all devices.

### 🧱 Architecture & Performance

  - **Clean architecture using:** Service Layer,Observers,Builders,Repositories and Decorators Caches, DTOs.
  - **Dynamic XML/RSS Sitemaps Generated**


## Setup With Docker
```
git clone https://github.com/ibrahimjml/MyBlog4u.git
cp .env.docker .env
docker compose up --build -d
```

## Normal Installation Requirements
- **Requirements extensions:**
- **PHP 8.3**
- **Imagick**
- **intl**
- **tokenizer**

## Reverb Reverse Proxy Apache Requirements
- **LoadModule proxy_module modules/mod_proxy.so**
- **LoadModule proxy_http2_module modules/mod_proxy_http2.so**
- **LoadModule proxy_wstunnel_module modules/mod_proxy_wstunnel.so**
- **LoadModule ssl_module modules/mod_ssl.so**


1.📦 Install dependencies
```
composer install
```
2.🛠️ Create a copy of the .env file
```
cp .env.example .env
```
3.🔑 Generate the application key
```
php artisan key:generate
```
4.📦 install node_modules
```
npm install
```
5.🚀 Compile assets with Tailwind CSS
```
npm run dev
```
6.🗄️ Set up the database
```
php artisan migrate
```
7.🔗 Create symbolic link for storage
```
rm public/storage
php artisan storage:link
```
8.🗄️ Seed Admin credantials with roles and permessions 
```
php artisan db:seed --class=AdminSeeder
```
9.🗄️ Seed the SMTP configuration important 
```
php artisan db:seed --SmtpSeeder
```
10.💻 Run the application
```
php artisan serve
```
11.Enable Reverb Broadcasting (optional)
```
BROADCAST_ENABLED=true
BROADCAST_DRIVER=reverb

REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http


VITE_REVERB_ENABLED=true
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="blogpost.test" 
VITE_REVERB_PORT=443
VITE_REVERB_SCHEME=https

```
then start reverb and queue
```
php artisan queue:work
php artisan reverb:start

```
12.🚀 For better performance configure Redis and enable Cache to true
```
CACHE_ENABLED=true
REDIS_CLIENT=predis

```
13. TNTSearch Scout (optional)
```
SCOUT_DRIVER=tntsearch
SCOUT_QUEUE=true
SCOUT_QUEUE_CONNECTION=redis
SCOUT_QUEUE_NAME=scout

TNTSEARCH_FUZZINESS=true
TNTSEARCH_AS_YOU_TYPE=false
TNTSEARCH_BOOLEAN=false
TNTSEARCH_MAX_DOCS=500
```
Index your posts, tags, categories, user, news (optional) run the following command:
```
php artisan scout:import "App\Models\Post"
php artisan scout:import "App\Models\Scraping\ScrapedPost"
php artisan scout:import "App\Models\Category"
php artisan scout:import "App\Models\Hashtag"
php artisan scout:import "App\Models\User"
```
Then run queue for tntsearch:
```
php artisan queue:work --queue=scout 
```

14. N8N Whatsapp Notificcation (optional)

Myblog4u will notify admins of pending approval articles through Whatsapp. You'll need to set up a Whatsapp Business API. Then, configure N8N workflow to send new pending article messages.

download the laravel_to_whatsapp workflow [click here to download](https://gist.github.com/ibrahimjml/46a06b3c8b821513ed52c86cc8725b07)

## Docker
```
docker pull n8nio/n8n:latest

docker run -d \
  --name n8n \
  --restart unless-stopped \
  -p 127.0.0.1:5678:5678 \
  -e WEBHOOK_URL=https://yourdomain.com \
  -e EXECUTIONS_DATA_PRUNE=true \
  -e EXECUTIONS_DATA_MAX_AGE=72 \
  -v n8n_data:/home/node/.n8n \
  n8nio/n8n:latest
```
If using Nginx, or Apache try reverse proxy to access.

Setup n8n environment config 

```
N8N_WEBHOOK_ENABLED=
N8N_WEBHOOK_URL=
N8N_WEBHOOK_PHONE=
```

## Testing Notifications
- **Configure new smtp in adminpanel->settings->smtp settings**
- **Test it by sending test mail**

## Test the Application
- **Copy .env.testing.example to .env.testing**
- **Run the commands:**
```
php artisan key:generate --env=testing
```
```
php artisan migrate --seed --env=testing
```
```
php artisan serve --env=testing
```

## Production

MyBlog4u runs on a production stack built for reliability and performance:

- ☁️ **Hosting** — DigitalOcean, served via Nginx
- 📦 **Object storage** — Cloudflare R2 for file uploads
- 🔔 **Real-time notifications** — Laravel Reverb
- ⚙️ **Queue management** — Supervisor for handling background jobs
- 📧 **Email delivery** — Brevo
- ⚡ **Caching** — Redis
