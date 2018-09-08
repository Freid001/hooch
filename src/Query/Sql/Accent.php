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
    private $symbol;

    /**
     * @var bool
     */
    private $ignoreAccentSymbol = false;

    /**
     * @param string $symbol
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @param bool $ignore
     */
    public function ignore($ignore = true)
    {
        $this->ignoreAccentSymbol = $ignore;
    }

    /**
     * @param string $string
     * @param bool $delimiter
     * @return string
     */
    public function append($string, $delimiter = false)
    {
        if($this->ignoreAccentSymbol || !is_string($string)){
            return $string;
        }

        if($delimiter){
            $strings = explode($delimiter,$string);

            $returnString = '';
            foreach($strings as $string){
                $returnString .= !empty($returnString) ? $delimiter . $this->append($string) : $this->append($string);
            }

            return $returnString;
        }

        return $this->symbol.$string.$this->symbol;
    }
}
