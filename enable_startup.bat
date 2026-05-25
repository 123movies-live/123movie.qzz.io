@echo off
title 123MOVIES - Auto-Start Setup
color 0A

echo.
echo  =====================================================
echo   123MOVIES Import Helper - Auto-Start Installer
echo  =====================================================
echo.

:: Get the directory where this .bat file is located
set "PROJECT_DIR=%~dp0"
:: Remove trailing backslash
if "%PROJECT_DIR:~-1%"=="\" set "PROJECT_DIR=%PROJECT_DIR:~0,-1%"

set "TASK_NAME=123Movies_ImportHelper"
set "SCRIPT_PATH=%PROJECT_DIR%\import_helper.py"
set "LOG_PATH=%PROJECT_DIR%\import_helper.log"
set "VBS_PATH=%PROJECT_DIR%\start_helper_silent.vbs"

echo [*] Project Folder: %PROJECT_DIR%
echo [*] Script:         %SCRIPT_PATH%
echo [*] Log File:       %LOG_PATH%
echo.

:: Check if Python is available
python --version >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [!] ERROR: Python not found in PATH.
    echo     Please install Python from https://python.org and ensure it's added to PATH.
    echo.
    pause
    exit /b 1
)

:: Check if import_helper.py exists
if not exist "%SCRIPT_PATH%" (
    echo [!] ERROR: import_helper.py not found in:
    echo     %PROJECT_DIR%
    echo.
    pause
    exit /b 1
)

echo [*] Python found. Proceeding with setup...
echo.

:: Create a silent VBScript launcher (hides the console window entirely)
echo [*] Creating silent background launcher (start_helper_silent.vbs)...
(
echo Set WshShell = CreateObject("WScript.Shell"^)
echo Dim logPath
echo logPath = "%LOG_PATH:\=\\%"
echo WshShell.Run "cmd /c cd /d ""%PROJECT_DIR:\=\\%"" && python -u import_helper.py >> """ ^& logPath ^& """ 2>^&1", 0, False
echo Set WshShell = Nothing
) > "%VBS_PATH%"

echo     Done: %VBS_PATH%
echo.

:: Delete any existing scheduled task with the same name (clean reinstall)
schtasks /query /tn "%TASK_NAME%" >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo [*] Removing previous scheduled task...
    schtasks /delete /tn "%TASK_NAME%" /f >nul 2>&1
    echo     Old task removed.
    echo.
)

:: Register new Task Scheduler entry: run at LOGON with HIGHEST privilege, hidden
echo [*] Registering Windows Task Scheduler entry...
echo     Task Name: %TASK_NAME%
echo     Trigger:   At User Logon
echo     Priority:  Highest (no UAC popup)
echo.

schtasks /create /tn "%TASK_NAME%" /tr "wscript.exe \"%VBS_PATH%\"" /sc ONLOGON /rl HIGHEST /f >nul 2>&1

if %ERRORLEVEL% EQU 0 (
    echo  =====================================================
    echo   [SUCCESS] Auto-Start Registered!
    echo  =====================================================
    echo.
    echo   The 123MOVIES Import Helper will now automatically
    echo   start silently in the background every time you
    echo   log into Windows.
    echo.
    echo   Log file location:
    echo   %LOG_PATH%
    echo.
    echo   To DISABLE auto-start later, run:
    echo   disable_startup.bat
    echo.
    echo  =====================================================
) else (
    echo [!] Task Scheduler registration failed.
    echo     Trying alternative: Startup Folder method...
    echo.
    
    :: Fallback: copy VBS to Windows Startup folder
    set "STARTUP_FOLDER=%APPDATA%\Microsoft\Windows\Start Menu\Programs\Startup"
    copy "%VBS_PATH%" "%STARTUP_FOLDER%\123movies_import_helper.vbs" >nul 2>&1
    
    if %ERRORLEVEL% EQU 0 (
        echo  =====================================================
        echo   [SUCCESS] Auto-Start Registered (Startup Folder)!
        echo  =====================================================
        echo.
        echo   Shortcut placed in Windows Startup folder.
        echo   The helper will auto-launch on next login.
        echo.
    ) else (
        echo [!] Both methods failed. Please run this file as Administrator.
        echo     Right-click enable_startup.bat and select "Run as administrator".
        echo.
    )
)

:: Offer to start the helper RIGHT NOW without waiting for reboot
echo.
echo [?] Start the Import Helper right now? (y/n)
set /p START_NOW=    Enter choice: 
if /i "%START_NOW%"=="y" (
    echo.
    echo [*] Launching Import Helper in background...
    start "" wscript.exe "%VBS_PATH%"
    timeout /t 2 >nul
    echo [*] Import Helper started! Backend is now running on:
    echo     http://localhost:3000
    echo.
    echo [*] Open admin.html in your browser to use the dashboard.
)

echo.
echo  Press any key to close this window...
pause >nul
