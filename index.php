<?php
/**
 * Application entry point
 */
session_start();

require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    if ($_SESSION['is_admin'] ?? false) {
        header('Location: /admin/index.php');
    } else {
        header('Location: /pages/vote.php');
    }
} else {
    header('Location: /pages/login.php');
}
exit;
