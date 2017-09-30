<?php

namespace QueryMule\Query\Sql;

/**
 * Class Query
 * @package QueryMule\Query\Sql
 */
trait Query
{
    /**
     * @var array
     */
    private $sql = [];

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param $type
     * @return string|null
     */
    private function queryGet($type)
    {
        return !empty($this->sql[$type]) ? $this->sql[$type] : null;
    }

    /**
     * @param $type
     * @param Sql $sql
     */
    private function queryAdd($type, Sql $sql)
    {
        $this->sql[$type] = !empty($this->sql[$type]) ? $this->sql[$type] . ' ' . $sql->sql() : $sql->sql();

        foreach($sql->parameters() as $key => $parameter){
            $this->parameters[$type][] = $parameter;
        }
    }

    /**
     * @return void
     */
    private function queryReset()
    {
        $this->sql = [];
        $this->parameters = [];
    }

    /**
     * @param array $buildOrder
     * @return \QueryMule\Query\Sql\Sql
     */
    private function queryBuild(array $buildOrder)
    {
        $sql = '';
        $parameters = [];
        foreach($buildOrder as $type){
            if(!empty($this->sql[$type])) {

                $sql .= !empty($sql) ? ' '.$this->sql[$type] : $this->sql[$type];
                if(!empty($this->parameters[$type])) {
                    foreach ($this->parameters[$type] as $parameter) {
                        $parameters[] = $parameter;
                    }
                }
            }
        }
        return new Sql($sql,$parameters);
    }
}