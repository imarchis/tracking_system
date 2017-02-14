<?php
namespace Tracking;

class StorageSeeder implements StorageSeederInterface
{
    private $storage;

    public function  __construct(StorageTypeInterface $storage) {
        $this->storage = $storage;
    }

    public function run()
    {
        if($this->storage->countRecords() == 0 ){
            for ($i = 0; $i<10; $i++) {
                $data = [
                    'tracking_code' => hash('crc32b', $i),
                    'delivery_date' => date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + $i, date('Y')))
                ];
                $this->storage->insert($data);
            }
        }
    }
}