<?php
session_start();

// Define quiz questions
$questions = [
    [
        'topic' => 'Основные понятия',
        'question' => 'Что такое сортировка?',
        'options' => [
            'Процесс упорядочивания элементов в определенном порядке',
            'Процесс удаления элементов из массива',
            'Процесс добавления новых элементов в массив',
            'Процесс копирования элементов массива'
        ],
        'correct' => [0]
    ],
    [
        'topic' => 'Сложность алгоритмов',
        'question' => 'Какая временная сложность у пузырьковой сортировки?',
        'options' => [
            'O(n)',
            'O(n log n)',
            'O(n²)',
            'O(1)'
        ],
        'correct' => [2]
    ],
    [
        'topic' => 'Сортировка вставками',
        'question' => 'Какой алгоритм сортировки работает эффективнее всего на почти отсортированных данных?',
        'options' => [
            'Быстрая сортировка',
            'Сортировка вставками',
            'Сортировка слиянием',
            'Пирамидальная сортировка'
        ],
        'correct' => [1]
    ],
    [
        'topic' => 'Быстрая сортировка',
        'question' => 'Какой элемент выбирается как опорный в быстрой сортировке?',
        'options' => [
            'Первый элемент',
            'Последний элемент',
            'Средний элемент',
            'Все варианты возможны'
        ],
        'correct' => [3]
    ],
    [
        'topic' => 'Сортировка слиянием',
        'question' => 'Какие преимущества имеет сортировка слиянием?',
        'options' => [
            'Стабильная сортировка',
            'Работает за O(n)',
            'Не требует дополнительной памяти',
            'Все перечисленное'
        ],
        'correct' => [0]
    ],
    [
        'topic' => 'Пирамидальная сортировка',
        'question' => 'На какой структуре данных основана пирамидальная сортировка?',
        'options' => [
            'Стек',
            'Очередь',
            'Двоичная куча',
            'Связный список'
        ],
        'correct' => [2]
    ],
    [
        'topic' => 'Сортировка подсчетом',
        'question' => 'В каких случаях сортировка подсчетом эффективна?',
        'options' => [
            'При больших наборах данных',
            'При небольшом диапазоне значений',
            'При случайном порядке элементов',
            'При обратном порядке элементов'
        ],
        'correct' => [1]
    ],
    [
        'topic' => 'Сортировка выбором',
        'question' => 'Как работает сортировка выбором?',
        'options' => [
            'Находит минимальный элемент и меняет его с первым',
            'Сравнивает соседние элементы',
            'Разделяет массив на части',
            'Использует дополнительный массив'
        ],
        'correct' => [0]
    ],
    [
        'topic' => 'Сортировка Шелла',
        'question' => 'Что является основой сортировки Шелла?',
        'options' => [
            'Сортировка пузырьком',
            'Сортировка вставками',
            'Сортировка выбором',
            'Сортировка слиянием'
        ],
        'correct' => [1]
    ],
    [
        'topic' => 'Сравнение алгоритмов',
        'question' => 'Какие алгоритмы сортировки являются стабильными?',
        'options' => [
            'Быстрая сортировка и сортировка выбором',
            'Сортировка слиянием и сортировка вставками',
            'Пирамидальная сортировка и сортировка Шелла',
            'Только быстрая сортировка'
        ],
        'correct' => [1]
    ]
];

// Initialize quiz if not started
if (!isset($_SESSION['quiz_started'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['user_name'] = $_POST['name'];
        $_SESSION['user_group'] = $_POST['group'];
        $_SESSION['quiz_started'] = true;
        $_SESSION['current_question'] = 0;
        $_SESSION['score'] = 0;
        $_SESSION['answers'] = [];
        // Shuffle questions
        shuffle($questions);
        $_SESSION['questions'] = $questions;
    } else {
        header('Location: index.php');
        exit;
    }
}

// Handle answer submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $current_question = $_SESSION['questions'][$_SESSION['current_question']];
    $user_answer = $_POST['answer'];
    $is_correct = in_array($user_answer, $current_question['correct']);
    
    $_SESSION['answers'][] = [
        'question' => $current_question['question'],
        'user_answer' => $current_question['options'][$user_answer],
        'correct' => $is_correct
    ];
    
    if ($is_correct) {
        $_SESSION['score']++;
    }
    
    $_SESSION['current_question']++;
    
    if ($_SESSION['current_question'] >= count($_SESSION['questions'])) {
        // Quiz completed
        header('Location: results.php');
        exit;
    }
}

$current_question = $_SESSION['questions'][$_SESSION['current_question']];
$progress = ($_SESSION['current_question'] / count($_SESSION['questions'])) * 100;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вопрос <?php echo $_SESSION['current_question'] + 1; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #f0f0f0;
            border-radius: 10px;
            margin: 20px 0;
        }
        .progress {
            width: <?php echo $progress; ?>%;
            height: 100%;
            background-color: #4CAF50;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        .question {
            margin: 20px 0;
        }
        .options {
            margin: 20px 0;
        }
        .option {
            margin: 10px 0;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Вопрос <?php echo $_SESSION['current_question'] + 1; ?> из <?php echo count($_SESSION['questions']); ?></h1>
    
    <div class="progress-bar">
        <div class="progress"></div>
    </div>
    
    <div class="question">
        <h2><?php echo $current_question['question']; ?></h2>
    </div>
    
    <form method="POST">
        <div class="options">
            <?php foreach ($current_question['options'] as $index => $option): ?>
                <div class="option">
                    <input type="radio" name="answer" value="<?php echo $index; ?>" id="option<?php echo $index; ?>" required>
                    <label for="option<?php echo $index; ?>"><?php echo $option; ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit">Ответить</button>
    </form>
</body>
</html> 