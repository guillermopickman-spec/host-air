@echo off
title Hostair Development Servers
color 0A

echo ========================================
echo   Hostair Development Server Starter
echo ========================================
echo.

REM Check if Node.js is installed
where node >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Node.js is not installed or not in PATH.
    echo Please install Node.js and try again.
    pause
    exit /b 1
)

REM Check if PHP is installed
where php >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: PHP is not installed or not in PATH.
    echo Please install PHP and try again.
    pause
    exit /b 1
)

REM Navigate to project directory
cd /d "%~dp0"
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Could not change to script directory.
    pause
    exit /b 1
)

echo Starting Laravel development server...
echo.

REM Start Laravel server in a new window
start "Laravel Server" cmd /k "php artisan serve --host=127.0.0.1 --port=8000"

echo Waiting 3 seconds for Laravel server to initialize...
timeout /t 3 /nobreak >nul

echo.
echo Installing/updating npm dependencies...
echo.

REM Install dependencies if node_modules missing
if not exist "node_modules" (
    echo node_modules not found. Running npm install...
    call npm install
    if %ERRORLEVEL% NEQ 0 (
        echo ERROR: npm install failed.
        pause
        exit /b 1
    )
) else (
    echo node_modules found. Skipping npm install.
)

REM Adjust Vite proxy for local development (if currently set to Docker service)
echo.
echo Configuring Vite proxy for local development...
powershell -Command "if ((Get-Content vite.config.js) -match 'http://app:80') { (Get-Content vite.config.js) -replace 'http://app:80', 'http://127.0.0.1:8000' | Set-Content vite.config.js; echo 'Updated Vite proxy to 127.0.0.1:8000'; } else { echo 'Vite proxy already configured for local or different target'; }"

echo.
echo Starting Vite frontend dev server...
echo.

REM Start Vite in a new window
start "Vite Frontend" cmd /k "npm run dev"

echo.
echo ========================================
echo   Both servers are starting...
echo   Laravel:  http://127.0.0.1:8000
echo   Vite:     http://127.0.0.1:5173
echo ========================================
echo.
echo Close the individual command windows to stop the servers.
echo.
pause
