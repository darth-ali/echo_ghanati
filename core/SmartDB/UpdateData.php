<?php

namespace SmartDB;

class UpdateData
{

    private string $table;
    private array  $data;
    private array  $format;


    /*----------------------------  Generate Select  --------------------------*/

    /**
     * @param string $table
     * @param string $key
     * @param string $value
     * @param string $format
     */
    private function __construct(string $table, string $key, string $value, string $format)
    {
        $this->table = $table;
        $this->data[$key] = $value;
        $this->format[] = $format;
    }


    public static function __Create(string $table, string $key, string $value, string $format): UpdateData
    {
        return new self($table, $key, $value, $format);
    }

    /*----------------------------  Main Functions  --------------------------*/

    /**
     * @param string $key
     * @param string $value
     * @param string $format
     * @return $this
     */
    public function addData(string $key, string $value, string $format): UpdateData
    {
        $this->data[$key] = $value;
        $this->format[] = $format;
        return $this;
    }

    public function addConditionData(string $key, string $value, string $format): UpdateConditionData
    {
        return UpdateConditionData::__Create($this->table, $this->data, $this->format, $key, $value, $format);
    }

    /**
     * @return int|false (int|false) The number of rows inserted, or false on error.
     */
    public function save()
    {
        global $wpdb;
        $wpdb->update(
            $this->table,
            $this->data,
            [],
            $this->format,
            [],
        );
        return $wpdb->insert_id;
    }


}