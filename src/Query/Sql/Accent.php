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
        return $this->accent.$string.$this->accent;
    }
}