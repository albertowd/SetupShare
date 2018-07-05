<?php
require_once __DIR__ . "/../util/includes.php";

/**
 * Setup ini, sp or null.
 */
$setup = null;

/**
 * Check the filters.
 */
$ext = param("ext");
$id = param("id");

/**
 * Check integrity.
 */
if (!$ext || !$id) {
    abortExecution(403, "Invalid id or extension.");
}

/**
 * Do something!
 */
if (DBConnection::connect()) {
    /**
     * Execute the query.
     */
    $stmt = DBConnection::prepare("SELECT * FROM setup WHERE id = ?", array($id));
    if ($stmt && $stmt->execute()) {
        $setup = $stmt->fetchObject();
    }
}

/**
 * Return the setup.
 */
if ($setup == null) {
    abortExecution(404, "Setup not found");
} else {
    if (isTest()) {
        header("Content-Type: text/html;charset=UTF-8");
        debug($setup->ext);
    } else {
        header("Content-Disposition: attachment;filename=\"{$setup->name}\"");
        header("Content-Length: " . mb_strlen($setup->$ext));
        header("Content-Type: application/octet-stream;");
        echo $setup->$ext;
    }
}
