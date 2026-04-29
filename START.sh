#!/bin/bash

# Voting System - Clean Start Script

echo "🗳️  Voting System - Fresh Start"
echo "================================"
echo ""

# Step 1: Stop and remove old containers
echo "Step 1: Cleaning up old containers..."
docker-compose down -v --remove-orphans 2>/dev/null || true
sleep 2

# Step 2: Build fresh images
echo ""
echo "Step 2: Building Docker images..."
docker-compose build --no-cache

# Step 3: Start services
echo ""
echo "Step 3: Starting services..."
docker-compose up -d

# Step 4: Wait for services to be ready
echo ""
echo "Step 4: Waiting for services to be ready..."
sleep 10

# Step 5: Check status
echo ""
echo "================================"
echo "Container Status:"
docker-compose ps

echo ""
echo "================================"
echo "✅ Services should be starting!"
echo ""
echo "Access points:"
echo "  🌐 Web App:    http://localhost"
echo "  🗄️  phpMyAdmin: http://localhost:8080"
echo ""
echo "Admin credentials:"
echo "  Email: admin@vote.com"
echo "  Password: admin123"
echo ""
echo "Tip: View logs with:"
echo "  docker-compose logs -f"
echo ""
