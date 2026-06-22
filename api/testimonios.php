<?php
header('Content-Type: application/json; charset=utf-8');

$file   = __DIR__ . '/../data/testimonios.json';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo file_exists($file) ? file_get_contents($file) : '[]';
    exit;
}

if ($method === 'POST') {
    $raw   = file_get_contents('php://input');
    $entry = json_decode($raw, true);
    if ($entry === null) {
        http_response_code(400);
        echo '{"error":"JSON inválido"}';
        exit;
    }
    $existing = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    if (!is_array($existing)) $existing = [];
    $existing[] = $entry;
    file_put_contents($file, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo '{"ok":true}';
    exit;
}

http_response_code(405);
echo '{"error":"Método no permitido"}';
