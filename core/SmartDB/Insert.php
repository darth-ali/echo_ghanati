<?php

namespace SmartDB;

class Insert
{
    private string $table;

    /*----------------------------  Generate Select  --------------------------*/

    /**
     * @param string $intoTable
     */
    private function __construct(string $intoTable)
    {
        $this->table = $intoTable;
    }

    public static function __Create(string $intoTable): Insert
    {
        return new self($intoTable);
    }


    /*----------------------------  Main Functions  --------------------------*/



    //region after this step

    /**
     * @param string $key
     * @param string $value
     * @param string $format
     * @return InsertData
     */
    public function addData(string $key, string $value, string $format): InsertData
    {
        return InsertData::__Create($this->table, $key, $value, $format);
    }

    //endregion

}