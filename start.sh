#!/bin/bash

# Quick startup script for the voting system
# This script helps with common operations

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
print_header() {
    echo -e "${BLUE}▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬${NC}"
    echo -e "${BLUE}🗳️  Voting System - Setup${NC}"
    echo -e "${BLUE}▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬${NC}"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Check if Docker is installed
check_docker() {
    if ! command -v docker &> /dev/null; then
        print_error "Docker is not installed"
        return 1
    fi
    print_success "Docker installed"
    return 0
}

# Check if Docker Compose is installed
check_docker_compose() {
    if ! command -v docker-compose &> /dev/null; then
        print_error "Docker Compose is not installed"
        return 1
    fi
    print_success "Docker Compose installed"
    return 0
}

# Start Docker services
start_services() {
    print_info "Starting Docker services..."
    docker-compose up -d
    print_success "Services started"
    sleep 3
}

# Stop Docker services
stop_services() {
    print_info "Stopping Docker services..."
    docker-compose down
    print_success "Services stopped"
}

# Show status
show_status() {
    echo ""
    print_info "Service Status:"
    docker-compose ps
    echo ""
}

# Show logs
show_logs() {
    print_info "Showing logs (press Ctrl+C to stop)..."
    docker-compose logs -f
}

# Show access points
show_access() {
    echo ""
    echo -e "${BLUE}▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬${NC}"
    echo -e "${GREEN}Access Points:${NC}"
    echo -e "${BLUE}▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬${NC}"
    echo ""
    echo "Web Application:"
    echo "  🌐 http://localhost"
    echo ""
    echo "Admin Panel:"
    echo "  Email: admin@vote.com"
    echo "  Password: admin123"
    echo ""
    echo "phpMyAdmin (Database Management):"
    echo "  🗄️  http://localhost:8080"
    echo "  User: root"
    echo "  Password: root"
    echo ""
    echo "MySQL:"
    echo "  Host: localhost"
    echo "  Port: 3306"
    echo "  User: root"
    echo "  Password: root"
    echo ""
}

# Check database connection
check_db() {
    print_info "Checking database connection..."
    docker-compose exec -T db mysql -u root -proot -e "SELECT 1" > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        print_success "Database connection OK"
        return 0
    else
        print_error "Database connection failed"
        return 1
    fi
}

# Run tests
run_tests() {
    print_info "Running tests..."
    
    # Check if tables exist
    print_info "Checking database tables..."
    
    # Check users table
    docker-compose exec -T db mysql -u root -proot voting_db -e "SELECT COUNT(*) FROM users" > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        print_success "users table exists"
    else
        print_error "users table not found"
    fi
    
    # Check candidates table
    docker-compose exec -T db mysql -u root -proot voting_db -e "SELECT COUNT(*) FROM candidates" > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        print_success "candidates table exists"
    else
        print_error "candidates table not found"
    fi
    
    # Check votes table
    docker-compose exec -T db mysql -u root -proot voting_db -e "SELECT COUNT(*) FROM votes" > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        print_success "votes table exists"
    else
        print_error "votes table not found"
    fi
    
    # Check admin user
    ADMIN_COUNT=$(docker-compose exec -T db mysql -u root -proot voting_db -e "SELECT COUNT(*) FROM users WHERE email='admin@vote.com'" 2>/dev/null | tail -1)
    if [ "$ADMIN_COUNT" -ge 1 ]; then
        print_success "Admin user exists"
    else
        print_error "Admin user not found"
    fi
}

# Show menu
show_menu() {
    echo ""
    echo -e "${BLUE}Options:${NC}"
    echo "  1) Start services"
    echo "  2) Stop services"
    echo "  3) Show status"
    echo "  4) Show logs"
    echo "  5) Check database"
    echo "  6) Run tests"
    echo "  7) Show access points"
    echo "  8) Rebuild containers"
    echo "  0) Exit"
    echo ""
    read -p "Enter option: " option
}

# Main menu loop
main_menu() {
    while true; do
        show_menu
        
        case $option in
            1) start_services; show_access ;;
            2) stop_services ;;
            3) show_status ;;
            4) show_logs ;;
            5) check_db ;;
            6) run_tests ;;
            7) show_access ;;
            8) print_info "Rebuilding containers..."; docker-compose up --build -d; show_access ;;
            0) print_info "Exiting..."; exit 0 ;;
            *) print_error "Invalid option" ;;
        esac
    done
}

# Main function
main() {
    print_header
    echo ""
    
    # Check prerequisites
    print_info "Checking prerequisites..."
    
    if ! check_docker; then
        echo ""
        print_error "Please install Docker first: https://docker.com"
        exit 1
    fi
    
    if ! check_docker_compose; then
        echo ""
        print_error "Please install Docker Compose first"
        exit 1
    fi
    
    echo ""
    
    # Show menu or auto-start if service is down
    if docker-compose ps | grep -q "web"; then
        print_success "Services already running"
        show_access
        main_menu
    else
        print_info "Services not running. Starting now..."
        start_services
        
        # Wait for services to be ready
        print_info "Waiting for services to be ready..."
        sleep 10
        
        # Check database
        check_db
        run_tests
        
        show_access
        main_menu
    fi
}

# Run main function
main
