<?php
// Создаем новое изображение
$width = 500;
$height = 600;
$image = imagecreatetruecolor($width, $height);

// Включаем сглаживание
imageantialias($image, true);

// Устанавливаем цвета
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
$orange = imagecolorallocate($image, 255, 140, 0);
$light_orange = imagecolorallocate($image, 255, 180, 100);
$gray = imagecolorallocate($image, 180, 180, 180);
$dark_gray = imagecolorallocate($image, 100, 100, 100);

// Заполняем фон белым цветом
imagefill($image, 0, 0, $white);

// Рисуем тень
imagefilledellipse($image, 260, 320, 200, 260, $gray);


// Добавляем тени для крыльев
imagefilledarc($image, 175, 325, 70, 180, 0, 360, $dark_gray, IMG_ARC_PIE);
imagefilledarc($image, 350, 325, 70, 180, 0, 360, $dark_gray, IMG_ARC_PIE);
// Рисуем тело пингвина
imagefilledellipse($image, 250, 300, 200, 280, $black);

// Рисуем живот пингвина
imagefilledellipse($image, 250, 320, 140, 200, $white);



// Рисуем голову
imagefilledellipse($image, 250, 180, 130, 130, $black);

// Рисуем глаза
imagefilledellipse($image, 220, 160, 25, 25, $white); // левый глаз
imagefilledellipse($image, 280, 160, 25, 25, $white); // правый глаз

// Зрачки
imagefilledellipse($image, 220, 160, 12, 12, $black); // левый зрачок
imagefilledellipse($image, 280, 160, 12, 12, $black); // правый зрачок

// Блики в глазах
imagefilledellipse($image, 217, 157, 6, 6, $white);
imagefilledellipse($image, 277, 157, 6, 6, $white);

// Рисуем клюв
$beak_points = array(
    230, 180,  // Верхняя точка
    270, 180,  // Верхняя правая
    250, 210   // Нижняя точка
);
imagefilledpolygon($image, $beak_points, 3, $orange);

// Добавляем блик на клюве
$beak_highlight = array(
    235, 182,
    260, 182,
    248, 200
);

imagefilledpolygon($image, $beak_highlight, 3, $light_orange);

// Рисуем лапки с тенью
imagefilledellipse($image, 210, 450, 70, 35, $dark_gray); // тень левой лапки
imagefilledellipse($image, 310, 450, 70, 35, $dark_gray); // тень правой лапки
imagefilledellipse($image, 200, 445, 70, 35, $orange); // левая лапка
imagefilledellipse($image, 300, 445, 70, 35, $orange); // правая лапка

// Рисуем крылья
imagefilledarc($image, 160, 310, 80, 200, 0, 360, $black, IMG_ARC_PIE);
imagefilledarc($image, 340, 310, 80, 200, 0, 360, $black, IMG_ARC_PIE);






// Сглаживаем края
imagesetthickness($image, 2);
imagearc($image, 250, 180, 130, 130, 0, 360, $black);

// Устанавливаем заголовок для вывода изображения
header('Content-Type: image/png');

// Выводим изображение
imagepng($image);

// Освобождаем память
imagedestroy($image);
?> 