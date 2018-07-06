<?php
require_once __DIR__ . "/../util/includes.php";

/**
 * Return var.
 */
$ret = false;

/**
 * Mandatory parameters.
 */
$setup = json_decode(param("setup"));

/**
 * Do something!
 */
if (setup != null && DBConnection::isConnected()) {
    if(setup.id > 0) {
        /**
         * Old setups, let's update it.
         */
        $sql = "UPDATE setup
                   SET ac_version = ?, ini = ?, sp = ?, 'version' = 'version' + 1
                 WHERE id = ?";
        $values = array($setup->ac_version, $setup->ini, $setup->sp);

        /**
         * Execute the query.
         */
        $stmt = DBConnection::prepare($sql, $values)
        if ($stmt && $stmt->execute()) {
            $ret = $stmt->rowCount();
        }
    } else {
        /**
         * New setup, time to insert.
         */
        $sql = "INSERT INTO setup(ac_version, car, driver, ini, 'name', sp, track)
                VALUES(?, ?, ?, ?, ?, ?, ?)";
        $values = array($setup->ac_version, $setup->car, $setup->driver, $setup->ini, $setup->name, $setup->sp, $setup->track);
        /**
         * Execute the query.
         */
        $stmt = DBConnection::prepare($sql, $values);
        if ($stmt && $stmt->execute()) {
            $ret = DBConnection::lastInsertId();
        }
    }
}

/**
 * Return the new id or success of the uploaded setup.
 */
$ret = json_encode($ret);
if (isTest()) {
    header("Content-Type: text/html;charset=UTF-8");
    die("<pre>$ret</pre>");
} else {
    header("Content-Type: application/json");
    die($ret);
}
