@echo off
set PROJECT_DIR="C:\Users\Love Station\Documents\alom vai\website\ecommerce"

echo Starting Laravel + NPM + Editor + Browser...

:: --- Start PHP Artisan Serve in Git Bash ---
start "" "C:\Program Files\Git\git-bash.exe" -c "cd %PROJECT_DIR% && php artisan serve; echo '--- PHP SERVER CLOSED ---'; read -p 'Press ENTER to exit...'"

:: --- Start NPM Run Dev in new Git Bash window ---
start "" "C:\Program Files\Git\git-bash.exe" -c "cd %PROJECT_DIR% && npm run dev; echo '--- NPM SERVER CLOSED ---'; read -p 'Press ENTER to exit...'"

:: --- Start Git Status in new Git Bash window ---
start "" "C:\Program Files\Git\git-bash.exe" -c "cd %PROJECT_DIR% && git status; echo '--- GIT STATUS CLOSED ---'; read -p 'Press ENTER to exit...'"

:: --- Open Antigravity Editor ---
start "" "C:\Users\Love Station\AppData\Local\Programs\Antigravity\antigravity.exe" %PROJECT_DIR%

:: --- Open Browser with Laravel URLs ---
start "" chrome "http://localhost:8000"
start "" chrome "http://localhost:8000/login"

echo All services started successfully!
exit
