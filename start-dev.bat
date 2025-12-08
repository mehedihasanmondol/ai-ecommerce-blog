@echo off
set PROJECT_DIR=%~dp0

echo Starting Laravel + NPM + Editor + Browser...

:: --- Start PHP Artisan Serve in Git Bash ---
start "" /D "%PROJECT_DIR%" "C:\Program Files\Git\git-bash.exe" -c "php artisan serve; echo '--- PHP SERVER CLOSED ---'; exec bash"

:: --- Start NPM Run Dev in new Git Bash window ---
start "" /D "%PROJECT_DIR%" "C:\Program Files\Git\git-bash.exe" -c "npm run dev; echo '--- NPM SERVER CLOSED ---'; exec bash"

:: --- Start Git Status in new Git Bash window ---
start "" /D "%PROJECT_DIR%" "C:\Program Files\Git\git-bash.exe" -c "git status; echo '--- GIT STATUS CLOSED ---'; exec bash"

:: --- Open Antigravity Editor ---
start "" "%USERPROFILE%\AppData\Local\Programs\Antigravity\antigravity.exe" "%PROJECT_DIR%"

:: --- Open Browser with Laravel URLs ---
start "" chrome "http://localhost:8000"
start "" chrome "http://localhost:8000/login"

echo All services started successfully!
exit
