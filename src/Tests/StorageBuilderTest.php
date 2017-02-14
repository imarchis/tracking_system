<?php
namespace Tracking;

use PHPUnit_Framework_TestCase;

class StorageBuilderTest extends PHPUnit_Framework_TestCase {

    function testStorageBuilderInterface()
    {
        $storageBuilder = new StorageBuilder();
        $this->assertInstanceOf('\Tracking\StorageBuilderInterface', $storageBuilder);
    }

    function testStorageBuilderSetStorageNull()
    {
        $storageBuilder = new StorageBuilder();
        $storageBuilder->setStorage('test');
        $storage = $storageBuilder->getStorage();
        $this->assertNull($storage);
    }

    function testStorageBuilderSetStorageAsCSV()
    {
        define('CSV_PATH', dirname(dirname(__FILE__)). "/DataStores/CSV/db.csv");
        $storageBuilder = new StorageBuilder();
        $storageBuilder->setStorage('csv');
        $storage = $storageBuilder->getStorage();
        $this->assertInstanceOf('\Tracking\CSVStorage', $storage);
    }

    function testStorageBuilderSetStorageAsSqlite()
    {
        define('SQLITE_PATH', dirname(dirname(__FILE__)). "/DataStores/SQLite/db.sqlite3");
        $storageBuilder = new StorageBuilder();
        $storageBuilder->setStorage('sqlite');
        $storage = $storageBuilder->getStorage();
        $this->assertInstanceOf('\Tracking\SQLiteStorage', $storage);
    }


}