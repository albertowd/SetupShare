<?php
/**
 * Handles response logging.
 *
 * @author albertowd
 */
class Logger
{
    /**
     * Log enums.
     */
    const LOGGER_ALL = 0;
    const LOGGER_DEBUGGER = 1;
    const LOGGER_IMPORTANT = 2;

    /**
     * Request log level key.
     * @var string
     */
    private static $level = "log_level";

    /**
     * Log the message in the response.
     *
     * @param string $message
     *           Log message.
     * @param int $logLevel
     *           Log level.
     */
    public static function log(string $message, int $logLevel = self::LOGGER_ALL)
    {
        global $loggerLever;
        // Verifies if there is a predefined log_level to work with.
        if (!$loggerLever) {
            $loggerLever = isset($_REQUEST["test"]) ? self::LOGGER_ALL : self::LOGGER_IMPORTANT;
        }

        if ($logLevel >= $loggerLever) {
            echo "[ " . date("Y-m-d H:i:s") . " ] $message<br />\n";
        }
    }
}

/**
 * Logger test.
 */
if (isset($_REQUEST["test"])) {
    Logger::log("Test log message.", Logger::LOGGER_IMPORTANT);
}
