<?php
namespace Tracking;

interface StorageTypeInterface {
    public function setup();
    public function insert($data);
    public function allRecords();
    public function countRecords();
    public function findOneBy($key, $value);
}
