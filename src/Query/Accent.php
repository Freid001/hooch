<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query;

/**
 * Class Accent
 * @package Redstraw\Hooch\Query\Sql
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
    public function append(string $string, bool $delimiter = false)
    {
        if($this->ignoreAccentSymbol){
            return $string;
        }

        if($delimiter){
            return array_reduce(explode(".", $string), function($transformedString, $stringPart){
                if(!empty($transformedString)){
                    return $transformedString.".".$this->append($stringPart);
                }

                return $this->append($stringPart);
            }, "");
        }

        return $this->symbol.$string.$this->symbol;
    }
}
