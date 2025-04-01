<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тест по методам сортировки</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
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
    <h1>Тест по методам сортировки</h1>
    <form action="quiz.php" method="POST">
        <div class="form-group">
            <label for="name">ФИО:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="group">Группа:</label>
            <input type="text" id="group" name="group" required>
        </div>
        <button type="submit">Начать тест</button>
    </form>
</body>
</html> 