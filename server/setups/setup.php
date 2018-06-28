<?php

class Setup
{

    /**
     * var stdClass $info Stores the setup informations.
     */
    public $info;

    /**
     * var string $ini Setup file content.
     */
    public $ini;

    /**
     * var string $sp Pit strategy file content;
     */
    public $sp;

    /**
     * Default constructor.
     *
     * @param string $ini
     *            Setup file content.
     * @param string $sp
     *            Pit strategy file content.
     */
    public function __construct(string $ini = "", string $sp = "")
    {
        $this->info = new stdClass();
        $this->ini = $ini;
        $this->sp = $sp;
    }

    /**
     * Check setup integrity.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        // SP file and info are not required.
        return $this->ini != null && $this->ini != "";
    }

    /**
     * Load a setup from file.
     *
     * @param string $path
     *            Path of the file without the extension.
     * @return Setup The new setup.
     */
    public static function fromFile(string $path): Setup
    {
        $setup = new Setup();
        
        // Loads the .ini file contents.
        if (file_exists("files/$path.ini")) {
            $tmp = file_get_contents("files/$path.ini");
            if ($tmp !== false) {
                $setup->ini = $tmp;
            }
        }
        
        // Loads the .sp file contents.
        if (file_exists("files/$path.sp")) {
            $tmp = file_get_contents("files/$path.sp");
            if ($tmp !== false) {
                $setup->sp = $tmp;
            }
        }
        
        // Parse setup info.
        Setup::parseInfoFromName($path, $setup);
        
        return $setup;
    }

    /**
     * Parse setup infos from the path of the file.
     *
     * @param string $fileName
     *            Setup file name.
     * @param Setup $setup
     *            Instance of a setup class to update.
     */
    public static function parseInfoFromName(string $fileName, Setup &$setup)
    {
        list ($track, $car, $driver) = explode(".", $fileName);
        $setup->info->car = $car;
        $setup->info->driver = $driver;
        $setup->info->name = substr($fileName, strpos($fileName, $driver) + strlen($driver) + 1);
        $setup->info->track = $track;
        
        $setup->info->name = str_replace(".ini", "", $setup->info->name);
    }
}
?>