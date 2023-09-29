# Book App

Sistēma vēlas uzglabāt datus par grāmatu veidiem un to popularitāti


## Installation


```bash
composer install
```

Nomainīt nosaukumu failam .env.example

`\.env.example` -> `\.env`

Šajā failā jāaizpilda datubāzes mainīgie

`DB_CONNECTION`,`DB_HOST`,`DB_PORT`,`DB_DATABASE`,`DB_USERNAME`,`DB_PASSWORD`

Jāuzģenerē `APP_KEY`
```bash
php artisan key:generate
```

Lai izveidotu datubāzes tabulas jāizpilda ši komanda

```bash
php artisan migrate
```

Lai izveidotu tabulas un aizpildītu tās ar informāciju jāizpilda šī komanda

```bash
php artisan migrate:fresh --seed
```
## API Reference

#### Dabūt top 10 grāmatas

```http
  GET /api/topTen
```