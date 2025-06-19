<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
error_reporting(0);
ini_set('display_errors', 0);
include "connection.php";

// Получаем входные параметры
$input = $_GET;
if (!$input || !is_array($input)) {
    echo json_encode(["error" => "Invalid input"]);
    exit();
}

// Отделяем dontKnowValue от трейтов
$dontKnowValue = isset($input['dontKnowValue']) ? intval($input['dontKnowValue']) : 0;
unset($input['dontKnowValue']);
$traitScores = $input;

// Функция для построения WHERE-части
function buildWhere(array $map) {
    if (empty($map)) {
        return "1"; // без фильтров — все записи
    }
    $parts = [];
    foreach ($map as $trait => $value) {
        $parts[] = "`$trait` = " . intval($value);
    }
    return implode(" AND ", $parts);
}

// Функция для подсчета единичек по неизвестным трейтам
function countUnknownTraits(array $heroes, array $knownTraits) {
    $traitCounts = [];
    foreach ($heroes as $hero) {
        foreach ($hero as $col => $val) {
            if ($col === 'id') continue; // Игнорируем id
            if (!array_key_exists($col, $knownTraits) && $val == 1) {
                if (!isset($traitCounts[$col])) $traitCounts[$col] = 0;
                $traitCounts[$col]++;
            }
        }
    }
    return array_filter($traitCounts, fn($c) => $c > 0);
}

// Если передан один или ни одного трейта
if (count($traitScores) <= 1) {
    $whereSql = buildWhere($traitScores);
    $stmt = $conn->prepare("SELECT * FROM characters WHERE $whereSql");
    $stmt->execute();
    $heroes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$heroes) {
        echo json_encode(["error" => "Мы не знаем героев которые подходят под описание..."]);
        exit();
    }
    if (count($heroes) === 1) {
        echo json_encode(["id" => $heroes[0]['id']]);
        exit();
    }

    $traitCounts = countUnknownTraits($heroes, $traitScores);

    if (empty($traitCounts)) {
        echo json_encode(["error" => "No traits left to analyze"]);
        exit();
    }

    asort($traitCounts);
    $traitKeys = array_keys($traitCounts);

    if (count($traitKeys) <= $dontKnowValue) {
        echo json_encode(["error" => "No traits left to analyze after skipping"]);
        exit();
    }

    $traitKeys = array_slice($traitKeys, $dontKnowValue);

    $nextMinCount = $traitCounts[$traitKeys[0]];
    $candidates = array_filter($traitKeys, fn($key) => $traitCounts[$key] === $nextMinCount);
    $randomTrait = $candidates[array_rand($candidates)];

    echo json_encode(["trait" => $randomTrait, "ratio" => 0.50]);
    exit();
}

// Более одного трейта
$keys = array_keys($traitScores);
$lastTrait = end($keys);

// Собираем карту предыдущих трейтов
$prevTraitScores = $traitScores;
if ($lastTrait !== false) {
    unset($prevTraitScores[$lastTrait]);
}

// Подсчитываем количество героев до добавления последнего трейта
$prevWhere = buildWhere($prevTraitScores);
$stmtPrev = $conn->prepare("SELECT COUNT(*) FROM characters WHERE $prevWhere");
$stmtPrev->execute();
$previousCount = (int) $stmtPrev->fetchColumn();

// Подсчитываем текущее множество героев
$currentWhere = buildWhere($traitScores);
$stmt = $conn->prepare("SELECT * FROM characters WHERE $currentWhere");
$stmt->execute();
$heroes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$currentCount = count($heroes);

if ($currentCount === 0) {
    echo json_encode(["error" => "Мы не знаем героев которые подходят под описание..."]);
    exit();
}

if ($currentCount === 1) {
    $hero = $heroes[0];
    echo json_encode(["id" => $hero['id']]);
    exit();
}

// Подсчитываем единички по неизвестным трейтам
$traitCounts = countUnknownTraits($heroes, $traitScores);

if (empty($traitCounts)) {
    echo json_encode(["error" => "No traits left to analyze"]);
    exit();
}

asort($traitCounts);
$traitKeys = array_keys($traitCounts);

if (count($traitKeys) <= $dontKnowValue) {
    echo json_encode(["error" => "No traits left to analyze after skipping"]);
    exit();
}

$traitKeys = array_slice($traitKeys, $dontKnowValue);

$nextMinCount = $traitCounts[$traitKeys[0]];
$candidates = array_filter($traitKeys, fn($key) => $traitCounts[$key] === $nextMinCount);
$randomTrait = $candidates[array_rand($candidates)];

// Считаем редукцию
$reduction = $previousCount - $currentCount;
$ratio = $previousCount > 0 ? $reduction / $previousCount : 0;

echo json_encode([
    "trait" => $randomTrait,
    "ratio" => $ratio
]);
?>