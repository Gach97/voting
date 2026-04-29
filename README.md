# Voting System

A simple PHP voting application for student elections.

## Demo Admin Account
- Email: admin@vote.com
- Password: admin123

## Features
- User registration and login
- Admin panel for managing candidates
- One vote per user
- Live results display
- Secure password hashing with bcrypt

## Setup
1. Import `setup.sql` into `voting_db`.
2. Update `config/database.php` if needed.
3. Run the app from your web server.
4. Log in with the demo admin account.

## Notes
- Admin users are redirected to `/admin/index.php`.
- Regular users vote at `/pages/vote.php`.
- Results are visible at `/pages/results.php`.
