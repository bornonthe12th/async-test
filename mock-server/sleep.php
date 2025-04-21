<?php
$ts = date('H:i:s');
$pid = getmypid(); // unique per request (can act as thread ID)

file_put_contents('php://stdout', "👤 Request handled by PID $pid at $ts\n", FILE_APPEND);

sleep(5);

header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'handled_by' => $pid,
    'time' => $ts,
]);