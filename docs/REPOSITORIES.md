# Repositories

## Table

### Class
```php
class Book extends AbstractTable
{
    public function getName()
    {
        return 'book';
    }

    public function filterById($id)
    {
        return $this->filter->where('b.id', '=?', $id);
    }
}
```

### Instantiate
```php
// instantiate table.
$book = new Book($database->dbh('your_db_config_key')->driver());
````

### Filters
```php
// filter by id.
$book->filterById(1);
$query = $book->select($cols, $alias)->build();
```