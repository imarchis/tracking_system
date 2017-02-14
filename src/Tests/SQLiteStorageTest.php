<?php
namespace Tracking;

use PHPUnit_Framework_TestCase;

class SQLiteStorageTest extends PHPUnit_Framework_TestCase {

    function setUp(){
        if(is_file(dirname(__FILE__) . '/sqlite/test.sqlite3')){
            unlink(dirname(__FILE__) . '/sqlite/test.sqlite3');
        }
        if (file_exists(dirname(__FILE__) . '/sqlite')) {
            rmdir(dirname(__FILE__)  . '/sqlite');
        }
        mkdir(dirname(__FILE__).'/sqlite', 0777);
    }

    function testSQLiteStorageInterface() {
        $testFile = dirname(__FILE__).'/sqlite/'.'test.sqlite3';
        $csvStorage = new SQLiteStorage($testFile);
        $this->assertInstanceOf('\Tracking\StorageTypeInterface', $csvStorage);
    }

    function testSQLiteStorageSetup() {
        $testFile = dirname(__FILE__).'/sqlite/'.'test.sqlite3';
        $storage = new SQLiteStorage($testFile);
        $storage->setup();
        $sqlite = new \SQLite3($testFile);
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='orders';";
        $x = $sqlite->exec($sql);
        $this->assertTrue($x);
    }

    function testSQLiteStorageInsert() {
        $testFile = dirname(__FILE__).'/sqlite/'.'test.sqlite3';
        $storage = new SQLiteStorage($testFile);
        $storage->setup();
        $sampleContent =  ['tracking_code' => 'test', 'delivery_date' => '2017-02-14'];
        $storage->insert($sampleContent);
        $sqlite = new \SQLite3($testFile);
        $sql = "SELECT COUNT(*) as entries FROM orders;";
        $ret = $sqlite->query($sql);
        $found = $ret->fetchArray(SQLITE3_ASSOC)['entries'];
        $this->assertEquals($found, 1);
    }

    function testSQLiteStorageCountRecords() {
        $testFile = dirname(__FILE__).'/sqlite/'.'test.sqlite3';
        $storage = new SQLiteStorage($testFile);
        $storage->setup();
        for ($i = 0; $i<2; $i++) {
            $data = [
                'tracking_code' => hash('crc32b', $i),
                'delivery_date' => date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + $i, date('Y')))
            ];
            $storage->insert($data);
        }
        $sqlite = new \SQLite3($testFile);
        $sql = "SELECT COUNT(*) as entries FROM orders;";
        $ret = $sqlite->query($sql);
        $found = $ret->fetchArray(SQLITE3_ASSOC)['entries'];
        $this->assertEquals($found, 2);
        $this->assertEquals($storage->countRecords(), 2);
        $this->assertEquals($storage->countRecords(), $found);
    }

    function testSQLiteStorageAllRecords() {
        $testFile = dirname(__FILE__).'/sqlite/'.'test.sqlite3';
        $storage = new SQLiteStorage($testFile);
        $storage->setup();
        for ($i = 0; $i<2; $i++) {
            $data = [
                'tracking_code' => hash('crc32b', $i),
                'delivery_date' => date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + $i, date('Y')))
            ];
            $storage->insert($data);
        }
        $sqlite = new \SQLite3($testFile);
        $sql = "SELECT * FROM orders;";
        $ret = $sqlite->query($sql);
        $found = [];
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $found[] = $row;
        }
        $this->assertEquals($storage->allRecords(), $found);
    }

    function testSQLiteStorageFindOneBy() {
        $testFile = dirname(__FILE__).'/sqlite/'.'test.sqlite3';
        $storage = new SQLiteStorage($testFile);
        $storage->setup();
        $sampleContent =  ['tracking_code' => 'test', 'delivery_date' => '2017-02-14'];
        $storage->insert($sampleContent);
        $result = $storage->findOneBy('tracking_code','test');
        $this->assertEquals($result['tracking_code'], 'test');
        $this->assertEquals($result['delivery_date'], '2017-02-14');
    }

    function  tearDown(){
        if(is_file(dirname(__FILE__) . '/sqlite/test.sqlite3')){
            unlink(dirname(__FILE__) . '/sqlite/test.sqlite3');
        }
        if (file_exists(dirname(__FILE__) . '/sqlite')) {
            rmdir(dirname(__FILE__)  . '/sqlite');
        }
    }
}