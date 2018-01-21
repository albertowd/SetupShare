<?php
require_once "setup.php";

class SetupUtils
{
    /**
     * Check the params passed through HTTP and return a default value.
     * @param string $param Searched param.
     */
    public static function checkParam(string $param) {
        return isset($_REQUEST[$param]) && strlen($_REQUEST[$param]) > 0 ? $_REQUEST[$param] : null;
    }

    /**
     * Read setups from file and return them.
     */
    public static function listSetups(): array {
        $setups = array();

        foreach (scandir(".") as &$fileName) {
            if (!is_dir($fileName) && strrpos($fileName, ".ini") != false) {
                array_push($setups, Setup::fromFile(substr($fileName, 0, -4)));
            }
        }

        return $setups;
    }

    /**
     * Search setups by file name to return to the App.
     * @param string $car Name of the wanted car.
     * @param string $track Name of the wanted track.
     */
    public static function listSetupsToApp(string $car, string $track): array
    {
        $setups = array();

        $filter = "$track.$car";
        $filterLen = strlen($filter);
        foreach (scandir(".") as &$fileName) {
            if (!is_dir($fileName) && strrpos($fileName, ".ini") != false && strpos($fileName, $filter) === 0) {
                $setup = new Setup();
                Setup::parseInfoFromPath(substr($fileName, 0, -4), $setup);

                if (!isset($setups[$setup->driver])) {
                    $setups[$setup->driver] = array();
                }
                array_push($setups[$setup->driver], $setup->name);
            }
        }

        return $setups;
    }
}
?>