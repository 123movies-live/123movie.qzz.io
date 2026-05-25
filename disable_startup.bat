@echo off
title 123MOVIES - Remove Auto-Start
color 0C

echo.
echo  =====================================================
echo   123MOVIES Import Helper - Auto-Start Remover
echo  =====================================================
echo.

set "PROJECT_DIR=%~dp0"
if "%PROJECT_DIR:~-1%"=="\" set "PROJECT_DIR=%PROJECT_DIR:~0,-1%"

set "TASK_NAME=123Movies_ImportHelper"
set "VBS_PATH=%PROJECT_DIR%\start_helper_silent.vbs"
set "STARTUP_FOLDER=%APPDATA%\Microsoft\Windows\Start Menu\Programs\Startup"

:: Remove from Task Scheduler
schtasks /query /tn "%TASK_NAME%" >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo [*] Removing scheduled task: %TASK_NAME%...
    schtasks /delete /tn "%TASK_NAME%" /f >nul 2>&1
    echo     Task removed successfully.
) else (
    echo [*] No scheduled task found (already removed or never registered).
)

:: Remove from Startup folder (fallback)
if exist "%STARTUP_FOLDER%\123movies_import_helper.vbs" (
    echo [*] Removing from Startup folder...
    del "%STARTUP_FOLDER%\123movies_import_helper.vbs" >nul 2>&1
    echo     Startup shortcut removed.
)

:: Kill any running instance
tasklist /fi "IMAGENAME eq python.exe" /fi "WINDOWTITLE eq *import_helper*" >nul 2>&1
echo [*] Stopping any running Import Helper instances...
taskkill /f /im wscript.exe >nul 2>&1

echo.
echo  =====================================================
echo   [DONE] Auto-Start has been disabled.
echo  =====================================================
echo.
echo   The Import Helper will no longer start automatically.
echo   You can re-enable it anytime by running:
echo   enable_startup.bat
echo.
pause
