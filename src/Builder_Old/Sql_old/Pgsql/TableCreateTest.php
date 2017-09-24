<?php namespace test\Builder\Sql\Pgsql\TableCreate;
use freidcreations\QueryMule\Builder\Sql\Pgsql\TableCreate;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnAdd;

/**
 * Class TableCreateTest
 * @package test\Builder\Sql\Common\TableCreate
 */
class TableCreateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TableCreate
     */
    private $table;

    function setUp()
    {
        $database = $this->getMockBuilder('freidcreations\QueryMule\Builder\Connection\Database')
            ->disableOriginalConstructor()
            ->getMock();

        $database->expects($this->any())->method('driver')->will($this->returnValue('pgsql'));

        $table = $this->getMock('freidcreations\QueryMule\Builder\Sql\Table',[],[
            'some_database_connection_key',
            'some_table_name'
        ]);

        $table->expects($this->any())->method('name')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('dbh')->willReturn($database);

        $this->table = TableCreate::make($table);
    }

    public function tearDown()
    {
        $this->table->reset();
    }

    public function testCreateTable()
    {
        $this->table->create(function(TableColumnAdd $table){
            //do nothing
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( );',trim($this->table->build()->sql()));
    }

    public function testCreateTableIfNotExists()
    {
        $this->table->create(function(TableColumnAdd $table){
            //dso nothing
        },false,true);

        $this->assertEquals('CREATE TABLE IF NOT EXISTS "some_table_name" ( );',trim($this->table->build()->sql()));
    }

    public function testCreateTableTemporary()
    {
        $this->table->create(function(TableColumnAdd $table){
            //do nothing
        },true,false);

        $this->assertEquals('CREATE TEMPORARY TABLE "some_table_name" ( );',trim($this->table->build()->sql()));
    }

    public function testCreateTableTemporaryIfNotExists()
    {
        $this->table->create(function(TableColumnAdd $table){
            //do nothing
        },true,true);

        $this->assertEquals('CREATE TEMPORARY TABLE IF NOT EXISTS "some_table_name" ( );',trim($this->table->build()->sql()));
    }

    public function testCreateTableWithColumnInt()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->int(10);
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" INT );',trim($this->table->build()->sql()));
    }

    public function testCreateTableWithColumnVarchar()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->varchar(50);
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" VARCHAR(50) );',trim($this->table->build()->sql()));
    }

    public function testCreateTableWithColumnBoolean()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->boolean();
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" BOOL );',trim($this->table->build()->sql()));
    }

    public function testCreateTableWithColumnDecimal()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->decimal(2,1);
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" DECIMAL(2,1) );',trim($this->table->build()->sql()));
    }

    public function testCreateTableWithColumn()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->text();
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" TEXT );',trim($this->table->build()->sql()));
    }

    public function testCreateTableWithColumnAndAutoIncrement()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->increment(50);
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" SERIAL );',trim($this->table->build()->sql()));
    }

    public function testCreateTableWithColumnAndNotNull()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->varchar(50)->notNull();
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" VARCHAR(50) NOT NULL );',trim($this->table->build()->sql()));
    }

    public function testCreateTableWithColumnAndNullable()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->varchar(50)->nullable();
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" VARCHAR(50) NULL );',trim($this->table->build()->sql()));
    }

    public function testCreateTableWithColumnAndDefualt()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->varchar(50)->default('some_default_value');
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" VARCHAR(50) DEFAULT ? );',trim($this->table->build()->sql()));
        $this->assertContains('some_default_value', $this->table->build()->parameters());
    }

    public function testThrowExceptionForCreateTableWithColumnAndFirst()
    {
        $this->setExpectedException('Exception');
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->varchar(50)->first();
        },false,false);
    }

    public function testThrowExceptionForCreateTableWithColumnAndAfter()
    {
        $this->setExpectedException('Exception');
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->varchar(50)->after('another_column');
        },false,false);
    }

    public function testCreateTableWithPrimaryKey()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->int();
            $table->add('another_column')->int();
            $table->primaryKey('some_primary_key',['some_column','another_column']);
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" INT ,  "another_column" INT ,  CONSTRAINT "some_primary_key" PRIMARY KEY ("some_column","another_column") );',trim($this->table->build()->sql()));
    }

    public function testCreateTableWithUniqueKey()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->int();
            $table->add('another_column')->int();
            $table->uniqueKey('some_unique',['some_column','another_column']);
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" INT ,  "another_column" INT ,  CONSTRAINT "some_unique" UNIQUE ("some_column","another_column") );',trim($this->table->build()->sql()));
    }

    public function testCreateTableWithIndex()
    {
        $this->table->create(function(TableColumnAdd $table){
            $table->add('some_column')->int();
            $table->add('another_column')->int();
            $table->index('some_index',['some_column', 'another_column']);
        },false,false);

        $this->assertEquals('CREATE TABLE "some_table_name" ( "some_column" INT ,  "another_column" INT ); CREATE INDEX "some_index" ON "some_table_name" ("some_column","another_column");',trim($this->table->build()->sql()));
    }
}