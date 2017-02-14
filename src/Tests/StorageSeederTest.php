<?php
namespace Tracking;

use PHPUnit_Framework_TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class StorageSeederTest extends PHPUnit_Framework_TestCase {

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup('src');
    }
    function testStorageSeederInterface()
    {
        /** @var StorageTypeInterface $csvStorage */
        $csvStorage = $this->getMockBuilder('Tracking\CSVStorage')
            ->disableOriginalConstructor()
            ->getMock();
        $storageSeeder = new StorageSeeder($csvStorage);
        $this->assertInstanceOf('\Tracking\StorageSeederInterface', $storageSeeder);
    }

    function testStorageSeederRun()
    {
        $testFile = 'test.csv';
        $csvStorage = new CSVStorage(vfsStream::url('src/'. $testFile));
        $this->assertFalse($this->root->hasChild($testFile));
        $csvStorage->setup();
        $storageSeeder = new StorageSeeder($csvStorage);
        $storageSeeder->run();
        $this->assertEquals($csvStorage->countRecords(), 10);
    }
}