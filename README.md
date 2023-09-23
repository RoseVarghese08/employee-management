cd employee-management-system
composer install
cp .env.example .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=emp_manage
DB_USERNAME=
DB_PASSWORD=

php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
npm run build
