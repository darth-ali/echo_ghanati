<?php

namespace SmartDB;

class SelectFrom
{
    private string $query;

    /*----------------------------  Generate Select  --------------------------*/

    /**
     * @param string $query
     */
    private function __construct(string $query)
    {
        $this->query = $query;
    }

    public static function __Create(string $query): SelectFrom
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

    public function where(string $field): SelectWhere
    {
        $query = $this->query . ' WHERE ' . $field;

        return SelectWhere::__Create($query);
    }

    public function join(string $joinTable, string $onField1, string $onField2): SelectFrom
    {
        $query = $this->query . ' INNER JOIN ' . $joinTable . ' ON ' . $onField1 . ' = ' . $onField2;

        return SelectFrom::__Create($query);
    }
}