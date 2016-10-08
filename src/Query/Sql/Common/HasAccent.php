<?php  namespace freidcreations\QueryMule\Query\Sql\Common;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Builder\Connection\Database;

/**
 * Class HasAccent
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
trait HasAccent
{
    /**
     * @var Table
     */
    private $table;

    /**
     * Make Table
     * @param Table $table
     * @return $this
     */
    public function makeAccent(QueryBuilderInterface $builder)
    {
        $this->table = $builder->table();
        return $this;
    }

    /**
     * Accent
     * @param $string
     * @param bool|false $tableName
     * @return string
     */
    public function accent($string, $tableName = false)
    {
        $items = explode('.',$string);
        $return = '';
        foreach($items as $key => $item){
            $dot = ($key != (count($items)-1)) ? '.' : '';

            switch($this->table->dbh()->driver())
            {
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