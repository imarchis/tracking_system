<?php
namespace Tracking;

class StorageFacade implements StorageFacadeInterface
{
    private $storage;

    public function  __construct(StorageTypeInterface $storage) {
        $this->storage = $storage;
    }

    public function codes()
    {
        $codes = $this->storage->allRecords();
        return ['codes' => $codes];
    }

    public function deliveryDate($code)
    {
        $response = [];
        if ($code != '') {
            $entry = $this->storage->findOneBy('tracking_code', $code);
            if(!empty($entry)) {
                $response['delivery'] = $entry['delivery_date'];
            } else {
                $response['error'] = 'Invalid Code';
            }
        } else {
            $response['error'] = 'Invalid Request';
        }
        return $response;
    }
}