<?php
namespace Tracking;

interface StorageBuilderInterface
{
    public function setStorage($type);
    public function getStorage();
}