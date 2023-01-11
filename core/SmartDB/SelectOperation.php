<?php

namespace SmartDB;

class SelectOperation
{
    private string $query;

    /*----------------------------  Generate Select  --------------------------*/

    private function __construct(string $query)
    {
        $this->query = $query;
    }

    public static function __Create(string $query): SelectOperation
    {
        return new self($query);
    }

    /*----------------------------  Main Functions  --------------------------*/

    /**
     * @return string
     */
    public function generateQuery(): string
    {
        return $this->query;
    }

    /**
     * @param string $output
     * @return array
     */
    public function execute(string $output = OBJECT): array
    {
        return SelectExecute::__Create($this->query, $output);
    }

    /**
     * @param string $field
     * @return SelectWhere
     */
    public function and(string $field): SelectWhere
    {
        $query = $this->query . ' AND ' . $field;
        return SelectWhere::__Create($query);
    }

    /**
     * @param string $field
     * @return SelectWhere
     */
    public function or(string $field): SelectWhere
    {
        $query = $this->query . ' OR ' . $field;
        return SelectWhere::__Create($query);
    }
    //endregion

}