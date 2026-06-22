<?php
header('Content-Type: application/json; charset=utf-8');

$file   = __DIR__ . '/../data/visitas.json';
$method = $_SERVER['REQUEST_METHOD'];

$data = file_exists($file) ? json_decode(file_get_contents($file), true) : ['total' => 0];
if (!is_array($data) || !isset($data['total'])) $data = ['total' => 0];

if ($method === 'POST') {
    $data['total']++;
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

echo json_encode(['total' => $data['total']]);
