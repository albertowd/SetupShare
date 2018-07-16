<?php
require_once __DIR__ . "/../util/includes.php";

/**
 * Setup list.
 */
$list = array();

/**
 * Optional parameters.
 */
$car = param("car");
$driver = param("driver");
$name = param("name");
$steamId = intval(param("id", "0"));
$track = param("track");

/**
 * Do something!
 */
if (DBConnection::connect()) {
    /**
     * Check the filters.
     */
    $carSql = $car ? "car LIKE :car" : "TRUE";
    $driverSql = $driver ? "driver LIKE :driver" : "TRUE";
    $friendsSetupSql = "";
    $nameSql = $name ? "`name` LIKE :name" : "TRUE";
    $mySetupSql = "";
    $trackSql = $track ? "track LIKE :trak" : "TRUE";

    $filters = array();
    if ($car) {$filters[":car"] = $car;}
    if ($driver) {$filters[":driver"] = $driver;}
    if ($driver) {$filters[":name"] = $nameSql;}
    if ($track) {$filters[":track"] = $track;}
    if ($steamId > 0) {$filters[":steam_id"] = $steamId;}

    /**
     * Check own and friend's setups.
     */
    if ($steamId > 0) {
        $friendList = implode(",", SteamAPI::getFriendIds(intval($steamId)));

        $mySetupSql = "SELECT ac_version, car, driver, id, `name`, sp, track, `version`, version_ts, visibility
                         FROM setup
                        WHERE TRUE AND ($carSql AND $driverSql AND $trackSql AND steam_id = :steam_id AND visibility = 2)";
        $mySetupSql = "UNION $mySetupSql";

        $friendsSetupSql = "SELECT ac_version, car, driver, id, `name`, sp, track, `version`, version_ts, visibility
                              FROM setup
                             WHERE TRUE AND ($carSql AND $driverSql AND $trackSql AND steam_id IN($friendList) AND visibility = 1)";
        $friendsSetupSql = "UNION $friendsSetupSql";
    }

    /**
     * Execute the query.
     */
    $sql = "SELECT ac_version, car, driver, id, `name`, sp, track, `version`, version_ts, visibility
              FROM setup
             WHERE TRUE AND $carSql AND $driverSql AND $trackSql AND visibility = 0
             $mySetupSql $friendsSetupSql
             ORDER BY ac_version DESC, `name` DESC " . (param("app") == null ? "LIMIT 15" : "");
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
    header("Content-Type: text/html;charset=UTF-8");
    debug($list);
} else {
    header("Content-Type: application/json");
    echo $list;
}
