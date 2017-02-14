<?php
namespace Tracking;

use PHPUnit_Framework_TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class CSVStorageTest extends PHPUnit_Framework_TestCase {

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup('src');
    }

    function testCSVStorageInterface() {
        $testFile = 'test.csv';
        $csvStorage = new CSVStorage(vfsStream::url('src/'. $testFile));
        $this->assertInstanceOf('\Tracking\StorageTypeInterface', $csvStorage);
    }

    function testCSVStorageSetup() {
        $testFile = 'test.csv';
        $storage = new CSVStorage(vfsStream::url('src/'. $testFile));
        $this->assertFalse($this->root->hasChild($testFile));
        $sampleContent =  ['id', 'tracking_code', 'delivery_date'];
        $storage->setup();
        $this->assertTrue($this->root->hasChild($testFile));
        $this->assertEquals($this->root->getChild($testFile)->getContent(), implode(',',$sampleContent)."\n");
    }

    function testCSVStorageInsert() {
        $testFile = 'test.csv';
        $storage = new CSVStorage(vfsStream::url('src/'. $testFile));
        $this->assertFalse($this->root->hasChild($testFile));
        $sampleContent =  ['tracking_code' => 'test', 'delivery_date' => '2017-02-14'];
        $storage->setup();
        $storage->insert($sampleContent);
        $this->assertEquals(count(file(vfsStream::url('src/'.$testFile), FILE_SKIP_EMPTY_LINES)) - 1, 1);
    }

    function testCSVStorageCountRecords() {
        $testFile = 'test.csv';
        $storage = new CSVStorage(vfsStream::url('src/'. $testFile));
        $this->assertFalse($this->root->hasChild($testFile));
        $storage->setup();
        for ($i = 0; $i<2; $i++) {
            $data = [
                'tracking_code' => hash('crc32b', $i),
                'delivery_date' => date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + $i, date('Y')))
            ];
            $storage->insert($data);
        }
        $this->assertEquals(
            $storage->countRecords(),
            count(file(vfsStream::url('src/'.$testFile), FILE_SKIP_EMPTY_LINES)) - 1
        );
    }

    function testCSVStorageAllRecords() {
        $testFile = 'test.csv';
        $storage = new CSVStorage(vfsStream::url('src/'. $testFile));
        $this->assertFalse($this->root->hasChild($testFile));
        $storage->setup();
        for ($i = 0; $i<2; $i++) {
            $data = [
                'tracking_code' => hash('crc32b', $i),
                'delivery_date' => date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + $i, date('Y')))
            ];
            $storage->insert($data);
        }
        $this->assertEquals(count($storage->allRecords()), 2);
    }

    function testCSVStorageFindOneBy() {
        $testFile = 'test.csv';
        $storage = new CSVStorage(vfsStream::url('src/'. $testFile));
        $this->assertFalse($this->root->hasChild($testFile));
        $storage->setup();
        $sampleContent =  ['tracking_code' => 'test', 'delivery_date' => '2017-02-14'];
        $storage->setup();
        $storage->insert($sampleContent);
        $result = $storage->findOneBy('tracking_code','test');
        $this->assertEquals($result['tracking_code'], 'test');
        $this->assertEquals($result['delivery_date'], '2017-02-14');
    }
}