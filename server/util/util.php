<?php

/**
 * Wrong parameters, are you trying what, sr?
 */
function abortExecution()
{
    http_response_code(403);
    header("Content-Type: text;charset=UTF-8");
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
