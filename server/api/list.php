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
if (DBConnection::isConnected()) {
    /**
     * Check the filters.
     */
    $carSql = $car != null ? "s.car LIKE ?" : "TRUE";
    $driverSql = $driver != null ? "s.driver LIKE ?" : "TRUE";
    $nameSql = $name != null ? "s.name LIKE ?" : "TRUE";
    $trackSql = $track != null ? "s.track LIKE ?" : "TRUE";
    $filters = array();
    if ($car != null) {array_push($filters, $car);}
    if ($driver != null) {array_push($filters, $driver);}
    if ($driver != null) {array_push($filters, $nameSql);}
    if ($track != null) {array_push($filters, $track);}

    /**
     * Execute the query.
     */
    $sql = "SELECT s.ac_version, s.car, s.driver, s.name, s.track, s.version
              FROM setup s
             WHERE TRUE AND $carSql AND $driverSql AND $trackSql
             ORDER BY s.ac_version DESC, s.version DESC
             LIMIT 15";
    if (!$stmt = DBConnection::prepare($sql, $values)) {
        Logger::log(DBConnection::errorMessage(), Logger::LOGGER_IMPORTANT);
    } else {
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

/**
 * Return the found setups.
 */
if (isDebugMode()) {
    echo "<pre>";
}
echo json_encode($list);
if (isDebugMode()) {
    echo "</pre>";
}
