<?php
/**
 * Core voting system functions
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Get all candidates
 */
function getCandidates() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT id, name, position, bio FROM candidates ORDER BY id ASC');
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Check if user has already voted
 */
function hasVoted($userId) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT has_voted FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result ? (bool)$result['has_voted'] : false;
}

/**
 * Cast a vote (with transaction)
 */
function castVote($userId, $candidateId) {
    global $pdo;
    try {
        $pdo->beginTransaction();
        
        // Insert vote
        $stmt = $pdo->prepare('INSERT INTO votes (voter_id, candidate_id) VALUES (?, ?)');
        $stmt->execute([$userId, $candidateId]);
        
        // Update user has_voted flag
        $stmt = $pdo->prepare('UPDATE users SET has_voted = 1 WHERE id = ?');
        $stmt->execute([$userId]);
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

/**
 * Get voting results with vote counts
 */
function getResults() {
    global $pdo;
    $stmt = $pdo->prepare('
        SELECT 
            c.id,
            c.name,
            c.position,
            COUNT(v.id) AS vote_count
        FROM candidates c
        LEFT JOIN votes v ON c.id = v.candidate_id
        GROUP BY c.id
        ORDER BY vote_count DESC
    ');
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get total vote count
 */
function getTotalVotes() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM votes');
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}
