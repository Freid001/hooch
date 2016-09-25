<?php include_once('vendor/autoload.php');

use QueryMule\Library\Table\Book;

$e = Book::query()->create(function(){

})->build();

var_dump($e);