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
    if ($stmt = DBConnection::prepare("SELECT * FROM setup WHERE id = ?", array($id))) {
        $setup = $stmt->fetchObject();
    }
}

/**
 * Return the setup.
 */
if ($setup == null || $setup->$ext == null) {
    abortExecution(404, "Setup not found");
} else {
    if (isTest()) {
        debug($setup->$ext);
    }
    {
        header("Content-Disposition: attachment;filename=\"{$setup->name}.{$ext}\"");
        header("Content-Length: " . mb_strlen($setup->$ext));
        //header("Content-Transfer-Encoding: binary");
        //header("Content-Type: text/html");
        echo $setup->$ext;
    }
}
