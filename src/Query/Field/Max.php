<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Field;


use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Sql;

/**
 * Class Max
 * @package Redstraw\Hooch\Query\Sql
 */
class Max implements FieldInterface
{
    /**
     * @var Accent
     */
    private $accent;

    /**
     * @var string
     */
    private $column;

    /**
     * Column constructor.
     * @param string $column
     */
    public function __construct(string $column)
    {
        $this->column = $column;
    }

    /**
     * @param Accent $accent
     */
    public function setAccent(Accent $accent): void
    {
        $this->accent = $accent;
    }

    /**
     * @return Sql
     */
    public function sql(): Sql
    {
        if(!empty($this->accent)){
            $this->column = $this->accent->append($this->column, true);
        }

        return  new Sql('MAX('.$this->column.')',[],false);
    }
}
