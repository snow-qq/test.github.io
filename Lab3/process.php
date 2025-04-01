<?php
// открытие файла
$inputText = file_get_contents('input.txt');

// разделение текста на слова
$words = preg_split('/\s+/', $inputText);

// обработка слов
for ($i = 0; $i < count($words); $i++) {
    if ($i % 2 == 0) {
        $words[$i] = strtoupper($words[$i]);
    }
}

// Соединяем слова
$outputText = implode(' ', $words);

// output file
file_put_contents('output.txt', $outputText);

echo "Processing complete! Check output.txt for results.\n";
?> 