# laravel-eloquent-system-logger

## It keeps user-based logs of creation, update and deletion of Laravel ORM Models.

# Install
## 00 - composer require enesekinci/eloquent-system-logger
## 01 - add to providers config/app.php  => EnesEkinci\EloquentSystemLogger\EloquentSystemLoggerServiceProvider::class,
## 02 - example using;
    
     use EnesEkinci\EloquentSystemLogger\Service\LoggerService;
     class About extends Model
       {
        use LoggerService;
   
