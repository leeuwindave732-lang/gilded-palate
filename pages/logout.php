<?php
session_start();

// Example: logout user only
if (isset($_GET['role']) && $_GET['role'] === 'admin') {
    unset($_SESSION['user_id']);
    unset($_SESSION['role']);
    header('Location: ../admin-pages/login.php');
    exit;
} else {
    unset($_SESSION['user_id']);
    unset($_SESSION['role']);
    header('Location: ../pages/login.php');
    exit;
}
