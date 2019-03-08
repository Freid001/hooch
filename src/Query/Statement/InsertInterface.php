<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Statement;


use Redstraw\Hooch\Query\QueryBuilderInterface;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;

/**
 * Interface InsertInterface
 * @package Redstraw\Hooch\Query\Sql\Statement
 */
interface InsertInterface extends QueryBuilderInterface
{

    /**
     * @param RepositoryInterface $table
     * @param array $cols
     * @return InsertInterface
     */
    public function into(RepositoryInterface $table, array $cols): InsertInterface;

    /**
     * @param array $values
     * @return InsertInterface
     */
    public function onDuplicateKeyUpdate(array $values): InsertInterface;

    /**
     * @param array $values
     * @return InsertInterface
     */
    public function values(array $values): InsertInterface;

}