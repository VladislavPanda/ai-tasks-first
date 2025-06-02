<?php
// Запрос к API
$apiUrl = "https://fakestoreapi.com/products";
$response = file_get_contents($apiUrl);

// Проверка на успешность запроса
if ($response === false) {
    die("Ошибка при получении данных от API.");
}

// Преобразование JSON-ответа в PHP-массив
$products = json_decode($response, true);

// Проверка, что декодирование прошло успешно
if (!is_array($products)) {
    die("Ошибка: получен некорректный JSON.");
}

$defectiveProducts = [];

foreach ($products as $product) {
    $issues = [];

    // Проверка title
    if (!isset($product['title']) || trim($product['title']) === "") {
        $issues[] = "Пустое или отсутствующее поле title";
    }

    // Проверка price
    if (!isset($product['price']) || !is_numeric($product['price']) || $product['price'] < 0) {
        $issues[] = "Отрицательная или некорректная цена";
    }

    // Проверка rating.rate
    if (
        !isset($product['rating']['rate']) ||
        !is_numeric($product['rating']['rate']) ||
        $product['rating']['rate'] > 5
    ) {
        $issues[] = "Некорректный рейтинг (rating.rate > 5 или отсутствует)";
    }

    // Если есть ошибки — добавляем в массив с дефектами
    if (!empty($issues)) {
        $defectiveProducts[] = [
            'id' => $product['id'],
            'title' => $product['title'] ?? '[Без названия]',
            'issues' => $issues
        ];
    }
}

// Вывод результатов
echo "<h2>Найдено " . count($defectiveProducts) . " дефектных продуктов:</h2>";

foreach ($defectiveProducts as $item) {
    echo "<strong>ID:</strong> {$item['id']}<br>";
    echo "<strong>Название:</strong> " . htmlspecialchars($item['title']) . "<br>";
    echo "<strong>Ошибки:</strong><ul>";
    foreach ($item['issues'] as $issue) {
        echo "<li>" . htmlspecialchars($issue) . "</li>";
    }
    echo "</ul><hr>";
}

