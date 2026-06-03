<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel
# Laravel Blog Application
## 📸 Screenshots

👉 [View Screenshots](SCREENSHOTS.md)

---

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

### 🧑‍💼 Admin Control Panel

  - **Dashboard Pages:** Realtime charts tracking new users, published posts, with fully responsive and AJAX based.
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
  -  **Email and In-app Notifications:** Creators will recieve mutiple notifications type Views,Mentions,Posted,Following,Requested Following,Reports and by emails.

### 🧱 Architecture & Performance

  - **Clean architecture using:** Service Layer,Observers,Builders,Repositories and Decorators Caches, DTOs.
  - **Dynamic XML Sitemaps**


## 🔥🔥 Upcoming Features.

  - **AI multi providers management**
  - **AI auto paste content**
  - **Paywall system**

## INSTALLATION
- **Requirements extensions:**
- **PHP 8.3**
- **Imagick**
- **intl**
- **tokenizer**

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
php artisan db:seed --AdminSeeder
```
9.🗄️ Seed the SMTP configuration important 
```
php artisan db:seed --SmtpSeeder
```
10.💻 Run the application
```
php artisan serve
```
11.🚀 For better performance configure Redis and enable Cache to true
```
CACHE_ENABLED=true
REDIS_CLIENT=predis
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
## Admin Login
`Use these credentails to log in as admin`


- Email: admin@mail.ru.
- Pass : adminadmin123.

## Laravel RESTful API for this project <img height="20" src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/1200px-Laravel.svg.png" />

All Requests start with http://127.0.0.1:8000/api

`Login`

- `POST /api/login` - login to get token access.

 `posts`
- `GET /api/blog` - Get all posts.No authentication required.
- `GET /api/posts/{post}` - Get single post.No authentication required.
- `POST /api/create` - Create new post , authentication required.
- `PUT /api/post/update/{post}` - Update authorized post, authentication required.
- `DELETE /api/post/{post}` - Delete authorized post, authentication required.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
