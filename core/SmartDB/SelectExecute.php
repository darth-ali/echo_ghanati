<?php

namespace SmartDB;

class SelectExecute
{

    /**
     * @param string $query
     * @param string $output
     * @return array
     */
    public static function __Create(string $query, string $output): array
    {
        global $wpdb;
        if ($query != null) {
            $result = $wpdb->get_results($query, $output);
            if ($result != null)
                return $result;
        }
        return [];
    }
}