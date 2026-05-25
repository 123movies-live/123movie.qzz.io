@echo off
title 123Movies Importer Companion Server
cd /d "%~dp0"
echo ====================================================
echo Starting 123Movies Importer Companion Server...
echo ====================================================
python import_helper.py
pause
