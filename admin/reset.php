<?php
/**
 * Reset all votes (admin only)
 */
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$confirmed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirm = $_POST['confirm'] ?? '';
    
    if ($confirm === 'yes') {
        try {
            // Delete all votes
            $stmt = $pdo->prepare('DELETE FROM votes');
            $stmt->execute();
            
            // Reset has_voted flag for all users
            $stmt = $pdo->prepare('UPDATE users SET has_voted = 0');
            $stmt->execute();
            
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'All votes have been reset successfully.'];
            header('Location: /admin/index.php');
            exit;
        } catch (Exception $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Failed to reset votes.'];
            header('Location: /admin/index.php');
            exit;
        }
    }
}

$pageTitle = 'Reset Votes';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="glass-card p-5">
            <h2 class="mb-4 text-center text-danger">⚠️ Reset All Votes</h2>

            <div class="alert alert-danger mb-4">
                <strong>Warning!</strong> This action will:
                <ul class="mb-0 mt-2">
                    <li>Delete all votes from the database</li>
                    <li>Reset all users' "has_voted" status</li>
                    <li>Allow users to vote again</li>
                </ul>
                <p class="mt-3 mb-0"><strong>This action cannot be undone.</strong></p>
            </div>

            <form method="POST" novalidate>
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmCheck" name="confirm" value="yes" required>
                        <label class="form-check-label" for="confirmCheck">
                            I understand this will permanently delete all votes
                        </label>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-danger btn-lg">Reset All Votes</button>
                    <a href="/admin/index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
