# Connections

## Database

### Config
```php
// configure a database connection.
$database = new Database([
    // configure mysql pdo connection.
    'your_db_pdo_mysql_key' => [
        DatabaseHandler::DATABASE_DRIVER => 'mysql',
        DatabaseHandler::DATABASE_HOST => '127.0.0.1',
        DatabaseHandler::DATABASE_DATABASE => 'your_db_name',
        DatabaseHandler::DATABASE_USER => 'your_db_user',
        DatabaseHandler::DATABASE_PASSWORD => 'your_db_password',
        DatabaseHandler::DATABASE_ADAPTER => DatabaseHandler::ADAPTER_PDO
    ],
        
    // configure pgsql pdo connection.
    'your_db_pdo_pgsql_key' => [
        DatabaseHandler::DATABASE_DRIVER => 'pgsql',
        DatabaseHandler::DATABASE_HOST => '127.0.0.1',
        DatabaseHandler::DATABASE_DATABASE => 'your_db_name',
        DatabaseHandler::DATABASE_USER => 'your_db_user',
        DatabaseHandler::DATABASE_PASSWORD => 'your_db_password',
        DatabaseHandler::DATABASE_ADAPTER => DatabaseHandler::ADAPTER_PDO,
    ],
    
    // configure sqlite pdo connection.
    'your_db_pdo_sqlite_key' => [
        DatabaseHandler::DATABASE_DRIVER => 'sqlite',
        DatabaseHandler::DATABASE_DATABASE => 'your_db_name',
        DatabaseHandler::DATABASE_PATH_TO_FILE => __DIR__.'/path/to/sqlite',
        DatabaseHandler::DATABASE_ADAPTER => DatabaseHandler::ADAPTER_PDO,
    ],
    
    // configure mysqli connection.
    'your_db_mysqli_key' => [
        DatabaseHandler::DATABASE_DRIVER => 'mysql',
        DatabaseHandler::DATABASE_HOST => '127.0.0.1',
        DatabaseHandler::DATABASE_DATABASE => 'your_db_name',
        DatabaseHandler::DATABASE_USER => 'your_db_user',
        DatabaseHandler::DATABASE_PASSWORD => 'your_db_password',
        DatabaseHandler::DATABASE_ADAPTER => DatabaseHandler::ADAPTER_MYSQLI
    ]
]);
````

### Handler
```php
// get database handler.
$dbh = $database->dbh('your_db_config_key');

// get connection driver.
$driver = $dbh->driver();
````