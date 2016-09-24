<?php namespace QueryMule\Library\Migration;
use QueryMule\Library\Table\Book;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDataType;

/**
 * Class Migration_1473002387_MyDatabase
 * @package QueryMule\MyDatabase\Migration
 */
class Migration_1473002387_MyDatabase
{
   /** 
    * Migrate
    */ 
    public static function migrate()
    {
        Book::query()->create(function(TableColumnDataType $table){
            $table->int('id')->notNull()->autoIncrement();
            $table->int('author_id')->notNull();
            $table->varchar('name',225)->notNull();
            $table->text('description')->nullable();
            $table->decimal('price',2,1)->notNull();
            $table->boolean('in_stock')->notNull()->default(0);
            $table->varchar('isbn_number',15)->notNull()->comment("International Standard Book Number.");

            $table->primaryKey([
                'id',
                'author_id'
            ]);

            $table->uniqueKey('book',[
                'name',
                'isbn_number'
            ]);

            $table->index([
                'isbn_number'
            ]);
        })->execute();
    }

    /**
     * Rollback
     */
    public static function rollBack()
    {
        //TODO: Add roll back.
        //Book::query()->drop();
    }
}
