<?php

namespace Redstraw\Hooch\Query\Field;


use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Sql;

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
