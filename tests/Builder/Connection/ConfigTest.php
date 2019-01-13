<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use QueryMule\Builder\Connection\Config;
use QueryMule\Builder\Connection\Handler\DatabaseHandler;
//use QueryMule\Builder\Exception\DatabaseException;
use QueryMule\Query\Connection\Handler\DatabaseHandlerInterface;

///**
// * Class ConfigTest
// * @package test\Builder\Sql\MySql
// */
//class ConfigTest extends TestCase
//{
////    public function testInvalidConfig()
////    {
////        $this->expectException(DatabaseException::class);
////
////        $config = new Config();
////        $config->dbh(getenv('DB_NAME'));
////    }
//}
