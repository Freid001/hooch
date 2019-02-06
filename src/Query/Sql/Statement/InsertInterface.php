<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Sql\Statement;


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
     * @return InsertInterface
     */
    public function into(RepositoryInterface $table): InsertInterface;

    /**
     * @param array $values
     * @return InsertInterface
     */
    public function insert(array $values): InsertInterface;

    /**
     * @param array $values
     * @return InsertInterface
     */
    public function onDuplicateKeyUpdate(array $values): InsertInterface;
}