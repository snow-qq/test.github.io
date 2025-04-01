<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Обработка файлов</title>
    <style>
        body {
            background-color: #1e1e1e;
            color: #d4d4d4;
            font-family: 'Consolas', 'Monaco', monospace;
            padding: 20px;
            margin: 0;
        }
        .log-container {
            background-color: #2d2d2d;
            border-radius: 5px;
            padding: 20px;
            white-space: pre-wrap;
            font-size: 14px;
            line-height: 1.5;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .timestamp {
            color: #569cd6;
        }
        .separator {
            color: #808080;
        }
        .message {
            color: #d4d4d4;
        }
        .success {
            color: #6a9955;
        }
        .error {
            color: #f14c4c;
        }
        .warning {
            color: #dcdcaa;
        }
    </style>
</head>
<body>
    <div class="log-container">
<?php

require_once __DIR__ . '/vendor/autoload.php';

use lab7\FileProcessor;

ob_start();
$processor = new FileProcessor();
$processor->processFiles();
$output = ob_get_clean();

// Преобразуем спецсимволы в HTML-сущности
$output = htmlspecialchars($output, ENT_QUOTES, 'UTF-8');

// Добавляем цветовое оформление для различных типов сообщений
$output = preg_replace(
    '/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) \| /',
    '<span class="timestamp">$1</span><span class="separator"> | </span>',
    $output
);

// Раскрашиваем сообщения в зависимости от типа
$output = str_replace('❌', '<span class="error">❌</span>', $output);
$output = str_replace('✅', '<span class="success">✅</span>', $output);
$output = str_replace('⚠️', '<span class="warning">⚠️</span>', $output);

echo $output;
?>
    </div>
</body>
</html> 