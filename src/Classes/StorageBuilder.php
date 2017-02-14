<?php
namespace Tracking;

class StorageBuilder implements StorageBuilderInterface
{
    private $storage;

    public function setStorage($type) {
        if ($type == 'csv') {
            $this->storage = new CSVStorage(CSV_PATH);
        } elseif ($type == 'sqlite') {
            $this->storage = new SQLiteStorage(SQLITE_PATH);
        } else {
            $this->storage = null;
        }
    }

    public function getStorage()
    {
        return $this->storage;
    }
}