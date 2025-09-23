# Staging env

```
git clone https://github.com/rhpz/larvael.git
cd larvael
cp .env.example .env
composer install
php artisan key:generate

php artisan migrate:fresh

npm install
npm run dev   # or: npm run build for production assets
php artisan serve
```