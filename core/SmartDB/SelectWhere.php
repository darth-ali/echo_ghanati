<?php

namespace SmartDB;

class SelectWhere
{
    private string $query;


    /*----------------------------  Generate Select  --------------------------*/

    /**
     * @param string $query
     * @param string $table
     */
    private function __construct(string $query)
    {
        $this->query = $query;
    }

    public static function __Create(string $query): SelectWhere
    {
        return new self($query);
    }


    /*----------------------------  Main Functions  --------------------------*/

    /**
     * @param string $value
     * @return SelectOperation
     */
    public function equalTo(string $value): SelectOperation
    {
        $query = sprintf('%s = "%s"', $this->query, $value);
        return SelectOperation::__Create($query);
    }

    /**
     * @return SelectOperation
     */
    public function isNull(): SelectOperation
    {
        $query = sprintf('%s IS NULL', $this->query);
        return SelectOperation::__Create($query);
    }

    /**
     * @param string $first
     * @param string $second
     * @return SelectOperation
     */
    public function between(string $first, string $second): SelectOperation
    {
        $query = sprintf('%s BETWEEN "%s" AND "%s"', $this->query, $first, $second);
        return SelectOperation::__Create($query);
    }

    /**
     * @param string $value
     * @return SelectOperation
     */
    public function recentHours(string $value): SelectOperation
    {
        $query = sprintf('%s > date > NOW() - INTERVAL %s HOUR', $this->query, $value);
        return SelectOperation::__Create($query);
    }

    /**
     * @param string $value
     * @return SelectOperation
     */
    public function like(string $value): SelectOperation
    {
        $query = sprintf('%s LIKE "%s"', $this->query, $value);
        return SelectOperation::__Create($query);
    }


}