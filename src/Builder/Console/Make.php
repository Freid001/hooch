<?php namespace freidcreations\QueryMule\Builder\Console;

/**
 * Class Make
 * @package freidcreations\QueryMule\Builder\Console
 */
class Make
{
    /**
     * Connections
     * @param $connections
     * @return string
     */
    public static function connections($connections)
    {
        return  "<?php namespace QueryMule; \n\n" .
            "/** \n" .
            " * Class Connections \n" .
            " * @package QueryMule \n" .
            " */ \n" .
            "class Connections\n" .
            "{\n" .
            "   /** \n" .
            "    * Database \n" .
            "    * @return array \n" .
            "    */ \n" .
            "    public static function database()\n" .
            "    {\n".
            "        return [\n" .
            "". $connections .
            "        ];\n" .
            "    }\n" .
            "}\n";
    }

    /**
     * Migration
     */
    public static function migration($database, $name)
    {
        return  "<?php namespace QueryMule\\" . $database . "\\Migration; \n\n" .
            "/** \n" .
            " * Class " . $name . " \n" .
            " * @package QueryMule\\" . $database . "\\Migration \n" .
            " */ \n" .
            "class " . $name . "\n" .
            "{\n" .
            "   /** \n" .
            "    * Migrate \n" .
            "    */ \n" .
            "    public static function migrate()\n" .
            "    {\n" .
            "        //TODO: Add migration. \n" .
            "    }\n" .
            "\n".
            "   /** \n" .
            "    * Rollback \n" .
            "    */ \n" .
            "    public static function rollBack()\n" .
            "    {\n" .
            "        //TODO: Add roll back. \n" .
            "    }\n" .
            "}\n";
    }

    /**
     * Table
     */
    public static function table($database, $className, $tableName)
    {
        return  "<?php namespace QueryMule\\" . $database . "\\Table; \n" .
        "use freidcreations\\QueryMule\\Query\\AbstractTable; \n\n" .
        "/** \n" .
        " * Class " . $className . " \n" .
        " * @package QueryMule\\" . $database . "\\Table \n" .
        " */ \n" .
        "class " . $className . " extends AbstractTable\n" .
        "{\n" .
        "   /**\n" .
        "    * @var string\n" .
        "    */\n" .
        "   private static \$table = '" . $tableName . "'; \n\n" .
        "   /** \n" .
        "    * Query \n" .
        "    */ \n" .
        "    public static function query()\n" .
        "    {\n" .
        "        parent::table(self::\$table);\n" .
        "    }\n" .
        "}\n";
    }
}