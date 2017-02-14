<?php
namespace Tracking;

class StorageBuilder implements StorageBuilderInterface
{
    private $storage;

    public function setStorage() {
        if (DEFAULT_STORAGE == 'csv') {
            $this->storage = new CSVStorage(CSV_PATH);
        } elseif (DEFAULT_STORAGE == 'sqlite') {
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