<?php
require_once "setup.php";

class SetupUtils
{

    /**
     * Check the params passed through HTTP and return a default value.
     *
     * @param string $param
     *            Searched param.
     */
    public static function checkParam(string $param)
    {
        return isset($_REQUEST[$param]) && strlen($_REQUEST[$param]) > 0 ? $_REQUEST[$param] : null;
    }

    /**
     * Search for filtered setups.
     *
     * @param string $car
     *            Car name, if there is.
     * @param string $track
     *            Track name, if there is.
     * @return array List of info of each setup available.
     */
    public static function listSetups(string &$car, string &$track): array
    {
        $setups = array();
        foreach (scandir("files") as &$fileName) {
            if (! is_dir($fileName) && strrpos($fileName, ".ini") !== false) {
                $info = explode(".", $fileName);
                if ($track != "" && $info[0] != $track) {
                    continue;
                }
                if ($car != "" && $info[1] != $car) {
                    continue;
                }
                
                $setup = new Setup();
                Setup::parseInfoFromName($fileName, $setup);
                array_push($setups, $setup->info);
            }
        }
        
        return $setups;
    }
}
?>