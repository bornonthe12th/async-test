<?php

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

require 'vendor/autoload.php';

$client = new Client();

$start = microtime(true);

// Build all promises first
$promises = [];
for ($i = 0; $i < 6; $i++) {
    $promises[$i] = $client->getAsync('http://mock-server/sleep.php');
}

// Wait for all to settle concurrently
$results = Utils::settle($promises)->wait();

$end = microtime(true);

echo "\n";

foreach ($results as $i => $result) {
    if ($result['state'] === 'fulfilled') {
        echo "✅ Request #$i status: " . $result['value']->getStatusCode() . "\n";
    } else {
        echo "❌ Request #$i failed: " . $result['reason'] . "\n";
    }
}

$total = round($end - $start, 2);
echo "\n⏱️ Total time: {$total} seconds\n";
