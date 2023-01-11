<?php

trait Singelton
{
    public function __construct()
    {
    }
    public function __clone(): void
    {
        // TODO: Implement __clone() method.
    }

    final public static function getInstance()
    {
        static $instance = [];
        $calledClass = get_called_class();
        if (!isset($instance[$calledClass])) {
            $instance[$calledClass] = new $calledClass();
            do_action(sprintf('vbook_theme_singelton_init%s',$calledClass));
        }
        return $instance[$calledClass];
    }
}