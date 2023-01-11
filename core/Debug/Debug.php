<?php

namespace Debug;
class Debug
{
    public static function VarDump ($data)
    {
        echo '<pre style="direction: ltr">' . var_export($data, true) . '</pre>';
    }
}