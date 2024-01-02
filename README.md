## How to setup local environment via docker and sail

1. docker-compose up -d
2. docker exec xml_to_excel-laravel.test-1 composer install 
3. copy .env.example to .env
4. vendor/bin/sail up
5. vendor/bin/sail artisan migrate:fresh
6. vendor/bin/sail artisan db:seed
7. vendor/bin/sail artisan key:generate
8. vendor/bin/sail npm i
9. vendor/bin/sail npm run dev
10. see localhost. Test user credentials: test@example.com:password
