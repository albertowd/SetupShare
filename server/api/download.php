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
    $sql = "SELECT * FROM setup WHERE id = ?";
    if ($stmt = DBConnection::prepare($sql, array($id))) {
        $setup = $stmt->fetchObject();
    }
}

/**
 * Return the setup.
 */
if ($setup == null) {
    http_response_code(404);
    header("Content-Type: text;charset=UTF-8");
    die("Setup not found.");
} else {
    if (isTest()) {
        header("Content-Type: text/html;charset=UTF-8");
        die("<pre>{$setup->$ext}</pre>");
    } else {
        header("Content-Disposition: attachment;filename=\"{$setup->name}\"");
        header("Content-Length: " . mb_strlen($setup->$ext));
        header("Content-Type: application/octet-stream;");
        die($setup->$ext);
    }
}
