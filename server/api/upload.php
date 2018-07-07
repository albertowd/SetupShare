<?php
require_once __DIR__ . "/../util/includes.php";

/**
 * Return var.
 */
$ret = false;

/**
 * Mandatory parameter.
 */
$setup = json_decode(file_get_contents("php://input"));

/**
 * Check integrity.
 */
if (!$setup) {
    error_log(json_last_error() . ": " . json_last_error_msg());
    error_log(file_get_contents("php://input"));
    abortExecution(403, "Setup is not valid.");
}

/**
 * Do something!
 */
if (DBConnection::connect()) {
    /**
     * Default id for insert new setups.
     */
    $id = 0;

    /**
     * Search for the setup in the database.
     */
    $sql = "SELECT id
              FROM setup
             WHERE car = ? AND driver = ? AND `name` = ? AND track = ?";
    $stmt = DBConnection::prepare($sql, array($setup->car, $setup->driver, $setup->name, $setup->track));
    if ($stmt && $row = $stmt->fetchObject()) {
        $id = $row->id;
    }

    if ($id > 0) {
        /**
         * Old setups, let's update it.
         */
        $sql = "UPDATE setup
                   SET ac_version = ?, ini = ?, sp = ?, `version` = `version` + 1
                 WHERE id = ?";

        /**
         * Execute the query.
         */
        $stmt = DBConnection::prepare($sql, array($setup->ac_version, $setup->ini, $setup->sp, $id));
        if ($stmt) {
            $ret = $stmt->rowCount() > 0 ? "Setup updated." : "Setup not updated.";
        }
    } else {
        /**
         * New setup, time to insert.
         */
        $sql = "INSERT INTO setup(ac_version, car, driver, ini, `name`, sp, track)
                VALUES(?, ?, ?, ?, ?, ?, ?)";
        $values = array($setup->ac_version, $setup->car, $setup->driver, $setup->ini, $setup->name, $setup->sp, $setup->track);
        /**
         * Execute the query.
         */
        if ($stmt = DBConnection::prepare($sql, $values)) {
            $ret = "Setup uploaded.";
        }
    }
}

/**
 * Return the new id or success of the uploaded setup.
 */
header("Content-Type: text/html;charset=UTF-8");
if (isTest()) {
    debug($ret);
} else {
    echo $ret;
}
