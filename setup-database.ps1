# Database Setup Script for Laravel Login System
# This script helps you set up the database for the application

Write-Host "==================================" -ForegroundColor Green
Write-Host "Laravel Login System - Database Setup" -ForegroundColor Green
Write-Host "==================================" -ForegroundColor Green
Write-Host ""

# Check if .env file exists
if (-not (Test-Path ".env")) {
    Write-Host "Error: .env file not found!" -ForegroundColor Red
    Write-Host "Please copy .env.example to .env first" -ForegroundColor Yellow
    exit 1
}

Write-Host "Choose your database option:" -ForegroundColor Cyan
Write-Host "1. SQLite (Quick setup for development)" -ForegroundColor White
Write-Host "2. MySQL (Recommended for production)" -ForegroundColor White
Write-Host ""

$choice = Read-Host "Enter your choice (1 or 2)"

if ($choice -eq "1") {
    Write-Host ""
    Write-Host "Setting up SQLite database..." -ForegroundColor Yellow
    
    # Create database file
    $dbPath = "database\database.sqlite"
    if (-not (Test-Path $dbPath)) {
        New-Item -Path $dbPath -ItemType File -Force | Out-Null
        Write-Host "✓ Created database file: $dbPath" -ForegroundColor Green
    } else {
        Write-Host "✓ Database file already exists" -ForegroundColor Green
    }
    
    # Update .env file
    $envContent = Get-Content .env
    $envContent = $envContent -replace "DB_CONNECTION=.*", "DB_CONNECTION=sqlite"
    $fullPath = (Get-Location).Path + "\database\database.sqlite"
    $envContent = $envContent -replace "DB_DATABASE=.*", "DB_DATABASE=$fullPath"
    $envContent | Set-Content .env
    
    Write-Host "✓ Updated .env file" -ForegroundColor Green
    
} elseif ($choice -eq "2") {
    Write-Host ""
    Write-Host "MySQL Database Setup" -ForegroundColor Yellow
    Write-Host "Please enter your MySQL credentials:" -ForegroundColor Cyan
    
    $dbHost = Read-Host "Database Host (default: 127.0.0.1)"
    if ([string]::IsNullOrWhiteSpace($dbHost)) { $dbHost = "127.0.0.1" }
    
    $dbPort = Read-Host "Database Port (default: 3306)"
    if ([string]::IsNullOrWhiteSpace($dbPort)) { $dbPort = "3306" }
    
    $dbName = Read-Host "Database Name"
    $dbUser = Read-Host "Database Username"
    $dbPass = Read-Host "Database Password" -AsSecureString
    $dbPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPass))
    
    # Update .env file
    $envContent = Get-Content .env
    $envContent = $envContent -replace "DB_CONNECTION=.*", "DB_CONNECTION=mysql"
    $envContent = $envContent -replace "DB_HOST=.*", "DB_HOST=$dbHost"
    $envContent = $envContent -replace "DB_PORT=.*", "DB_PORT=$dbPort"
    $envContent = $envContent -replace "DB_DATABASE=.*", "DB_DATABASE=$dbName"
    $envContent = $envContent -replace "DB_USERNAME=.*", "DB_USERNAME=$dbUser"
    $envContent = $envContent -replace "DB_PASSWORD=.*", "DB_PASSWORD=$dbPassPlain"
    $envContent | Set-Content .env
    
    Write-Host "✓ Updated .env file" -ForegroundColor Green
    Write-Host ""
    Write-Host "Note: Make sure the database '$dbName' exists in MySQL" -ForegroundColor Yellow
    Write-Host "You can create it with: CREATE DATABASE $dbName;" -ForegroundColor Yellow
    
} else {
    Write-Host "Invalid choice!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "Running migrations and seeding database..." -ForegroundColor Yellow

# Clear config cache
php artisan config:clear

# Run migrations with seed
php artisan migrate:fresh --seed

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "==================================" -ForegroundColor Green
    Write-Host "✓ Database setup completed successfully!" -ForegroundColor Green
    Write-Host "==================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Default Admin Credentials:" -ForegroundColor Cyan
    Write-Host "Email: admin@iherb.com" -ForegroundColor White
    Write-Host "Password: admin123" -ForegroundColor White
    Write-Host "Mobile: 01700000000" -ForegroundColor White
    Write-Host ""
    Write-Host "To start the application, run:" -ForegroundColor Yellow
    Write-Host "php artisan serve" -ForegroundColor White
    Write-Host ""
    Write-Host "Then visit: http://localhost:8000/login" -ForegroundColor Cyan
} else {
    Write-Host ""
    Write-Host "Error: Migration failed!" -ForegroundColor Red
    Write-Host "Please check the error messages above" -ForegroundColor Yellow
}
