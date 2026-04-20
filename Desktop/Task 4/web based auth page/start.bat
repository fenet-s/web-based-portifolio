@echo off
setlocal

set "PROJECT_ROOT=%~dp0"
set "PHP_EXE="

where php >nul 2>nul
if %errorlevel%==0 set "PHP_EXE=php"

if not defined PHP_EXE if exist "C:\xampp\php\php.exe" set "PHP_EXE=C:\xampp\php\php.exe"

if not defined PHP_EXE (
    echo PHP executable not found.
    echo Install PHP, XAMPP, or Laragon, then try again.
    pause
    exit /b 1
)

start "PHP Server" "%PHP_EXE%" -S 127.0.0.1:8000 -t "%PROJECT_ROOT%"
timeout /t 2 /nobreak >nul
start "" "http://127.0.0.1:8000/index.php"
