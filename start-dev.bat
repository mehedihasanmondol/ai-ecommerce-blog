@echo off
set "PROJECT_DIR=%~dp0"
set "PROJECT_DIR=%PROJECT_DIR:~0,-1%"

:: --- Check if Server is Running (Port 8000) ---
netstat -an | find "LISTENING" | find ":8000" >nul
if %errorlevel% equ 0 (
    goto :STOP_MODE
) else (
    goto :START_MODE
)

:START_MODE
echo ---------------------------------------------------
echo    STARTING DEVELOPMENT ENVIRONMENT
echo ---------------------------------------------------

:: --- Start PHP Artisan Serve (Title: LARAVEL_SERVER) ---
start "LARAVEL_SERVER" /D "%PROJECT_DIR%" "C:\Program Files\Git\git-bash.exe" -c "php artisan serve; echo '--- PHP SERVER CLOSED ---'; exec bash"

:: --- Start NPM Run Dev (Title: NPM_BUILD) ---
start "NPM_BUILD" /D "%PROJECT_DIR%" "C:\Program Files\Git\git-bash.exe" -c "npm run dev; echo '--- NPM SERVER CLOSED ---'; exec bash"

:: --- Start Git Status (Title: GIT_STATUS) ---
start "GIT_STATUS" /D "%PROJECT_DIR%" "C:\Program Files\Git\git-bash.exe" -c "git status; echo '--- GIT STATUS CLOSED ---'; exec bash"

:: --- Open Antigravity Editor ---
start "" "%USERPROFILE%\AppData\Local\Programs\Antigravity\antigravity.exe" "%PROJECT_DIR%"

:: --- Wait for Server to Start ---
echo Waiting for Laravel server (port 8000)...
:wait_loop
timeout /t 1 >nul
netstat -an | find "LISTENING" | find ":8000" >nul
if errorlevel 1 goto wait_loop

echo Server detected! Opening browser...

:: --- Open Browser ---
start "" chrome "http://localhost:8000"
start "" chrome "http://localhost:8000/login"

echo.
echo All services started! Run this file again to STOP them.
pause
exit


:STOP_MODE
echo ---------------------------------------------------
echo    STOPPING DEVELOPMENT ENVIRONMENT
echo ---------------------------------------------------
echo.

:: --- Kill Processes by Window Title ---
echo Closing Laravel Server...
taskkill /FI "WINDOWTITLE eq LARAVEL_SERVER" /F >nul 2>&1

echo Closing NPM Build...
taskkill /FI "WINDOWTITLE eq NPM_BUILD" /F >nul 2>&1

echo Closing Git Status...
taskkill /FI "WINDOWTITLE eq GIT_STATUS" /F >nul 2>&1

:: --- Kill Antigravity Editor ---
echo Closing Editor...
taskkill /IM "antigravity.exe" /F >nul 2>&1

echo.
echo NOTE: Browser tabs cannot be closed automatically safely.
echo       Please close standard Chrome windows manually.
echo.
echo All other services stopped.
pause
exit
