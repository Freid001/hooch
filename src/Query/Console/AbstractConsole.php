<?php namespace freidcreations\QueryMule\Query\Console;

/**
 * Class AbstractConsole
 * @package freidcreations\QueryMule\src\Query\Console
 */
abstract class AbstractConsole
{
    const BLACK = '30';
    const RED = '31';
    const GREEN = '32';
    const YELLOW = '33';
    const BLUE = '34';
    const BACKGROUND_BLACK = '40';
    const BACKGROUND_RED = '41';
    const BACKGROUND_GREEN = '42';
    const BACKGROUND_YELLOW = '43';
    const BACKGROUND_BLUE = '46';
    const LIGHT_RED = '131';
    const LIGHT_GREEN = '132';
    const LIGHT_BLUE = '136';
    const WHITE = '137';

    /**
     * Run
     * @param $path
     * @param $args
     * @return mixed
     */
    abstract public function run($args);

    /**
     * Fetch Resource
     * @param $path
     * @param $mode
     * @return resource
     */
    protected function fetchResource($path,$mode)
    {
        if($mode == 'r' && !file_exists($path)) {
            return false;
        }

        return fopen($path, $mode);
    }

    /**
     * Get Resource Contents
     * @param $resource
     * @return string
     */
    protected function getResourceContents($resource)
    {
        if(!is_resource($resource)) {
            return false;
        }
        return stream_get_contents($resource);
    }

    /**
     * Write resource
     * @param $resource
     * @param array $json
     */
    protected function writeResource($resource, $content)
    {
        if(is_resource($resource)) {
            fwrite( $resource, $content );
            fclose( $resource );
        }
    }

    /**
     * Output
     * @param $string
     * @param string $color
     * @param int|true $newLines
     * @param bool|false $bleep
     * @param bool|false $clear
     */
    protected function output($string, $color = self::WHITE, $newLines = 1, $bleep = false, $clear = false )
    {
        //Bleep
        if($bleep) {
            echo "\007";
        }

        //Clear
        if($clear) {
            echo "\033[1;1H\033[2J";
        }

        //Output
        echo "\033[".$color."m".$string."\033[0m";

        if($newLines){
            for($i=0;$i<$newLines;$i++){
                echo "\n";
            }
        }
    }

    /**
     * Input
     * @param $populate
     * @param $hide
     * @return string
     */
    protected function input($populate,$hide=false)
    {
        //Populate
        if(!is_null($populate)) {
            echo $populate;
        }

        //Hide input
        if($hide){
            echo "\033[".self::BLACK.";".self::BACKGROUND_BLACK."m";
            $input = trim(fgets(STDIN, 1024));
            echo "\033[0m";
            return $input;
        }

        return trim(fgets(STDIN, 1024));
    }

    /**
     * New Lines
     * @param $number
     */
    protected function newLines($number)
    {
        $this->output("",self::WHITE,$number);
    }
    /**
     * Logo
     * @link http://patorjk.com/software/taag/#p=display&f=Big&t=queryMule
     */
    protected function logo()
    {
        $this->output("
   ____                        __  __       _
  / __ \                      |  \/  |     | |
 | |  | |_   _  ___ _ __ _   _| \  / |_   _| | ___
 | |  | | | | |/ _ \ '__| | | | |\/| | | | | |/ _ \
 | |__| | |_| |  __/ |  | |_| | |  | | |_| | |  __/
  \____\_\__,_|\___|_|   \__, |_|  |_|\__,_|_|\___|
                          __/ |
                         |___/
        ");
    }

}