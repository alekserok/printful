<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../vendor/autoload.php';

$cache = new \App\CacheShippingOptions();
$api = new \App\ShippingOptions($cache);

$recipient = [
    'address1' => '11025 Westlake Dr',
    'city' => 'Charlotte',
    'country_code' => 'US',
    'state_code' => 'NC',
    'zip' => 28273,
];

$items = [
    [
        'quantity' => 2,
        'variant_id' => 7679
    ]
];

echo '<pre>'; var_dump($api->getData($recipient, $items)); die();
