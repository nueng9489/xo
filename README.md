# xo
XO Game

How to install 

```
git clone https://github.com/nueng9489/xo.git
```

Config you database connection in file .env for excample

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=xo
DB_USERNAME=root
DB_PASSWORD=
```

Go to project and run command

```
composser install
```

Migrate database use command

```
php artisan migrate
```

Run web application command

```
php artisan serv
```
