## How to setup local environment via docker and sail

1. copy .env.example to .env
2. docker compose run --no-deps --rm laravel.test composer install 
3. vendor/bin/sail up
4. vendor/bin/sail artisan migrate:fresh
5. vendor/bin/sail artisan db:seed
6. vendor/bin/sail artisan key:generate
7. vendor/bin/sail npm i
8. vendor/bin/sail npm run dev
9. see localhost. Test user credentials: test@example.com:password
