<?php
require_once "setup.php";
require_once "setuputils.php";

// Used variables.
$service = SetupUtils::checkParam("service");

/**
 * Wrong parameters, are you trying what, sr?
 */
function abortExecution(): void
{
    http_response_code(403);
    die("Please don't.");
}

/**
 * Let's download one setups ini or sp.
 */
function downloadSetup(): void
{
    $car = SetupUtils::checkParam("car");
    $driver = SetupUtils::checkParam("driver");
    $name = SetupUtils::checkParam("name");
    $track = SetupUtils::checkParam("track");
    
    // All info needed!
    if ($car == null || $driver == null || $name == null || $track == null) {
        abortExecution();
    }
    
    // Trying to load the setup file.
    $setup = Setup::fromFile("$track.$car.$driver.$name");
    if (! $setup->isValid()) {
        http_response_code(404);
        die("$name not found.");
    } else {
        $ext = SetupUtils::checkParam("ext");
        if ($ext == "sp" && $setup->sp == "") {
            http_response_code(404);
            die("$name not found.");
        } else {
            die($ext == "ini" ? $setup->ini : $setup->sp);
        }
    }
}

/**
 * App or site request of the setup list.
 */
function listSetups(): void
{
    $car = SetupUtils::checkParam("car");
    $track = SetupUtils::checkParam("track");
    
    if ($car == null) {
        $car = "";
    }
    if ($track == null) {
        $track = "";
    }
    
    die(json_encode(SetupUtils::listSetups($car, $track)));
}

/**
 * User has uploaded his setup files!
 */
function uploadSetup(): void
{
    $car = SetupUtils::checkParam("car");
    $driver = SetupUtils::checkParam("driver");
    $ini = SetupUtils::checkParam("ini");
    $name = SetupUtils::checkParam("name");
    $sp = SetupUtils::checkParam("sp");
    $track = SetupUtils::checkParam("track");
    
    // All variables are required.
    if ($car == null || $driver == null || $ini == null || $name == null || $track == null) {
        abortExecution();
    }
    
    // Trying to save the ini file first.
    $tmp = fopen("files/$track.$car.$driver.$name.ini", "w");
    if ($tmp === false) {
        http_response_code(403);
        die("Error on $name.ini.");
    } else {
        fwrite($tmp, $ini);
        fclose($tmp);
    }
    
    // Now let's save the sp file, if there is one.
    if ($sp != null) {
        $tmp = fopen("files/$track.$car.$driver.$name.sp", "w");
        if ($tmp === false) {
            http_response_code(403);
            die("Error on $name.sp.");
        } else {
            fwrite($tmp, $sp);
            fclose($tmp);
        }
    }
    die("$name uploaded.");
}

// Check if the service is ok.
if ($service == null) {
    abortExecution();
}

// Let's do something!
switch ($service) {
    case "download":
        downloadSetup();
        break;
    
    case "list":
        listSetups();
        break;
    
    case "upload":
        uploadSetup();
        break;
    
    default:
        abortExecution();
}