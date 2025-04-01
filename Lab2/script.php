<?php
$filename = 'sample.txt';

// Проверяем, существует ли файл
if (!file_exists($filename)) {
    die("Файл не найден: $filename");
}

// Читаем файл, убираем символы перевода строки, если это нужно
$lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$pattern = '/\b[A-Z][a-zA-Z]*[A-Z]\b/';

foreach ($lines as $line) {
    // Если строка содержит слово, удовлетворяющее условию, выводим её
    if (preg_match($pattern, $line)) {
        echo $line . "\n";
    }
}
?>
