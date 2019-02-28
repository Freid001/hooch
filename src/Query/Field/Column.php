<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Field;


use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Sql;

/**
 * Class Column
 * @package Redstraw\Hooch\Query\Sql
 */
class Column implements FieldInterface
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
            return new Sql($this->accent->append($this->column, '.'),[],false);
        }

        return new Sql($this->column,[],false);
    }
}
