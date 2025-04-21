<?php
// sleep.php or delay.php
sleep(5);
header('Content-Type: application/json');
echo json_encode(['status' => 'ok']);
