<?php

namespace QueryMule\Query\Sql;

/**
 * Class Accent
 * @package QueryMule\Query\Sql
 */
trait Accent
{
    /**
     * @var string
     */
    private $accent;

    /**
     * @param $accent
     */
    private function setAccent($accent)
    {
        $this->accent = $accent;
    }

    /**
     * @param $string
     * @return string
     */
    private function addAccent($string)
    {
        $items = explode('.',$string);

        $return = '';
        foreach($items as $key => $item){
            $dot = ($key != (count($items)-1)) ? '.' : '';
            $return .= $this->accent . $item . $this->accent . $dot;
        }
        return $return;
    }
}