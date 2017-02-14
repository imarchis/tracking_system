<?php
namespace Tracking;

use PHPUnit_Framework_TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class StorageFacadeTest extends PHPUnit_Framework_TestCase {

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup('src');
    }

    function testStorageFacadeInterface()
    {
        /** @var StorageTypeInterface $csvStorage */
        $csvStorage = $this->getMockBuilder('Tracking\CSVStorage')
            ->disableOriginalConstructor()
            ->getMock();
        $storageFacade = new StorageFacade($csvStorage);
        $this->assertInstanceOf('\Tracking\StorageFacadeInterface', $storageFacade);
    }

    function testStorageFacadeCodes()
    {
        $testFile = 'test.csv';
        $csvStorage = new CSVStorage(vfsStream::url('src/'. $testFile));
        $this->assertFalse($this->root->hasChild($testFile));
        $csvStorage->setup();
        $storageFacade = new StorageFacade($csvStorage);
        $sampleContent =  ['tracking_code' => 'test', 'delivery_date' => '2017-02-14'];
        $csvStorage->insert($sampleContent);
        $this->assertEquals(['codes' => $csvStorage->allRecords()], $storageFacade->codes());
    }

    function testStorageFacadeDeliveryDate()
    {
        $testFile = 'test.csv';
        $csvStorage = new CSVStorage(vfsStream::url('src/'. $testFile));
        $this->assertFalse($this->root->hasChild($testFile));
        $csvStorage->setup();
        $storageFacade = new StorageFacade($csvStorage);
        $sampleContent =  ['tracking_code' => 'test', 'delivery_date' => '2017-02-14'];
        $csvStorage->insert($sampleContent);
        $this->assertEquals(
            ['delivery' => '2017-02-14'],
            $storageFacade->deliveryDate('test')
        );
        $this->assertEquals(
            ['error' => 'Invalid Code'],
            $storageFacade->deliveryDate('random_code')
        );
        $this->assertEquals(
            ['error' => 'Invalid Request'],
            $storageFacade->deliveryDate('')
        );
    }
}