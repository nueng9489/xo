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
composer install
```

Migrate database use command

```
php artisan migrate
```

Run web application command

```
php artisan serv
```

The Logic of website application

1. Client can put number of table and system will be gennerate the the table for play request more then 3*3.
2. The system will gennerate the way to win template keep into variable.
3. Every time when client click into empty feild will be get the value for x, o player and map with winner template if can map to template that player will get winner.
4. After get winner the system will trigger to send data to save history to database.
5. On tab history you can see all history query 10rows per page.
6. You can see replay on history tab.

Play with bot
- The condition for play with bot right now just random to clik on empty feild don't have smart logic but we can improve them to smart logic that on the furture.

Detail project
I use Laravel on this project for manage database and render CRUD.
On game I use jQuery and Javascript to create Game application and use CSS and Bootstrap for style.
