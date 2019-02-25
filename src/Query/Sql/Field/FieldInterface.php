<?php

namespace Redstraw\Hooch\Query\Sql\Field;


use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Interface OperatorInterface
 * @package Redstraw\Hooch\Query
 */
interface FieldInterface
{
    /**
     * @param Accent $accent
     */
    public function setAccent(Accent $accent): void;

    /**
     * @return Sql
     */
    public function sql(): Sql;
}
