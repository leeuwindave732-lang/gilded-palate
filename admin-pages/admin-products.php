<?php
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([]);
    exit;
}

$res = $conn->query("SELECT * FROM products ORDER BY id ASC");
$products = [];
while ($row = $res->fetch_assoc()) {
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
