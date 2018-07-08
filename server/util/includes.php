<?php
require_once __DIR__ . "/db_connection.php";
require_once __DIR__ . "/util.php";
session_start();

/**
 * Called when an error is found.
 */
function php_error_handler()
{
    $last_error = error_get_last();
    if ($last_error) // && $last_error[ "type" ] == E_ERROR )
    {
        header("HTTP/1.1 500 Internal Server Error");

        $message = $last_error["message"];
        $firstIndex = strpos($message, ":") + 2;
        $endIndex = strpos($message, " in /");
        die(substr($message, $firstIndex, $endIndex - $firstIndex));
    }
}
error_reporting(E_ALL);
//register_shutdown_function("php_error_handler");

/**
 * TODO: configure https.
 */
