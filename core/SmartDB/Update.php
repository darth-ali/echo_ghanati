<?php

namespace SmartDB;

class Update
{
    private string $table;

    /*----------------------------  Generate Select  --------------------------*/

    /**
     * @param string $fromTable
     */
    private function __construct(string $fromTable)
    {
        $this->table = $fromTable;
    }

    public static function __Create(string $fromTable): Update
    {
        return new self($fromTable);
    }

    /*----------------------------  Main Functions  --------------------------*/

    /**
     * @param string $key
     * @param string $value
     * @param string $format
     * @return UpdateData
     */
    public function addData(string $key, string $value, string $format): UpdateData
    {
        return UpdateData::__Create($this->table, $key, $value, $format);
    }


}