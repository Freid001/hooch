# Query Builder

## Select

### Cols
```php
$query = $driver->select()->cols(['id','name'],'b')->build();
````

### From
```php
$book = new Book($database->dbh('your_db_config_key')->driver());
$query = $driver->select()->cols(['id','name'],'b')->from($book,'b')->build();
````

### Where
```php
$book = new Book($database->dbh('your_db_config_key')->driver());
$query = $driver->select()->cols(['id','name'],'b')->from($book,'b')->where('b.id','=?',1)->build();
````

## Insert

## Update

## Delete