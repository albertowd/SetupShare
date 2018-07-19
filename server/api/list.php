<?php
require_once __DIR__ . "/../util/includes.php";

/**
 * Setup list.
 */
$list = array();

/**
 * Optional parameters.
 */
$app = isset($_REQUEST["app"]);
$car = param("car");
if (!$app && $car) {
    $car = "%$car%";
}
$driver = param("driver");
if (!$app && $driver) {
    $driver = "%$driver%";
}
$name = param("name");
if (!$app && $name) {
    $name = "%$name%";
}
$steamId = intval(param("id", "0"));
$track = param("track");
if (!$app && $track) {
    $track = "%$track%";
}

/**
 * Do something!
 */
if (DBConnection::connect()) {
    /**
     * Check the filters.
     */
    $carSql = $car ? "car LIKE ?" : "TRUE";
    $driverSql = $driver ? "driver LIKE ?" : "TRUE";
    $friendsSetupSql = "";
    $nameSql = $name ? "`name` LIKE ?" : "TRUE";
    $mySetupSql = "";
    $trackSql = $track ? "track LIKE ?" : "TRUE";

    $filters = array();
    if ($car) {$filters[] = $car;}
    if ($driver) {$filters[] = $driver;}
    if ($name) {$filters[] = $name;}
    if ($track) {$filters[] = $track;}

    /**
     * Check own and friend's setups.
     */
    if ($steamId > 0) {
        $friendList = implode(",", SteamAPI::getFriendIds(intval($steamId)));
        $friendsSetupSql = "SELECT ac_version, car, driver, id, `name`, sp, track, `version`, version_ts, visibility
                                      FROM setup
                                     WHERE TRUE AND ($carSql AND $driverSql AND $nameSql AND $trackSql AND steam_id IN($friendList) AND visibility = 1)";
        $friendsSetupSql = "UNION $friendsSetupSql";
        if ($car) {$filters[] = $car;}
        if ($driver) {$filters[] = $driver;}
        if ($name) {$filters[] = $name;}
        if ($track) {$filters[] = $track;}

        $mySetupSql = "SELECT ac_version, car, driver, id, `name`, sp, track, `version`, version_ts, visibility
                         FROM setup
                        WHERE TRUE AND ($carSql AND $driverSql AND $nameSql AND $trackSql AND steam_id = ?)";
        $mySetupSql = "UNION $mySetupSql";
        if ($car) {$filters[] = $car;}
        if ($driver) {$filters[] = $driver;}
        if ($name) {$filters[] = $name;}
        if ($track) {$filters[] = $track;}
        $filters[] = $steamId;
    }

    /**
     * Execute the query.
     */
    $sql = "SELECT ac_version, car, driver, id, `name`, sp, track, `version`, version_ts, visibility
              FROM setup
             WHERE TRUE AND $carSql AND $driverSql AND $nameSql AND $trackSql AND visibility = 0
             $friendsSetupSql $mySetupSql
             ORDER BY ac_version DESC, `name` DESC " . ($app ? "" : "LIMIT 15");
    debug($filters);
    if ($stmt = DBConnection::prepare($sql, $filters)) {
        $list = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($list as &$setup) {
            $setup->sp = $setup->sp != null;
            $setup->version_ts = strtotime($setup->version_ts) * 1000;
        }
    }
}

/**
 * Return the found setups.
 */
$list = json_encode($list);
if (isTest()) {
    debug($list);
} else {
    header("Content-Type: application/json");
    echo $list;
}
