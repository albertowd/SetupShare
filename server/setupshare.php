<?php

// Path to the setup directory.
const SETUP_DIR = "/tmp/setups";

// Used variables.
$car = isset($_REQUEST["car"]) ? $_REQUEST["car"] : null;
$ini = isset($_REQUEST["ini"]) ? $_REQUEST["ini"] : "";
$driver = isset($_REQUEST["driver"]) ? $_REQUEST["driver"] : null;
$setup = isset($_REQUEST["setup"]) ? $_REQUEST["setup"] : null;
$sp = isset($_REQUEST["sp"]) ? $_REQUEST["sp"] : "";
$track = isset($_REQUEST["track"]) ? $_REQUEST["track"] : null;

/**
* Deletes old setups files.
*/
$timeLimit = time() - (24*60*60);
foreach (scandir(SETUP_DIR) as &$fileName) {
    $path = SETUP_DIR . "/$fileName";
    // If it's not a folder, it's a setup file, and if it's one day older will be recycled!
    if (!is_dir($path) && filectime($path) < $timeLimit) {
        unlink($path);
    }
}

/**
* Verifies if the setup folder exists to create it.
*/
if (!file_exists(SETUP_DIR) && !mkdir(SETUP_DIR)) {
    http_response_code(500);
    die("Cannot create '" . SETUP_DIR . "' folder.");
}

/**
 * Verify if the query is valid.
 */
if ($track === null || $car === null) {
    // Required for all the functions.
    http_response_code(403);
    die("'track' and 'car' fields are required to list, download or upload setups.");
} else {
    // Required for download/upload functions.
    $requiredCount = ($driver !== null ? 1 : 0) + ($setup !== null ? 1 : 0);
    if ($requiredCount > 0 && $requiredCount < 2) {
        http_response_code(403);
        die("'driver' and 'setup' fields are required to download/upload a setup.");
    }
}

/**
 * Do the setups magic!
 */
if ($driver === null) {
    // List the track/car setups.
    $setups = array();
    $carTrack = "$car.$track";
    $carTrackLen = strlen($carTrack);
    // Search for all setups available.
    foreach (scandir(SETUP_DIR) as &$fileName) {
        if (!is_dir($path) && $carTrack == substr($fileName, 0, $carTrackLen)) {
            // Gets the setup infos (car, track, driver, setup, extension).
            $setupInfo = explode(".", $fileName);
            // Add the driver list.
            if (!isset($setups[$setupInfo[2]])) {
                $setups[$setupInfo[2]] = array();
            }
            if (!in_array($setupInfo[3], $setups[$setupInfo[2]])) {
                // Add ths setup on driver list.
                array_push($setups[$setupInfo[2]], $setupInfo[3]);
            }
        }
    }
    die(json_encode($setups));
} elseif (strlen($ini) > 0) {
    // Upload setup.
    $tmp = fopen(SETUP_DIR . "/$car.$track.$driver.$setup.ini", "w");
    if ($tmp === false) {
        http_response_code(403);
        die("Error on $setup.ini.");
    } else {
        fwrite($tmp, $ini);
        fclose($tmp);
    }
    if (strlen($sp) > 0) {
        $tmp = fopen(SETUP_DIR . "/$car.$track.$driver.$setup.sp", "w");
        if ($tmp === false) {
            http_response_code(403);
            die("Error on $setup.sp.");
        } else {
            fwrite($tmp, $sp);
            fclose($tmp);
        }
    }
    die("$setup uploaded.");
} else {
    // Download setup.
    $info = array("ini" => "", "sp" => "");
    $tmp = file_get_contents(SETUP_DIR . "/$car.$track.$driver.$setup.ini");
    if ($tmp === false) {
        http_response_code(404);
        die("$setup not found.");
    } else {
        $info["ini"] = $tmp;
    }
    $tmp = file_get_contents(SETUP_DIR . "/$car.$track.$driver.$setup.sp");
    if ($tmp !== false) {
        $info["sp"] = $tmp;
    }
    die(json_encode($info));
}
