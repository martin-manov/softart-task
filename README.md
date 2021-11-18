create a .env file

```sh
APP_ENV=dev
DATABASE_URL="mysql://<db_user>:<db_pass>@127.0.0.1:3306/<db_name>"
```

run commands
```
composer install
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate