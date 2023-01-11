<?php

namespace SmartDB;


class UpdateConditionData
{
    private string $table;
    private array  $data;
    private array  $whereData;
    private array  $format;
    private array  $whereFormat;
    /*----------------------------  Generate Select  --------------------------*/

    /**
     * @param string $table
     * @param array $data
     * @param array $format
     * @param string $whereKey
     * @param string $whereValue
     * @param string $whereFormat
     */
    private function __construct(string $table, array $data, array $format, string $whereKey, string $whereValue, string $whereFormat)
    {
        $this->table = $table;
        $this->data = $data;
        $this->format = $format;
        $this->whereData[$whereKey] = $whereValue;
        $this->whereFormat[] = $whereFormat;
    }


    public static function __Create(string $table, array $data, array $format, string $whereKey, string $whereValue, string $whereFormat): UpdateConditionData
    {
        return new self($table, $data, $format, $whereKey, $whereValue, $whereFormat);
    }

    /*----------------------------  Main Functions  --------------------------*/


    /**
     * @param string $key
     * @param string $value
     * @param string $format
     * @return UpdateConditionData
     */
    public function addConditionData(string $key, string $value, string $format): UpdateConditionData
    {
        $this->whereData[$key] = $value;
        $this->whereFormat[] = $format;
        return $this;
    }

    public function save()
    {
        global $wpdb;
        $wpdb->update(
            $this->table,
            $this->data,
            $this->whereData,
            $this->format,
            $this->whereFormat,
        );
        return $wpdb->insert_id;
    }
}