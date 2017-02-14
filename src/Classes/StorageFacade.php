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
        return json_encode(['codes' => $codes]);
    }

    public function deliveryDate($code)
    {
        $response = [];
        if ($code != '') {
            $entry = $this->storage->findOneBy('tracking_code', $code);
            if(!empty($entry)) {
                $response['delivery'] = $entry['delivery_date'];
            } else {
                header("HTTP/1.0 404 Not Found");
                $response['error'] = 'Invalid Code';
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            $response['error'] = 'Invalid Request';
        }
        return json_encode($response);
    }
}