<?php

namespace QueryMule\Sql\Operator;


/**
 * Class Comparison
 * @package QueryMule\Builder\Sql\Generic
 */
class Comparison
{
    /**
     * @var null
     */
    private $operator = null;

    /**
     * @param $operator
     */
    private function __construct($operator)
    {
        $this->operator = $operator;
    }

    public static function equalTo()
    {
        return new self('=?');
    }

//    public static function like()
//    {
//        return new self('LIKE ?');
//    }

    public static function greaterThan()
    {
        return new self('>?');
    }

    public static function lessThan()
    {
        return new self('<?');
    }

    public static function greaterThanEqualTo()
    {
        return new self('>=?');
    }

    public static function lessThanEqualTo()
    {
        return new self('<=?');
    }

    public static function notEqualTo()
    {
        return new self('<>?');
    }

    public function build()
    {
        return $this->operator;
    }
}