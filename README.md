## How to setup local environment via docker and sail
1. docker run --rm --interactive --tty \
   --volume $PWD:/app \
   --user $(id -u):$(id -g) \
   --workdir /app \
   laravelsail/php81-composer:latest composer install

2. copy .env.example to .env
3. vendor/bin/sail up
4. vendor/bin/sail artisan migrate:fresh
5. vendor/bin/sail artisan db:seed
6. vendor/bin/sail artisan key:generate
7. vendor/bin/sail npm i
8. vendor/bin/sail npm run dev
9. see localhost. Test user credentials: test@example.com:password
