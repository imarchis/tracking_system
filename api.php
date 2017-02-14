<?php

include('vendor/autoload.php');
require('src/config.php');

$page = "";
if (isset($_GET["page"])) {
    $page = $_GET["page"];
    $builder = new \Tracking\StorageBuilder();
    $builder->setStorage(DEFAULT_STORAGE);
    $storage = $builder->getStorage();
    if($storage != null){
        $seeder = new \Tracking\StorageSeeder($storage);
        $seeder->run();
        switch ($page) {
            case "codes":
                $storageService = new \Tracking\StorageFacade($storage);
                $response = $storageService->codes();
                echo json_encode($response);
                break;
            case "delivery-date":
                if (isset($_GET['code'])) {
                    $code = htmlspecialchars(htmlentities($_GET['code']));
                    $storageService = new \Tracking\StorageFacade($storage);
                    $response = $storageService->deliveryDate($code);
                    if (isset($response['error'])) {
                        header("HTTP/1.0 401 Not Found");
                    }
                    echo json_encode($response);
                } else {
                    header("HTTP/1.0 401 Not Found");
                    $response['error'] = 'Invalid Request';
                    echo json_encode($response);
                }
                break;
            case "" :
                header("HTTP/1.0 404 Not Found");
                $response['error'] = 'Page Not Found';
                echo json_encode($response);
                break;
        }
    } else {
        header("HTTP/1.0 500 Server Error");
        $response['error'] = 'Server Misconfiguration';
        echo json_encode($response);
        die;
    }
} else {
    header("HTTP/1.0 404 Not Found");
    $response['error'] = 'Bad Request';
    echo json_encode($response);
}
