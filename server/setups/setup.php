<?php
class Setup
{
    /**
     * var string $car Car name.
     */
    public $car;

    /**
     * var string $driver Driver name.
     */
    public $driver;
    
    /**
     * var string $ini Setup file content.
     */
    public $ini;
    
    /**
     * 
     * var string $name Setup name.
     */
    public $name;

    /**
     * var string $sp Pit strategy file content;
     */
    public $sp;

    /**
     * var string $track Track name.
     */
    public $track;

    /**
     * Default constructor.
     * @param string $ini Setup file content.
     * @param string $sp Pit strategy file content.
     */
    public function __construct(string $ini = "", string $sp = "") {
        $this->ini = $ini;
        $this->sp = $sp;
    }

    /**
     * Check setup integrity.
     */
    public function isValid(): bool {
        // SP file is not required.
        return $this->ini != null && $this->ini != "";
    }

    /**
     * Simplify the class to  just the .ini and .sp contents.
     * @return array
     */
    public function simplify(): array {
        $simpleSetup = array();
        $simpleSetup["ini"] = $this->ini;
        $simpleSetup["sp"] = $this->sp;
        return $simpleSetup;
    }

    /**
     * Load a setup from file.
     * @param string $path Path of the file without the extension.
     * @return Setup The new setup.
     */
    public static function fromFile(string $path): Setup {
        $setup = new Setup();

        // Loads the .ini file contents.
        if (file_exists("$path.ini")) {
            $tmp = file_get_contents("$path.ini");
            if ($tmp !== false) {
                $setup->ini = $tmp;
            }
        }

        // Loads the .sp file contents.
        if (file_exists("$path.sp")) {
            $tmp = file_get_contents("$path.sp");
            if ($tmp !== false) {
                $setup->sp = $tmp;
            }
        }

        // Parse setup info.
        Setup::parseInfoFromPath($path, $setup);

        return $setup;
    }

    /**
     * Parse setup infos from the path of the file.
     */
    public static function parseInfoFromPath(string $path, Setup &$setup) {
        list($track, $car, $driver) = explode(".", $path);
        $setup->car = $car;
        $setup->driver = $driver;
        $setup->name = substr($path, strpos($path, $driver) + strlen($driver) + 1);
        $setup->track = $track;
    }
}
?>