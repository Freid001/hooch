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
    protected $accent;

    /**
     * @var bool
     */
    protected $ignoreAccentSymbol = false;

    /**
     * @param $accent
     */
    final protected function setAccent($accent)
    {
        $this->accent = $accent;
    }

    /**
     * @param bool $ignore
     */
    final protected function ignoreAccentSymbol($ignore = true)
    {
        $this->ignoreAccentSymbol = $ignore;
    }

    /**
     * @param $string
     * @param bool $delimiter
     * @return string
     */
    final protected function addAccent($string,$delimiter = false)
    {
        if($this->ignoreAccentSymbol){
            return $string;
        }

        if($delimiter){
            $strings = explode($delimiter,$string);

            $returnString = '';
            foreach($strings as $string){
                $returnString .= !empty($returnString) ? $delimiter . $this->addAccent($string) : $this->addAccent($string);
            }

            return $returnString;
        }

        return $this->accent.$string.$this->accent;
    }
}









