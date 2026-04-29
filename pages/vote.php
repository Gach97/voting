<?php
/**
 * Voting page - user casts vote
 */
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$candidates = getCandidates();
$alreadyVoted = hasVoted($_SESSION['user_id']);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$alreadyVoted) {
    $candidateId = $_POST['candidate_id'] ?? '';

    if (empty($candidateId)) {
        $errors[] = 'Please select a candidate.';
    } else {
        if (castVote($_SESSION['user_id'], $candidateId)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Your vote has been cast successfully!'];
            header('Location: /pages/results.php');
            exit;
        } else {
            $errors[] = 'Failed to cast vote. Please try again.';
        }
    }
}

$pageTitle = 'Vote';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <?php if ($alreadyVoted): ?>
            <div class="alert alert-info">
                <h5>You have already voted</h5>
                <p>Thank you for participating in the election. <a href="/pages/results.php">View live results</a></p>
            </div>
        <?php else: ?>
            <div class="glass-card p-5">
                <h2 class="mb-5 text-center">Cast Your Vote</h2>

                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-1">• <?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row">
                        <?php if (empty($candidates)): ?>
                            <p class="text-center text-muted">No candidates available.</p>
                        <?php else: ?>
                            <?php foreach ($candidates as $candidate): ?>
                                <div class="col-md-6 mb-4">
                                    <label class="candidate-card">
                                        <div class="card h-100 cursor-pointer">
                                            <div class="card-body">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="candidate_id" value="<?php echo htmlspecialchars($candidate['id']); ?>" id="candidate<?php echo $candidate['id']; ?>">
                                                </div>
                                                <h5 class="card-title"><?php echo htmlspecialchars($candidate['name']); ?></h5>
                                                <p class="card-text text-muted"><?php echo htmlspecialchars($candidate['position']); ?></p>
                                                <?php if ($candidate['bio']): ?>
                                                    <small class="text-secondary"><?php echo htmlspecialchars($candidate['bio']); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($candidates)): ?>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Cast Vote</button>
                    <?php endif; ?>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
