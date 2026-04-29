<?php
/**
 * HTML header with navigation
 */

require_once __DIR__ . '/auth.php';

$pageTitle = $pageTitle ?? 'Voting System';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/index.php">🗳️ Voting System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/vote.php">Vote</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/results.php">Results</a>
                    </li>
                    <?php if ($_SESSION['is_admin'] ?? false): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminMenu" role="button" data-bs-toggle="dropdown">
                                Admin Panel
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminMenu">
                                <li><a class="dropdown-item" href="/admin/index.php">Dashboard</a></li>
                                <li><a class="dropdown-item" href="/admin/candidates.php">Candidates</a></li>
                                <li><a class="dropdown-item" href="/admin/results.php">Results</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/admin/reset.php">Reset Votes</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-5">
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['flash']['type']); ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <script>
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 4000);
        </script>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
