<?php
/**
 * Admin dashboard
 */
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$candidates = getCandidates();
$results = getResults();
$totalVotes = getTotalVotes();
$totalUsers = $pdo->query('SELECT COUNT(*) as count FROM users')->fetch()['count'];
$votedUsers = $pdo->query('SELECT COUNT(*) as count FROM users WHERE has_voted = 1')->fetch()['count'];

$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row mb-5">
    <div class="col-md-3 mb-4">
        <div class="glass-card p-4 text-center">
            <h3 class="text-primary"><?php echo $totalUsers; ?></h3>
            <p class="text-muted">Registered Users</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="glass-card p-4 text-center">
            <h3 class="text-success"><?php echo $votedUsers; ?></h3>
            <p class="text-muted">Votes Cast</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="glass-card p-4 text-center">
            <h3 class="text-info"><?php echo count($candidates); ?></h3>
            <p class="text-muted">Candidates</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="glass-card p-4 text-center">
            <h3 class="text-warning">
                <?php echo $totalUsers > 0 ? round(($votedUsers / $totalUsers) * 100) : 0; ?>%
            </h3>
            <p class="text-muted">Turnout</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="glass-card p-5">
            <h3 class="mb-4">Vote Distribution</h3>
            <?php if ($totalVotes > 0): ?>
                <div class="table-responsive">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Position</th>
                                <th>Votes</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $r): ?>
                                <?php $percentage = round(($r['vote_count'] / $totalVotes) * 100); ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($r['name']); ?></td>
                                    <td><?php echo htmlspecialchars($r['position']); ?></td>
                                    <td><?php echo htmlspecialchars($r['vote_count']); ?></td>
                                    <td><?php echo $percentage; ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No votes yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-card p-5">
            <h3 class="mb-4">Quick Actions</h3>
            <ul class="list-unstyled">
                <li class="mb-3">
                    <a href="/admin/candidates.php" class="btn btn-outline-primary w-100">Manage Candidates</a>
                </li>
                <li class="mb-3">
                    <a href="/admin/results.php" class="btn btn-outline-info w-100">View Full Results</a>
                </li>
                <li class="mb-3">
                    <a href="/admin/reset.php" class="btn btn-outline-danger w-100">Reset Votes</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
