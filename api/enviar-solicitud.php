<?php
header('Content-Type: application/json; charset=utf-8');

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) {
    http_response_code(400);
    echo '{"ok":false,"error":"datos inválidos"}';
    exit;
}

$destinatario = 'ccorreodepruebageneral@gmail.com';

$fecha       = htmlspecialchars($data['fecha']       ?? '—', ENT_QUOTES, 'UTF-8');
$nombre      = htmlspecialchars($data['nombre']      ?? '—', ENT_QUOTES, 'UTF-8');
$correo      = htmlspecialchars($data['correo']      ?? '—', ENT_QUOTES, 'UTF-8');
$celular     = htmlspecialchars($data['celular']     ?? '—', ENT_QUOTES, 'UTF-8');
$tipoevento  = htmlspecialchars($data['tipoevento']  ?? '—', ENT_QUOTES, 'UTF-8');
$personas    = htmlspecialchars($data['personas']    ?? '—', ENT_QUOTES, 'UTF-8');
$comentarios = htmlspecialchars($data['comentarios'] ?? '—', ENT_QUOTES, 'UTF-8');

$asunto = '=?UTF-8?B?' . base64_encode('Nueva solicitud de evento — Quinta los Pinos') . '?=';

$cuerpo = "Nueva solicitud de evento recibida desde quintaLospinos.co\n"
        . str_repeat('─', 50) . "\n\n"
        . "Fecha solicitada : $fecha\n"
        . "Nombre           : $nombre\n"
        . "Correo           : $correo\n"
        . "Celular          : $celular\n"
        . "Tipo de evento   : $tipoevento\n"
        . "Número de personas: $personas\n"
        . "Comentarios      : $comentarios\n\n"
        . str_repeat('─', 50) . "\n"
        . "Quinta los Pinos — sistema automático\n";

$cabeceras = implode("\r\n", [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: base64',
    'From: =?UTF-8?B?' . base64_encode('Quinta los Pinos') . '?= <no-reply@quintalosPinos.co>',
    'Reply-To: ' . $correo,
    'X-Mailer: PHP/' . PHP_VERSION,
]);

$ok = mail($destinatario, $asunto, base64_encode($cuerpo), $cabeceras);
echo json_encode(['ok' => $ok]);
