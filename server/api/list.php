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
$track = param("track");

/**
 * Do something!
 */
if (DBConnection::connect()) {
    /**
     * Check the filters.
     */
    $carSql = $car != null ? "car LIKE ?" : "TRUE";
    $driverSql = $driver != null ? "driver LIKE ?" : "TRUE";
    $nameSql = $name != null ? "`name` LIKE ?" : "TRUE";
    $trackSql = $track != null ? "track LIKE ?" : "TRUE";
    $filters = array();
    if ($car != null) {array_push($filters, $car);}
    if ($driver != null) {array_push($filters, $driver);}
    if ($driver != null) {array_push($filters, $nameSql);}
    if ($track != null) {array_push($filters, $track);}

    /**
     * Execute the query.
     */
    $sql = "SELECT *
              FROM setup
             WHERE TRUE AND $carSql AND $driverSql AND $trackSql
             ORDER BY ac_version DESC, `name` DESC
             LIMIT 15";
    if ($stmt = DBConnection::prepare($sql, $filters)) {
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($list as &$setup) {
            $setup["name"] = ($setup["sp"] != null ? "* " : "") . $setup["name"];
            unset($setup["ini"]);
            unset($setup["sp"]);
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
