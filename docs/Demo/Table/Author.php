<?php

namespace QueryMule\Demo\Table;

use QueryMule\Query\Repository\Table\AbstractTable;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Class Author
 * @package QueryMule\demo\table
 */
class Author extends AbstractTable
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'author';
    }

    /**
     * @param $id
     * @return FilterInterface
     */
    public function filterByAuthorId($id) : FilterInterface
    {
        return $this->filter->where(function (FilterInterface $filter) use ($id) {
            $filter->where('author_id', '=?', $id);
        });
    }
}