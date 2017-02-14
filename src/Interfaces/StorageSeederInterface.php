<?php
namespace Tracking;

interface StorageSeederInterface
{
    public function  __construct(StorageTypeInterface $storage);
    public function run();
}