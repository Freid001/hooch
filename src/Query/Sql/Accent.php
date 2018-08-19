<?php

namespace QueryMule\Query\Sql;

/**
 * Class Accent
 * @package QueryMule\Query\Sql
 */
class Accent
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
     * @param string $accent
     */
    final public function setAccent($accent)
    {
        $this->accent = $accent;
    }

    /**
     * @param bool $ignore
     */
    final public function ignoreAccentSymbol($ignore = true)
    {
        $this->ignoreAccentSymbol = $ignore;
    }

    /**
     * @param string $string
     * @param bool $delimiter
     * @return string
     */
    final public function addAccent($string,$delimiter = false)
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
