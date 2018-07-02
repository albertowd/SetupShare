<?php
require_once __DIR__ . "/logger.php";

/**
 * Handles the database connection.
 *
 * @author albertowd
 */
class DBConnection
{
    /**
     * Database connection class.
     * @var PDO
     */
    private static $connection = null;

    /**
     * Atempt a connection with the database.
     * @return bool
     */
    public static function connect()
    {
        // If it's not connected, try it.
        if (static::$connection == null) {
            try {
                static::$connection = new PDO("mysql:charset=utf8;dbname=setup_share;host=localhost", "setupserver", "setup2018");
            } catch (PDOException $ex) {
                Logger::log($ex->getMessage(), Logger::LOGGER_IMPORTANT);
                static::$connection = null;
            }
        }

        return static::isConnected();
    }

    /**
     * If it's connected, disconnect.
     */
    public static function disconnect()
    {
        if (static::isConnected()) {
            static::$connection = null;
        }
    }

    /**
     * Verifies if it's connected.
     * @return bool
     */
    public static function isConnected()
    {
        return static::$connection != null;
    }

    /**
     * Make a simple query.
     *
     * @param string $sql
     *           Simple query.
     * @return PDOStatement|null
     */
    public static function query(string $sql)
    {
        $stmt = null;
        if (static::isConnected()) {
            $start = time();
            try {
                $stmt = static::$connection->query($sql);
                if (isTest()) {
                    $elapsed = time() - $start;
                    Logger::log("Executed ({$elapsed}s): $sql", Logger::LOGGER_DEBUGGER);
                }
            } catch (PDOException $ex) {
                error_log($ex->getMessage());
            }
        } else {
            error_log("Trying to query without being connected.");
        }
        return $stmt;
    }

    /**
     * Prepare a query.
     *
     * @param string $sql
     *           Prepared query.
     * @return PDOStatement|null
     */
    public static function prepare(string $sql, array $values)
    {
        $stmt = null;
        if (static::isConnected()) {
            $start = time();
            try {
                $stmt = static::$connection->prepare($sql);
                $stmt->execute($values);
                if (isTest()) {
                    $elapsed = time() - $start;
                    Logger::log("Executed ({$elapsed}s): $sql", Logger::LOGGER_DEBUGGER);
                }
            } catch (PDOException $ex) {
                error_log($ex->getMessage());
            }
        } else {
            error_log("Trying to query without being connected.");
        }
        return $stmt;
    }
}

/**
 * Connection test.
 */
if (isset($_REQUEST["test"]) && DBConnection::connect()) {
    Logger::log(DBConnection::isConnected() ? "Connection tested with success." : "No database connection.", Logger::LOGGER_IMPORTANT);
}
