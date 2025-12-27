<?php
require_once 'config.php';
requireLogin();

$fileName = $_GET['file'] ?? '';
$filesDir = __DIR__ . '/files/';
$filePath = $filesDir . basename($fileName);

// Verificar que el archivo existe y está en la carpeta permitida
if (empty($fileName) || !file_exists($filePath) || !is_file($filePath)) {
    die('File not found');
}

// Obtener información del archivo
$fileSize = filesize($filePath);
$mimeType = mime_content_type($filePath);

// Headers para forzar descarga
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
header('Content-Length: ' . $fileSize);
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: public');

// Limpiar buffer y enviar archivo
ob_clean();
flush();
readfile($filePath);
exit;
?>
