<?php
require_once __DIR__ . "/../util/includes.php";

/**
 * Setup ini, sp or null.
 */
$setup = null;

/**
 * Do something!
 */
if (DBConnection::isConnected()) {
    /**
     * Check the filters.
     */
    $ext = param("ext");
    $id = param("id");

    /**
     * Check integrity.
     */
    if (!$ext || !$id) {
        http_response_code(403);
        die("Please don't.");
    }

    /**
     * Execute the query.
     */
    $sql = "SELECT $ext FROM setup WHERE id = ?";
    if (!$stmt = DBConnection::prepare($sql, array($id))) {
        Logger::log(DBConnection::errorMessage(), Logger::LOGGER_IMPORTANT);
    } else {
        while ($obj = $stmt->fetchObject()) {
            $setup = $obj->$ext;
        }
    }
}

/**
 * Return the setup.
 */
if (isDebugMode()) {
    echo "<pre>";
}
echo $setup;
if (isDebugMode()) {
    echo "</pre>";
}
