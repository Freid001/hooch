<img src="https://raw.githubusercontent.com/Freid001/query-mule/refactor/queryMule.png">

A query builder and database access layer (DAL) built on top of PDO. This package providers a query toolkit which aims to make it easy to create complex and efficient queries. Developers can use this package on its own or in conjunction with a framework.

###Features
* Build select, insert, update and delete queries with ease.
* Store and recall query logic.
* Chain query statements.
* Switch database connections with ease.
* Supports MySQL and PostgreSQL.

##Installation
###Composer
Add this json to your composer.json file.
```json
"repositories": [
        {
            "url": "https://github.com/freidcreations/freid-query",
            "type": "git"
        }
    ],
"require": {
        "freidcreations/freidQuery" : "1.0"
},
```
then update your dependencies with composer update

##Database
###Config connection
To config a new database connection goto config/database.php and add your config details to the connections array. 
```php
public static function connections(){
        return [ 'local' => [ 'driver'    => 'mysql',
                              'host'      => 'localhost',
                              'database'  => '',
                              'username'  => '',
                              'password'  => '' ],
                 'staging' => [ 'driver'    => 'mysql',
                                'host'      => '',
                                'database'  => '',
                                'username'  => '',
                                'password'  => '' ],
                 'anotherDatabase' => [ 'driver'    => 'mysql',
                                        'host'      => '',
                                        'database'  => '',
                                        'username'  => '',
                                        'password'  => '' ],
                  'live'  => [ 'driver'    => 'mysql',
                               'host'      => '',
                               'database'  => '',
                               'username'  => '',
                               'password'  => '' ]
        ];
}
```
###Detect environment (Default connection)
The detect environment function found in config/database.php is use by the database handler to determind which connection to apply by defualt. Add any additional enviroments you want to detech to this function and return the database config key which needs to be used.
```php
public static function detectEnvironment(){
        if( $_SERVER["SERVER_ADDR"] == '127.0.0.1' || $_SERVER["SERVER_ADDR"] == '::1' ) {
            return 'local';
        }else if( $_SERVER["SERVER_ADDR"] == [IP address of your staging server] ){
            return 'staging';
        }else {
            return 'live';
        }
}
```

###Switch connection
Pass a predefined database config key to switchDBConnection(), when running a query for another database. Once the query has finished executing the default database connection will be reapplied. To make the switched DB connection stick, pass a second parameter marked true. This will ensure that all queries after this point will run on the new database connection, until either closeDBconnection() or switchDBConnection() are called again.
```php
$result = table('tableNameInNewDatabase')->select()->cols()->switchDBConnection('anotherDatabase')->many();
```

###Close connection
Use closeDBconnection() to disable the specified connection and reapply the default connection.
```php
$result = table('book')->select()->cols()->closeDBConnection('anotherDatabase')->many();
```

##Table
###Using a table class
A table class will enable you to use the query object for CRUD operations. It can also store query logic (see below) to reduce duplication. To create a table class simply extend query and implement the interface.
```php
class book extends query
{
  private static $name = 'book';`
  
  public static function query(){
        return self::table( self::$name );
  }

}
```

####Storing query logic
The table class (see above) allows you to store any query logic you may need to reuse on a regular bases. Simple add a new static function to your table class, and call self::query()->statement. This ensures that we are able to use this function for the active query statement.  
```php
public static function id( $bookId ){
   return self::query()->statement->where( 'id', '=?', $accountId );
}
```

 Use the recall function to apply the stored statement in a query chain. 
 ```php
 $result = book::query()->select()->cols()->recall( book::id(1) )->first();
 ```
 
You will need to first inherit the active query if you need to recall a function from another table class.
```php
$result = book::query()->select()->cols()->leftJoin( [ 'a' => author::query()->table ], function(){
            $join[] = book::query()->statement->on( 'b.id', '=', 'a.book_id' );
            return $join;
        })->recall( author::query()->inherit( book::query() )->recall( author::id(1) ) );
```


###Using simple table
Alternativly you can query your tables on the fly. In this case pass your table name into the table function of the simple object.
```php
$result = simple::table('book')->select()->cols()->many();
```

##Query

###Insert

###Select
```php
//Method signature
$query = book::query()->select( $distinct );

//SELECT
$query = book::query()->select();

//SELECT distinct
$query = book::query()->select( true );
```

####Cols
```php
//Method signature
$query->cols( $cols, $alias, $table );

//SELECT * from book
$query->cols();

//SELECT id from book
$query->cols( [ 'id' ] );

//SELECT id from book using b as an alias for the book table
$query->cols( [ 'id' ], 'b' );
```

####Functions

#####Count

#####Sum

#####Average

#####Min

#####Max

#####Group Concat

####From

####Join

#####Inner Join
```php
//Method signature
$query->innerJoin( $join, $cols );

//JOIN on author AS a
$query->innerJoin( [ 'a' => author::query()->table ] );

//JOIN on author AS a COLS author
$query->innerJoin( [ 'a' => author::query()->table ], [ 'author' ] );
```

#####Left Join
```php
//Method signature
$query->leftJoin( $join, $on, $cols );

//LEFT JOIN on author AS a ON author.id = book.author_id
$query->leftJoin( [ 'a' => author::query()->table ], [ 'a.id', '=', 'b.author_id' ] );

//LEFT JOIN on author AS a ON author.author = "Rudyard Kipling"
$query->leftJoin( [ 'a' => author::query()->table ], [ 'a.author', '=?', "Rudyard Kipling" ] );

//LEFT JOIN on author AS a ON book.author_id = author.id AND author.author = "Rudyard Kipling"
$query->leftJoin( [ 'a' => author::query()->table ], function(){
        $join[] = book::query()->statement->on( 'b.author_id', '=', 'a.id' )
        $join[] = book::query()->statement->on( 'a.author', '=?', "Rudyard Kipling" )
        return $join; 
});

//LEFT JOIN on author AS a ON author.id = book.author_id COLS author
$query->leftJoin( [ 'a' => author::query()->table ], [ 'a.id', '=', 'b.author_id' ], [ 'author' ] );

//LEFT JOIN on author AS a ON author.id = book.author_id COLS author AS writer 
$query->leftJoin( [ 'a' => author::query()->table ], [ 'a.id', '=', 'b.author_id' ], ['writer' => 'author'] );
```

#####Right Join
```php
//Method signature
$query->rightJoin( $join, $on, $cols );

//RIGHT JOIN on author AS a ON author.id = book.author_id
$query->rightJoin( [ 'a' => author::query()->table ], [ 'a.id', '=', 'b.author_id' ] );

//RIGHT JOIN on author AS a ON author.author = "Rudyard Kipling"
$query->rightJoin( [ 'a' => author::query()->table ], [ 'a.author', '=?', "Rudyard Kipling" ] );

//RIGHT JOIN on author AS a ON book.author_id = author.id AND author.author = "Rudyard Kipling"
$query->rightJoin( [ 'a' => author::query()->table ], function(){
        $join[] = book::query()->statement->on( 'b.author_id', '=', 'a.id' )
        $join[] = book::query()->statement->on( 'a.author', '=?', "Rudyard Kipling" )
        return $join; 
});

//RIGHT JOIN on author AS a ON author.id = book.author_id COLS author
$query->rightJoin( [ 'a' => author::query()->table ], [ 'a.id', '=', 'b.author_id' ], [ 'author' ] );

//RIGHT JOIN on author AS a ON author.id = book.author_id COLS author AS writer 
$query->rightJoin( [ 'a' => author::query()->table ], [ 'a.id', '=', 'b.author_id' ], ['writer' => 'author'] );
```

####Union



####Where

```php
//Method signature
$query->where( $column, $operator, $value, $clause );

//WHERE id = 1 
$query->where('id','=?','1');

//WHERE book LIKE "The Jungle Book"
$query->where('book','LIKE ?',"%The Jungle Book%");

//WHERE book NOT LIKE "The Jungle Book"
$query->where('book','NOT LIKE ?',"%The Jungle Book%"); 

//WHERE book LIKE "The Jungle Book" AND category = "Fiction"
$query->where('book','LIKE ?','%The Jungle Book%')->where('category','=?',"Fiction");
 
//WHERE book LIKE "The Jungle Book" OR id = 1 
$query->where('book','LIKE ?','%The Jungle Book%')->orWhere('id','=?','1');

//WHERE author.id = 1 AND ( book.id = 1 OR book.id = 3 );
$query->where('a.id','=?','1')->where(function(){
        $where[] = book::query()->statement->where('b.id','=?','1');
        $where[] = book::query()->statement->orWhere('b.id','=?','3');
        return $where;
})

//WHERE author.id = 1 AND ( book.id = 1 OR book.id = 3 ) AND ( book.name = "The Jungle Book" OR book.name = "The Elephant's Child" );
$query->where('a.id','=?','1')->where(function(){
        $where[] = book::query()->statement->where('b.id','=?','1');
        $where[] = book::query()->statement->orWhere('b.id','=?','3');
        return $where;
})->where(function(){
        $where[] = book::query()->statement->where('b.name','=?',"The Jungle Book");
        $where[] = book::query()->statement->orWhere('b.name','=?',"The Elephant's Child");
        return $where;
});

//WHERE ( category = "Fiction" AND ( book.name = "The Jungle Book" OR book.name = "The Elephant's Child" ) );
$query->where(function(){
        $where[] = book::query()->statement->where('b.category','=?','Fiction');

        foreach( $book->statement->where(function(){
            $where[] = book::query()->statement->where('b.name','=?',"The Jungle Book");
            $where[] = book::query()->statement->orWhere('b.name','=?',"The Elephant's Child");
            return $where;
        }) as $key => $row ){
            $where[] = $row;
        }
        return $where;
});
```

####Where In
```php
//Method signature
$query->whereIn( $column, $in )

//WHERE book.id IN [1,2,3]
$query->whereIn( 'b.id', [ 1,2,3 ] );

//OR book.id IN [1,2,3]
$query->whereIn( 'b.id', [ 1,2,3 ], 'OR' );
```

####Group
```php
//Method signature
$query->group( $columns );

//GROUP
$query->group( [ 'id', 'book' ] );
```

####Order
```php
//Method signature
$query->orderCol( $columns );

//ORDER BY id desc
$query->orderCol( [ 'id' => 'DESC' ] );

//ORDER BY id desc AND book desc
$query->orderCol( [ 'id' => 'DESC', 'book' => 'DESC' ] );
```

####Limit
```php
//Method signature
$query->limit( $offset );

//LIMIT
$query->limit( 2 );
$query->limit( 2, 1 );
```

###Update

###Delete













