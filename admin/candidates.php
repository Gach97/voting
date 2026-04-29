<?php
/**
 * Manage candidates
 */
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$errors = [];
$success = false;

// Add candidate
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'] ?? '';
    $position = $_POST['position'] ?? '';
    $bio = $_POST['bio'] ?? '';

    if (empty($name)) $errors[] = 'Candidate name is required.';
    if (empty($position)) $errors[] = 'Position is required.';

    if (!$errors) {
        try {
            $stmt = $pdo->prepare('INSERT INTO candidates (name, position, bio) VALUES (?, ?, ?)');
            $stmt->execute([$name, $position, $bio]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Candidate added successfully.'];
            header('Location: /admin/candidates.php');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Failed to add candidate.';
        }
    }
}

// Delete candidate
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $candidateId = $_POST['candidate_id'] ?? '';

    if (!empty($candidateId)) {
        try {
            // Delete votes first
            $stmt = $pdo->prepare('DELETE FROM votes WHERE candidate_id = ?');
            $stmt->execute([$candidateId]);
            
            // Delete candidate
            $stmt = $pdo->prepare('DELETE FROM candidates WHERE id = ?');
            $stmt->execute([$candidateId]);
            
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Candidate deleted.'];
            header('Location: /admin/candidates.php');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Failed to delete candidate.';
        }
    }
}

$candidates = getCandidates();

$pageTitle = 'Manage Candidates';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row">
    <div class="col-lg-8">
        <div class="glass-card p-5 mb-5">
            <h2 class="mb-4">Current Candidates</h2>

            <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-1">• <?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($candidates)): ?>
                <p class="text-muted">No candidates yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidates as $candidate): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($candidate['name']); ?></td>
                                    <td><?php echo htmlspecialchars($candidate['position']); ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="candidate_id" value="<?php echo $candidate['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this candidate?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-card p-5">
            <h3 class="mb-4">Add New Candidate</h3>

            <form method="POST" novalidate>
                <input type="hidden" name="action" value="add">

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="position" class="form-label">Position</label>
                    <input type="text" class="form-control" id="position" name="position" placeholder="e.g., President" required>
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">Bio (Optional)</label>
                    <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Add Candidate</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
