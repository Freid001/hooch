<?php

namespace Redstraw\Hooch\Query\Operator;


use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Sql;

/**
 * Interface OperatorInterface
 * @package Redstraw\Hooch\Query
 */
interface OperatorInterface
{
    /**
     * @return Accent
     */
    public function accent(): Accent;

    /**
     * @return Sql
     */
    public function sql(): Sql;

    /**
     * @return string
     */
    public function operator(): string;

    /**
     * @param bool $nested
     */
    public function setNested(bool $nested): void;

    /**
     * @return bool
     */
    public function isNested(): bool;
}
