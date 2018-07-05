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
    header("Content-Type: text/html;charset=UTF-8");
    die(BR . $message);
}

/**
 * Var_dumps the object with <pre> tags.
 *
 * @param mixed $obj
 *            Object to be dumpped.
 */
function debug($obj)
{
    echo BR . "<pre>";
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
