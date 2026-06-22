<?php
header('Content-Type: application/json; charset=utf-8');

$file = __DIR__ . '/../data/reservas.json';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo file_exists($file) ? file_get_contents($file) : '{}';
    exit;
}

if ($method === 'POST') {
    $raw  = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if ($data === null) {
        http_response_code(400);
        echo json_encode(['error' => 'JSON inválido']);
        exit;
    }
    if (!is_dir(dirname($file))) {
        mkdir(dirname($file), 0755, true);
    }
    $ok = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo $ok !== false ? '{"ok":true}' : '{"error":"No se pudo escribir el archivo"}';
    exit;
}

http_response_code(405);
echo '{"error":"Método no permitido"}';
