<!DOCTYPE html>
<html>
<head>
    <title>Лабораторная работа №6</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .buttons {
            text-align: center;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Лабораторная работа №6</h1>
        <div class="buttons">
            <a href="generate_pdf_mpdf.php" class="button">Просмотреть PDF</a>
            <a href="generate_pdf_mpdf.php?download=1" class="button">Скачать PDF</a>
        </div>
    </div>
</body>
</html> 