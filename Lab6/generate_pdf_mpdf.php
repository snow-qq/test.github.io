<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;

$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_header' => 5,
    'margin_footer' => 5,
]);

// Set document information
$mpdf->SetTitle('Лабораторная работа №6');
$mpdf->SetAuthor('Igor');
$mpdf->SetCreator('Igor');

// Add a page
$mpdf->AddPage();

// Header
$mpdf->SetFont('DejaVuSans', 'B', 15);
$mpdf->Cell(0, 10, 'Лабораторная работа №6', 0, 0, 'C');
$mpdf->Ln(20);

// Title and Author
$mpdf->SetFont('DejaVuSans', 'B', 16);
$mpdf->Cell(0, 10, 'Тема: Создание PDF документа с помощью PHP', 0, 1, 'C');
$mpdf->SetFont('DejaVuSans', 'I', 14);
$mpdf->Cell(0, 10, 'Автор: Игорь', 0, 1, 'C');
$mpdf->Ln(10);

// Abstract
$mpdf->SetFont('DejaVuSans', 'B', 14);
$mpdf->Cell(0, 10, 'Аннотация', 0, 1, 'L');
$mpdf->SetFont('DejaVuSans', '', 12);
$mpdf->MultiCell(0, 10, 'В данной лабораторной работе рассматривается создание PDF-документа с использованием PHP и библиотеки mPDF. Документ содержит различные элементы форматирования текста, включая различные межсимвольные и межстрочные расстояния, а также вставленные изображения.', 0, 'J');
$mpdf->Ln(10);

// First paragraph with normal spacing
$mpdf->SetFont('DejaVuSans', '', 12);
$mpdf->MultiCell(0, 10, 'В современном мире создание PDF-документов является важной задачей для многих веб-приложений. PHP предоставляет различные библиотеки для работы с PDF, одной из которых является mPDF. Эта библиотека позволяет создавать PDF-документы с нуля, добавляя текст, изображения и различные элементы форматирования.', 0, 'J');
$mpdf->Ln(10);

// Second paragraph with increased spacing
$mpdf->SetFont('DejaVuSans', '', 12);
$mpdf->SetLineWidth(0.1);
$mpdf->SetTextColor(0, 0, 0);
$mpdf->SetFillColor(255, 255, 255);
$mpdf->MultiCell(0, 15, 'При создании PDF-документов важно учитывать различные аспекты форматирования, такие как межсимвольные и межстрочные расстояния. Это позволяет сделать документ более читаемым и профессиональным. В данном примере мы демонстрируем различные варианты форматирования текста.', 0, 'J');

// Add a new page for rotated text
$mpdf->AddPage();

// Third paragraph with rotation
$mpdf->SetFont('DejaVuSans', 'B', 14);
$mpdf->Cell(0, 10, 'Пример поворота текста:', 0, 1, 'L');
$mpdf->Ln(60);

// Создаем отдельную область для повернутого текста
$mpdf->SetFont('DejaVuSans', '', 14);
$mpdf->SetXY(35, 110);
$mpdf->Rotate(45, 35, 110);
$mpdf->Text(35, 110, 'Текст повернут на 45 градусов');
$mpdf->Rotate(0);

// Add a new page for alignment examples
$mpdf->AddPage();

// Alignment examples section
$mpdf->SetFont('DejaVuSans', 'B', 14);
$mpdf->Cell(0, 10, 'Примеры выравнивания текста:', 0, 1, 'L');
$mpdf->Ln(20);

// Left alignment
$mpdf->SetFont('DejaVuSans', 'B', 12);
$mpdf->Cell(0, 10, 'Выравнивание по левому краю:', 0, 1, 'L');
$mpdf->SetFont('DejaVuSans', '', 12);
$mpdf->MultiCell(0, 10, 'Этот текст выровнен по левому краю. Это стандартный способ выравнивания текста, который часто используется для основного контента документа.', 0, 'L');
$mpdf->Ln(10);

// Right alignment
$mpdf->SetFont('DejaVuSans', 'B', 12);
$mpdf->Cell(0, 10, 'Выравнивание по правому краю:', 0, 1, 'L');
$mpdf->SetFont('DejaVuSans', '', 12);
$mpdf->MultiCell(0, 10, 'Этот текст выровнен по правому краю. Такое выравнивание часто используется для подписей, дат и других элементов, которые должны быть привязаны к правому краю страницы.', 0, 'R');
$mpdf->Ln(10);

// Center alignment
$mpdf->SetFont('DejaVuSans', 'B', 12);
$mpdf->Cell(0, 10, 'Выравнивание по центру:', 0, 1, 'L');
$mpdf->SetFont('DejaVuSans', '', 12);
$mpdf->MultiCell(0, 10, 'Этот текст выровнен по центру. Центрирование часто используется для заголовков, цитат и других элементов, которые должны быть визуально выделены на странице.', 0, 'C');
$mpdf->Ln(10);

// Justified alignment
$mpdf->SetFont('DejaVuSans', 'B', 12);
$mpdf->Cell(0, 10, 'Выравнивание по ширине:', 0, 1, 'L');
$mpdf->SetFont('DejaVuSans', '', 12);
$mpdf->MultiCell(0, 10, 'Этот текст выровнен по ширине. При таком выравнивании текст равномерно распределяется между левым и правым полями, создавая ровные края с обеих сторон. Это часто используется в газетах и книгах для создания аккуратного вида страницы.', 0, 'J');

// Add a new page for images
$mpdf->AddPage();

// Add images section
$mpdf->SetFont('DejaVuSans', 'B', 14);
$mpdf->Cell(0, 10, 'Примеры изображений:', 0, 1, 'L');
$mpdf->Ln(20);

// Add images
try {
    // Add images with existence check
    if (file_exists('images/photo.jpg')) {
        $mpdf->Image('images/photo.jpg', 10, 50, 50);
    }
    if (file_exists('images/image1.jpg')) {
        $mpdf->Image('images/image1.jpg', 70, 50, 50);
    }
    if (file_exists('images/image2.jpg')) {
        $mpdf->Image('images/image2.jpg', 130, 50, 50);
    }
} catch (Exception $e) {
    // If there is an error adding the image, skip it
    $mpdf->SetFont('DejaVuSans', 'I', 10);
    $mpdf->Text(10, 50, 'Изображение недоступно');
}

// Check if we need to download the file or show preview
if (isset($_GET['download'])) {
    $mpdf->Output('lab6.pdf', 'D');
} else {
    $mpdf->Output('lab6.pdf', 'I');
}
?> 