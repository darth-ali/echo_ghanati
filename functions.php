<?php
//const addresses definition
define("URI_DIRECTORY", trailingslashit(get_template_directory_uri()));
define("DIR_DIRECTORY", trailingslashit(get_template_directory()));
const URI_ASSETS = URI_DIRECTORY . 'assets/';

//Autoloader
function Autoload(string $path)
{
    spl_autoload_register(function ($classname) use ($path) {
        $classname = str_replace('\\', '/', $classname);
        $classes = DIR_DIRECTORY . $path . DIRECTORY_SEPARATOR . $classname . '.php';
        if (file_exists($classes))
            include_once $classes;
    });

}


Autoload('core');
