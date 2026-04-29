<?php
/**
 * Results page - public results display
 */
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$results = getResults();
$totalVotes = getTotalVotes();

$pageTitle = 'Results';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-card p-5">
            <h2 class="mb-5 text-center">Live Voting Results</h2>

            <?php if (empty($results)): ?>
                <p class="text-center text-muted">No results yet.</p>
            <?php else: ?>
                <p class="text-center text-muted mb-5">Total votes cast: <strong><?php echo $totalVotes; ?></strong></p>

                <?php foreach ($results as $index => $candidate): ?>
                    <?php 
                    $percentage = $totalVotes > 0 ? round(($candidate['vote_count'] / $totalVotes) * 100) : 0;
                    $isLeading = $index === 0 && $totalVotes > 0;
                    ?>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <strong><?php echo htmlspecialchars($candidate['name']); ?></strong>
                            <span><?php echo htmlspecialchars($candidate['position']); ?></span>
                        </div>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar <?php echo $isLeading ? 'bg-success' : 'bg-primary'; ?>" 
                                 style="width: <?php echo $percentage; ?>%">
                                <strong><?php echo $percentage; ?>% (<?php echo htmlspecialchars($candidate['vote_count']); ?> votes)</strong>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if ($totalVotes > 0): ?>
                    <div class="alert alert-success mt-5">
                        <h5>🏆 Leading Candidate</h5>
                        <p class="mb-0"><?php echo htmlspecialchars($results[0]['name']); ?> with <?php echo htmlspecialchars($results[0]['vote_count']); ?> votes</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (isLoggedIn()): ?>
                <div class="text-center mt-5">
                    <a href="/pages/vote.php" class="btn btn-primary">Back to Voting</a>
                </div>
            <?php else: ?>
                <div class="text-center mt-5">
                    <a href="/pages/login.php" class="btn btn-primary">Login to Vote</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
