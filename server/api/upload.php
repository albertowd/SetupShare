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
             WHERE car = ? AND `name` = ? AND steam_id = ? AND track = ?";
    $stmt = DBConnection::prepare($sql, array($setup->car, $setup->name, $setup->steam_id, $setup->track));
    if ($stmt && $row = $stmt->fetchObject()) {
        $id = $row->id;
    }

    if ($id > 0) {
        /**
         * Old setups, let's update it.
         */
        $sql = "UPDATE setup
                   SET ac_version = ?, driver = ?, ini = ?, sp = ?, `version` = `version` + 1, visibility = ?
                 WHERE id = ?";

        /**
         * Execute the query.
         */
        $stmt = DBConnection::prepare($sql, array($setup->ac_version, $setup->driver, $setup->ini, $setup->sp, $setup->visibility, $id));
        if ($stmt) {
            $ret = $stmt->rowCount() > 0 ? "$setup->name updated." : "$setup->name not updated.";
        }
    } else {
        /**
         * New setup, time to insert.
         */
        $sql = "INSERT INTO setup(ac_version, car, driver, ini, `name`, sp, steam_id, track, visibility)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $values = array($setup->ac_version, $setup->car, $setup->driver, $setup->ini,
            $setup->name, $setup->sp, $setup->steam_id, $setup->track, $setup->visibility);
        /**
         * Execute the query.
         */
        if ($stmt = DBConnection::prepare($sql, $values)) {
            $ret = "$setup->name uploaded.";
        }
    }
}

/**
 * Return the new id or success of the uploaded setup.
 */
if (isTest()) {
    debug($ret);
} else {
    header("Content-Type: text/html;charset=UTF-8");
    echo $ret;
}
