<?php  namespace freidcreations\QueryMule\Query\Sql\Common;
use freidcreations\QueryMule\Builder\Sql\Sql;

/**
 * Class HasBuilder
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
trait HasBuilder
{
    /**
     * @var QueryBuilderInterface
     */
    private $builder;

    /**
     * @var Sql
     */
    private $sql;

    /**
     * Make Builder
     * @param QueryBuilderInterface $builder
     */
    public function makeBuilder(QueryBuilderInterface $builder)
    {
        $this->builder = $builder;
        $this->sql = new Sql();
    }

    /**
     * Execute
     * @return \PDOStatement
     * @throws \Exception
     */
    public function execute()
    {
        //Build SQL
        $sql = $this->build();

        //Execute query
        $query = $this->builder->table()->dbh()->connection()->prepare($sql->sql());
        $query->execute($sql->parameters());

        return $query;
    }

    /**
     * Build
     * @return Sql
     */
    public function build() : Sql
    {
        //Build order for query
        $buildOrder = [
            QueryBuilderInterface::CREATE_TABLE,
            QueryBuilderInterface::ALTER_TABLE,
            QueryBuilderInterface::RENAME_TABLE,
            QueryBuilderInterface::DROP_TABLE,
            QueryBuilderInterface::SELECT,
            QueryBuilderInterface::INSERT,
            QueryBuilderInterface::UPDATE,
            QueryBuilderInterface::DELETE,
            QueryBuilderInterface::COLS,
            QueryBuilderInterface::INTO,
            QueryBuilderInterface::VALUES,
            QueryBuilderInterface::FROM,
            QueryBuilderInterface::JOIN,
            QueryBuilderInterface::WHERE,
            QueryBuilderInterface::GROUP,
            QueryBuilderInterface::ORDER,
            QueryBuilderInterface::HAVING,
            QueryBuilderInterface::LIMIT
        ];

        //Build Query
        $sql = new Sql();
        foreach( $buildOrder as $index => $type){
            $parameters = [];
            foreach(Sql::raw($type)->parameters() as $key => $parameter){
                $parameters[] = $parameter;
            }

            //Build query
            $sql->add(Sql::raw($type)->sql(),$parameters);
        }

        return $sql;
    }

    /**
     * Reset SQL
     * @return $this
     */
    public function reset()
    {
        Sql::reset();
        return $this;
    }
}