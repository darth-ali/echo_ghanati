<?php

namespace SmartDB;

class Select
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

    public static function __Create(string $query): Select
    {
        return new Select($query);
    }


    /*----------------------------  Main Functions  --------------------------*/

    /**
     * @param string $table
     * @return SelectFrom
     */
    public function from(string $table): SelectFrom
    {
        $query = $this->query . ' From ' . $table;

        return SelectFrom::__Create($query);
    }

}