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
     * @var bool
     */
    private $ignoreAccentSymbol = false;

    /**
     * @param $accent
     */
    private function setAccent($accent)
    {
        $this->accent = $accent;
    }

    /**
     * @param bool $ignore
     */
    private function ignoreAccentSymbol($ignore = true)
    {
        $this->ignoreAccentSymbol = $ignore;
    }

    /**
     * @param $string
     * @param bool $delimiter
     * @return string
     */
    private function addAccent($string,$delimiter = false)
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









