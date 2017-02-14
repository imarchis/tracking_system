<?php
namespace Tracking;

interface StorageFacadeInterface
{
    public function  __construct(StorageTypeInterface $storage);
    public function codes();
    public function deliveryDate($code);
}