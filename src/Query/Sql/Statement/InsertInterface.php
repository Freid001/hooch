<?php

declare(strict_types=1);

namespace QueryMule\Query\Sql\Statement;


use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Repository\RepositoryInterface;

/**
 * Interface InsertInterface
 * @package QueryMule\Query\Sql\Statement
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