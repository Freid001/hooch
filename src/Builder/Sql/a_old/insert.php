<?php namespace freidcreations\freidQuery\sql\statement;
use freidcreations\freidQuery\sql\table;
use freidcreations\freidQuery\sql\sql;

/**
 * @name insert
 * @author Fraser Reid
 * @created 26/11/2015
 * @copyright Copyright (c) - 2015 Fraser Reid
 */
class insert extends statement
{
    /**
     * Cols
     *
     * @param $data
     * @return $this
     */
    public function cols($data)
    {
//        $fields = '';
//        $dataSet = [];
//        $dataValue = [];
//
//        foreach ($data as $key => $value) {
//            if ($dataSet != false) {
//                $fields = $fields . ", " . $key;
//                $dataSet = $dataSet . ", :$key";
//                $dataValue[":$key"] = stripslashes(strip_tags($value));
//            } else {
//                $fields = $key;
//                $dataSet = ":$key";
//                $dataValue[":$key"] = stripslashes(strip_tags($value));
//            }
//        }
//
//
//        sql::raw(self::COLS)->add( $comma . implode(', ', $cols));
//
//        $this->sql =  self::INTO . ' ' . $this->table->name . '(' . $fields . ') ' . self::VALUES . ' (' . $dataSet . ')';
//        $this->parameters = $dataValue;
//        return $this;
    }

    /**
     * Into
     *
     * @param array $cols
     * @return $this
     */
    public function into(array $cols = [])
    {
        sql::raw(self::INTO)->add(self::INTO . $this->table->name . '(' . implode(', ', $cols) . ')');
        return $this;
    }

    /**
     * Row
     *
     * @param array $values
     * @return $this
     */
    public function values(array $values = [])
    {
        $questionMarks = [];

        $parameters = [];



        sql::raw(self::VALUES)->add(self::VALUES . '(' . $questionMarks . ')', $parameters);
        return $this;
    }

    /**
     * Execute
     *
     * @return $this
     * @throws \Exception
     */
    public function execute()
    {
        parent::execute();
        return $this;
    }

    /**
     * Get Last Insert Id
     *
     * @throws \Exception
     */
    public function getLastInsertId()
    {
        return $this->dbh()->lastInsertId();
    }

    /**
     * Instance
     *
     * @param table $table
     * @return mixed
     */
    public static function instance(table $table)
    {
        if (!isset(static::$instances[self::INSERT.'_'.$table->name()])){
            self::$instances[self::INSERT.'_'.$table->name()] = new self($table, self::INSERT);
        }
        return self::$instances[self::INSERT.'_'.$table->name()];
    }

    /**
     * Insert
     */
    public function insert()
    {
        sql::raw(self::INSERT)->add(self::INSERT);
    }
}
