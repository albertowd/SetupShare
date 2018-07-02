<?php

/**
 * Wrong parameters, are you trying what, sr?
 */
function abortExecution()
{
    http_response_code(403);
    die("Please don't.");
}

/**
 * Var_dumps the object with <pre> tags.
 *
 * @param mixed $obj
 *            Object to be dumpped.
 */
function debug($obj)
{
    echo "<br><pre>";
    var_dump($obj);
    echo "</pre><br>";
}

/**
 * Verifies if it's in debug mode (requests from localhost).
 * @return bool
 */
function isDebugMode()
{
    return $_SERVER["HTTP_HOST"] === "localhost";
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
 * Returns the host with the debug folder in the path, case it's in debug mode.
 * @return string
 */
function host()
{
    return "http://" . $_SERVER["HTTP_HOST"] . (isDebugMode() ? "/albertowd.com.br" : "");
}

/**
 * Returns the request parameter value, if it exists.
 *
 * @param string $name
 *            Parameter name.
 * @param bool $forceNull
 *            True return null if there's no value.
 * @return string|null
 */
function param(string $name, bool $forceNull = true)
{
    $param = $forceNull ? null : "";
    if (isset($_REQUEST[$name]) && strlen($_REQUEST[$name]) > 0) {
        $param = $_REQUEST[$name];
    }
    return $param;
}

/**
 * Verifies if there's a parameter in the request and if there's value in it.
 * @param string $name
 *            Parameter name.
 * @return bool
 */
function verifyParam(string $name)
{
    return isset($_REQUEST[$name]) && strlen($_REQUEST[$name]) > 0;
}
