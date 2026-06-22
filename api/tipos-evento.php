<?php
header('Content-Type: application/json; charset=utf-8');

$file   = __DIR__ . '/../data/tipos-evento.json';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo file_exists($file) ? file_get_contents($file) : '[]';
    exit;
}

if ($method === 'POST') {
    $raw  = file_get_contents('php://input');
    $body = json_decode($raw, true);
    $tipo = trim($body['tipo'] ?? '');
    if (!$tipo) { http_response_code(400); echo '{"error":"tipo vacío"}'; exit; }
    $list = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    if (!is_array($list)) $list = [];
    if (!in_array($tipo, $list)) {
        $list[] = $tipo;
        usort($list, 'strcoll');
    }
    file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo '{"ok":true}';
    exit;
}

if ($method === 'DELETE') {
    $raw  = file_get_contents('php://input');
    $body = json_decode($raw, true);
    $tipo = trim($body['tipo'] ?? '');
    $list = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    if (!is_array($list)) $list = [];
    $list = array_values(array_filter($list, fn($t) => $t !== $tipo));
    file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo '{"ok":true}';
    exit;
}

http_response_code(405);
echo '{"error":"Método no permitido"}';
