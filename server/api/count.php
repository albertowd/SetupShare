<?php
require_once __DIR__ . "/../util/includes.php";

/**
 * Setup count.
 */
$count = 0;

/**
 * Do something!
 */
if (DBConnection::connect()) {
    /**
     * Execute the query.
     */
    if ($stmt = DBConnection::query("SELECT COUNT(id) FROM setup")) {
        $count = $stmt->fetchColumn();
    }
}

/**
 * Return the setup count.
 */
if (!isTest()) {
    header("Content-Type: text/html;charset=UTF-8");
}
echo $count;
