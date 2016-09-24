<?php namespace QueryMule\Library\Table;
use freidcreations\QueryMule\Query\Table\AbstractTable;

/** 
 * Class Book
 * @package QueryMule\MyDatabase\Table 
 */ 
class Book extends AbstractTable
{
    /**
     * @var string
     */
    private static $databaseConnectionKey = 'Library';

    /**
     * @var string
     */
    private static $table = 'book';

    /**
     * Query
     * @return AbstractTable
     */
    public static function query() : AbstractTable
    {
        return parent::table(
            self::$databaseConnectionKey,
            self::$table
        );
    }
}
