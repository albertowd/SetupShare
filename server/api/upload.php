<?php
require_once __DIR__ . "/../util/includes.php";

/**
 * Return var.
 */
$ret = false;

/**
 * Mandatory parameter.
 */
$setup = json_decode(param("setup"));

/**
 * Check integrity.
 */
if (!$setup || !$setup->id) {
    abortExecution(403, "Invalid setup uploaded.");
}

/**
 * Do something!
 */
if (DBConnection::connect()) {
    if ($setup->id > 0) {
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
        if ($stmt = DBConnection::prepare($sql, $values) && $stmt->execute()) {
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
        if ($stmt = DBConnection::prepare($sql, $values) && $stmt->execute()) {
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
    debug($ret);
} else {
    header("Content-Type: application/json");
    echo $ret;
}
