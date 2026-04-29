<?php
/**
 * Admin results view
 */
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$results = getResults();
$totalVotes = getTotalVotes();

$pageTitle = 'Results';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="glass-card p-5">
    <h2 class="mb-5 text-center">Detailed Results</h2>

    <p class="text-center text-muted mb-5">Total votes cast: <strong><?php echo $totalVotes; ?></strong></p>

    <?php if (empty($results)): ?>
        <p class="text-center text-muted">No results yet.</p>
    <?php else: ?>
        <div class="table-responsive mb-5">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Candidate</th>
                        <th>Position</th>
                        <th>Votes</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $index => $r): ?>
                        <?php $percentage = $totalVotes > 0 ? round(($r['vote_count'] / $totalVotes) * 100) : 0; ?>
                        <tr <?php echo $index === 0 ? 'class="table-success"' : ''; ?>>
                            <td><strong><?php echo $index + 1; ?></strong></td>
                            <td><?php echo htmlspecialchars($r['name']); ?></td>
                            <td><?php echo htmlspecialchars($r['position']); ?></td>
                            <td><?php echo htmlspecialchars($r['vote_count']); ?></td>
                            <td><?php echo $percentage; ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="row">
            <?php foreach ($results as $index => $candidate): ?>
                <?php $percentage = $totalVotes > 0 ? round(($candidate['vote_count'] / $totalVotes) * 100) : 0; ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 <?php echo $index === 0 ? 'border-success' : ''; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($candidate['name']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($candidate['position']); ?></p>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar <?php echo $index === 0 ? 'bg-success' : 'bg-primary'; ?>" style="width: <?php echo $percentage; ?>%">
                                    <strong><?php echo $percentage; ?>%</strong>
                                </div>
                            </div>
                            <p class="mt-3 mb-0"><strong><?php echo htmlspecialchars($candidate['vote_count']); ?></strong> votes</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="text-center mt-5">
        <a href="/admin/index.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
