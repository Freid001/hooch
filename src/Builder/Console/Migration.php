<?php namespace freidcreations\QueryMule\Builder\Console;
use freidcreations\QueryMule\Query\Console\AbstractConsole;


/**
 * Class Migration
 * @package freidcreations\QueryMule\src\Builder\Console
 */
class Migration extends AbstractConsole
{
    /**
     * @var string
     */
    private $configPath;

    /**
     * Migration constructor.
     * @param $path
     * @param $command
     */
    public function __construct($path)
    {
        $this->configPath = $path;
    }

    /**
     * Run
     * @param $args
     */
    public function run($args)
    {

    }
}