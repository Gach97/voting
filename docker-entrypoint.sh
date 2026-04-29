#!/bin/bash
# Startup script for voting system - kept for reference
# Now handled by docker-compose health checks

echo "🗳️  Voting System Container Starting..."
apache2-foreground
