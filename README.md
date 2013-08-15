Steps to set up Judge on your machine:

1. Make sure you have PHP 5.4 with PDO and SQlite enabled.
2. Have [Composer](http://getcomposer.org) installed and in your PATH.
3. Run ```composer install``` 
4. Run ```php artisan asset:publish```
5. Run ```php artisan migrate```
6. Run ```php artisan db:seed```
7. Run ```php artisan serve``` to start the server.

NOTE: For us developers, if your latest pull included a new migration, make sure that you run
```composer dumpautoload``` so that Laravel can find the new migration.
