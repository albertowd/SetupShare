<?php

/**
 * Defnie the <br> tag.
 */
define("BR", "<br />");

/**
 * Wrong parameters, are you trying what, sr?
 *
 * @param int $code
 *            Return code to the request.
 * @param string $message
 *            Message to display, if any.
 */
function abortExecution(int $code = 403, string $message = "Please don't.")
{
    http_response_code($code);

    if (!isTest()) {
        header("Content-Type: text/html;charset=UTF-8");
    }

    if (isTest()) {
        $message = BR . $message;
    }
    die($message);
}

/**
 * Verifies the app version with the server version.
 */
function checkVersion()
{
    $appVersion = param("ver", "0");
    if ($appVersion != "0" && $appVersion != "1.2") {
        abortExecution(403, "Please use app v1.2.");
    }
}

/**
 * Var_dumps the object with <pre> tags.
 *
 * @param mixed $obj
 *            Object to be dumpped.
 */
function debug($obj)
{
    if (isTest()) {
        echo BR . "<pre>" . print_r($obj, true) . "</pre>";
    }
}

/**
 * Verifies if is a test run.
 * @return bool
 */
function isTest()
{
    return isset($_REQUEST["test"]);
}

/**
 * Returns the request parameter value, if it exists.
 *
 * @param string $name
 *            Parameter name.
 * @param mixed $defaultValue
 *            The default value if the param does not exists.
 * @return string|mixed
 */
function param(string $name, $defaultValue = null)
{
    $param = $defaultValue;
    if (isset($_REQUEST[$name]) && strlen($_REQUEST[$name]) > 0) {
        $param = $_REQUEST[$name];
    }
    return $param;
}

/**
 * Modifyies the header before any echo.
 */
if (isTest()) {
    header("Content-Type: text/html;charset=UTF-8");
}

/**
 * Call the version check when this file is required.
 */
checkVersion();
