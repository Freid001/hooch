<?php  namespace freidcreations\QueryMule\Builder\Sql\Common;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Builder\Connection\Database;

/**
 * Class HasAccent
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
trait HasAccent
{
    /**
     * Add accent
     * @param Table $table
     * @param $string
     * @param bool|false $tableName
     * @return string
     */
    public function addAccent(Table $table,$string, $tableName = false)
    {
        $items = explode('.',$string);
        $return = '';
        foreach($items as $key => $item){
            $dot = ($key != (count($items)-1)) ? '.' : '';

            switch($table->dbh()->driver()){
                case Database::DRIVE_POST_GRE_SQL:
                    if($tableName) {
                        $return .= '"' . $item . '"';
                    }else {
                        $return .= '"' . $item . '"' . $dot;
                    }
                    break;

                default:
                    $return .= '`' . $item . '`' . $dot;
            }
        }
        return $return;
    }
}