<?php
/**
 * Authentication and authorization functions
 */

/**
 * Redirect to login if not authenticated
 */
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /pages/login.php');
        exit;
    }
}

/**
 * Redirect to vote page if not admin
 */
function requireAdmin() {
    if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Admin access required.'];
        header('Location: /pages/vote.php');
        exit;
    }
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
