<?php
require_once "setup.php";
require_once "setuputils.php";

// Used variables.
$car = SetupUtils::checkParam("car");
$download = SetupUtils::checkParam("download");
$ini = SetupUtils::checkParam("ini");
$driver = SetupUtils::checkParam("driver");
$name = SetupUtils::checkParam("name");
$sp = SetupUtils::checkParam("sp");
$track = SetupUtils::checkParam("track");

/**
 * Do the setup magic!
 */
if ($driver == null) {
    // List the track/car setups.
    $setups = null;
    if ($track != null && $car != null) {
        $setups = SetupUtils::listSetupsToApp($car, $track);
    } else {
        $setups = SetupUtils::listSetups();
    }
    die(json_encode($setups));
} elseif ($car != null && $name != null && $track != null) {
    if ($ini != null) {
        // Upload setup.
        $tmp = fopen(SETUP_DIR . "/$car.$track.$driver.$name.ini", "w");
        if ($tmp === false) {
            http_response_code(403);
            die("Error on $name.ini.");
        } else {
            fwrite($tmp, $ini);
            fclose($tmp);
        }
        if ($sp != null) {
            $tmp = fopen(SETUP_DIR . "/$car.$track.$driver.$name.sp", "w");
            if ($tmp === false) {
                http_response_code(403);
                die("Error on $name.sp.");
            } else {
                fwrite($tmp, $sp);
                fclose($tmp);
            }
        }
        die("$name uploaded.");
    } else {
        // Download setup.
        $setup = Setup::fromFile("$track.$car.$driver.$name");
        if (!$setup->isValid()) {
            http_response_code(404);
            die("$name not found.");
        } else {
            die($download != null ? $setup->ini : json_encode($setup->simplify()));
        }
    }
} else {
    http_response_code(403);
    die("Use no params or use car and track together.");
}
