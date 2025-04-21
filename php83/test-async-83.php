<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

$client = new Client();
$start = microtime(true);

// Async requests to httpbin.org with 5-second delay each
$promises = [
    $client->getAsync('https://httpbin.org/delay/5'),
    $client->getAsync('https://httpbin.org/delay/5'),
    $client->getAsync('https://httpbin.org/delay/5'),
    $client->getAsync('https://httpbin.org/delay/5'),
    $client->getAsync('https://httpbin.org/delay/5'),
    $client->getAsync('https://httpbin.org/delay/5'),
];

// Wait for all promises to complete
$responses = Utils::settle($promises)->wait();

$end = microtime(true);
$totalTime = round($end - $start, 2);

echo "Total time: {$totalTime} seconds\n";

// Optional: dump response status
foreach ($responses as $index => $result) {
    echo "Request #$index status: " . $result['value']->getStatusCode() . "\n";
}

