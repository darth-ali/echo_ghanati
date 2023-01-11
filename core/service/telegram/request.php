<?php

use TelegramBot\Type\Request;

$TELEGRAM_SERVICE_DIR = __DIR__;
$PACKAGE_DIR          = dirname($TELEGRAM_SERVICE_DIR, 2) . '/package';
$TELEGRAM_PACKAGE_DIR = $PACKAGE_DIR . '/TelegramBot';

include_once $TELEGRAM_PACKAGE_DIR . '/Launcher.php';
include_once $TELEGRAM_SERVICE_DIR . '/commands.php';
include_once $TELEGRAM_SERVICE_DIR . '/response.php';
include_once $PACKAGE_DIR . '/WP/WPRestAPI.php';


$types = glob($TELEGRAM_PACKAGE_DIR . "/Type/*");
foreach ($types as $file) {
    include_once $file;
}

$methods = glob($TELEGRAM_PACKAGE_DIR . "/Method/*");
foreach ($methods as $file) {
    include_once $file;
}


header('Content-Type: text/html; charset=utf-8');
$message = file_get_contents("php://input");
file_put_contents("behrad.txt", $message);

$request = new Request($message);
global $commands;

(array_key_exists($request->getCommand(), $commands)) ? $commands[$request->getCommand()]($request) : error($request);

