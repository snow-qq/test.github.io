<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['quiz_started']) || !isset($_SESSION['answers'])) {
    header('Location: index.php');
    exit;
}

// Create results directory if it doesn't exist
$results_dir = 'results';
if (!file_exists($results_dir)) {
    if (!mkdir($results_dir, 0777, true)) {
        die('Не удалось создать директорию для результатов');
    }
}

// Check if directory is writable
if (!is_writable($results_dir)) {
    die('Директория results не доступна для записи. Проверьте права доступа.');
}

// Generate unique filename using session ID and timestamp
$timestamp = date('Y-m-d_H-i-s');
$filename = $results_dir . '/result_' . session_id() . '_' . $timestamp . '.txt';

// Prepare results content
$content = "Результаты теста по методам сортировки\n";
$content .= "====================================\n\n";
$content .= "ФИО: " . $_SESSION['user_name'] . "\n";
$content .= "Группа: " . $_SESSION['user_group'] . "\n";
$content .= "Дата и время прохождения: " . date('d.m.Y H:i:s') . "\n\n";
$content .= "Результаты:\n";
$content .= "Правильных ответов: " . $_SESSION['score'] . " из " . count($_SESSION['questions']) . "\n";
$content .= "Процент правильных ответов: " . round(($_SESSION['score'] / count($_SESSION['questions'])) * 100, 2) . "%\n\n";
$content .= "Детальные ответы:\n";
$content .= "----------------\n\n";

foreach ($_SESSION['answers'] as $index => $answer) {
    $content .= "Вопрос " . ($index + 1) . ":\n";
    $content .= $answer['question'] . "\n";
    $content .= "Ваш ответ: " . $answer['user_answer'] . "\n";
    $content .= "Результат: " . ($answer['correct'] ? "Правильно" : "Неправильно") . "\n\n";
}

// Save results to file
if (file_put_contents($filename, $content) === false) {
    die('Не удалось сохранить результаты в файл');
}

// Add file to ZIP archive
$zip_file = $results_dir . '/quiz_results.zip';
$zip = new ZipArchive();

if (file_exists($zip_file)) {
    if ($zip->open($zip_file, ZipArchive::CHECKCONS) !== true) {
        die('Не удалось открыть ZIP архив');
    }
} else {
    if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::CHECKCONS) !== true) {
        die('Не удалось создать ZIP архив');
    }
}

if ($zip->addFile($filename, basename($filename)) === false) {
    die('Не удалось добавить файл в ZIP архив');
}

$zip->close();

// Get all results files
$all_results = [];
$files = glob($results_dir . '/result_*.txt');
foreach ($files as $file) {
    $content = file_get_contents($file);
    if ($content) {
        $all_results[] = [
            'filename' => basename($file),
            'content' => $content
        ];
    }
}

// Clear session data
$user_name = $_SESSION['user_name'];
$user_group = $_SESSION['user_group'];
$score = $_SESSION['score'];
$total_questions = count($_SESSION['questions']);
$answers = $_SESSION['answers'];

session_destroy();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты теста</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .result-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .correct {
            color: #4CAF50;
        }
        .incorrect {
            color: #f44336;
        }
        .download-links {
            margin-top: 20px;
        }
        .download-links a {
            display: inline-block;
            margin-right: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .download-links a:hover {
            background-color: #45a049;
        }
        .all-results {
            margin-top: 40px;
        }
        .all-results h2 {
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .result-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .result-item pre {
            white-space: pre-wrap;
            font-family: inherit;
            margin: 0;
        }
        .result-item .date {
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <h1>Результаты теста</h1>
    
    <div class="result-card">
        <h2>Общая информация</h2>
        <p><strong>ФИО:</strong> <?php echo htmlspecialchars($user_name); ?></p>
        <p><strong>Группа:</strong> <?php echo htmlspecialchars($user_group); ?></p>
        <p><strong>Дата и время прохождения:</strong> <?php echo date('d.m.Y H:i:s'); ?></p>
        <p><strong>Правильных ответов:</strong> <?php echo $score; ?> из <?php echo $total_questions; ?></p>
        <p><strong>Процент правильных ответов:</strong> <?php echo round(($score / $total_questions) * 100, 2); ?>%</p>
    </div>

    <div class="result-card">
        <h2>Детальные ответы</h2>
        <?php foreach ($answers as $index => $answer): ?>
            <div class="answer">
                <h3>Вопрос <?php echo $index + 1; ?></h3>
                <p><strong>Вопрос:</strong> <?php echo htmlspecialchars($answer['question']); ?></p>
                <p><strong>Ваш ответ:</strong> <?php echo htmlspecialchars($answer['user_answer']); ?></p>
                <p class="<?php echo $answer['correct'] ? 'correct' : 'incorrect'; ?>">
                    <strong>Результат:</strong> <?php echo $answer['correct'] ? 'Правильно' : 'Неправильно'; ?>
                </p>
                <?php if (!$answer['correct']): ?>
                    <p><strong>Объяснение:</strong> <?php echo getExplanation($answer['question']); ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="download-links">
        <a href="download.php?file=<?php echo urlencode(basename($filename)); ?>" download>Скачать результаты в TXT</a>
        <a href="download.php?file=quiz_results.zip" download>Скачать все результаты (ZIP)</a>
        <a href="index.php">Пройти тест еще раз</a>
    </div>

    <div class="all-results">
        <h2>Все результаты тестирования</h2>
        <?php foreach ($all_results as $result): ?>
            <div class="result-item">
                <div class="date">
                    <?php 
                    if (preg_match('/result_.*?_(\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2})\.txt/', $result['filename'], $matches)) {
                        $date = DateTime::createFromFormat('Y-m-d_H-i-s', $matches[1]);
                        echo $date ? $date->format('d.m.Y H:i:s') : '';
                    }
                    ?>
                </div>
                <pre><?php echo htmlspecialchars($result['content']); ?></pre>
                <a href="download.php?file=<?php echo urlencode($result['filename']); ?>" download>Скачать</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

<?php
function getExplanation($question) {
    $explanations = [
        'Что такое сортировка?' => 'Сортировка - это процесс упорядочивания элементов в определенном порядке (по возрастанию, убыванию и т.д.).',
        'Какая временная сложность у пузырьковой сортировки?' => 'Пузырьковая сортировка имеет временную сложность O(n²), так как использует два вложенных цикла.',
        'Какой алгоритм сортировки работает эффективнее всего на почти отсортированных данных?' => 'Сортировка вставками эффективна на почти отсортированных данных, так как каждый элемент перемещается только на небольшое расстояние.',
        'Какой элемент выбирается как опорный в быстрой сортировке?' => 'В быстрой сортировке опорный элемент может быть выбран любым способом, но от выбора зависит эффективность алгоритма.',
        'Какие преимущества имеет сортировка слиянием?' => 'Сортировка слиянием является стабильной сортировкой, что важно при сортировке сложных объектов.',
        'На какой структуре данных основана пирамидальная сортировка?' => 'Пирамидальная сортировка использует двоичную кучу (heap) для организации данных.',
        'В каких случаях сортировка подсчетом эффективна?' => 'Сортировка подсчетом эффективна при небольшом диапазоне значений, так как использует дополнительный массив размером с диапазон значений.',
        'Как работает сортировка выбором?' => 'Сортировка выбором находит минимальный элемент и меняет его с первым элементом, затем повторяет процесс для оставшейся части массива.',
        'Что является основой сортировки Шелла?' => 'Сортировка Шелла основана на сортировке вставками, но использует убывающие шаги для улучшения эффективности.',
        'Какие алгоритмы сортировки являются стабильными?' => 'Сортировка слиянием и сортировка вставками являются стабильными, сохраняя относительный порядок равных элементов.'
    ];
    
    return isset($explanations[$question]) ? $explanations[$question] : 'Правильный ответ отличается от выбранного вами.';
}
?> 