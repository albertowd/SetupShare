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
$track = param("track");

/**
 * Do something!
 */
if (DBConnection::isConnected()) {
    /**
     * Check the filters.
     */
    $carSql = $car != null ? "c.name LIKE ?" : "TRUE";
    $driverSql = $driver != null ? "d.name LIKE ?" : "TRUE";
    $trackSql = $track != null ? "t.name LIKE ?" : "TRUE";
    $values = array();
    if ($car != null) {array_push($values, $car);}
    if ($driver != null) {array_push($values, $driver);}
    if ($track != null) {array_push($values, $track);}

    /**
     * Execute the query.
     */
    $sql = "SELECT s.name setup, c.name car, d.name driver, t.name track
              FROM setup s
              JOIN car c ON(s.id_car = c.id)
              JOIN driver d ON(s.id_driver = d.id)
              JOIN track t ON(s.id_track = t.id)
             WHERE TRUE AND $carSql AND $driverSql AND $trackSql
             ORDER BY s.version_ts DESC
             LIMIT 15";
    if (!$stmt = DBConnection::prepare($sql, $values)) {
        Logger::log(DBConnection::errorMessage(), Logger::LOGGER_IMPORTANT);
    } else {
        while ($obj = $stmt->fetchObject()) {
            unset($obj->id_car);
            unset($obj->id_driver);
            unset($obj->id_track);
            array_push($list, $obj);
        }
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
