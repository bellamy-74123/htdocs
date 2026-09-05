@echo off
echo ===================================================
echo   تشغيل نظام إدارة الصيدلية والذكاء الاصطناعي
echo ===================================================

echo [1/3] جاري تشغيل محرك الذكاء الاصطناعي Python FastAPI على http://127.0.0.1:8000 ...
start "AI Engine Server" cmd /k "cd ai-engine && py -m uvicorn api.server:app --host 127.0.0.1 --port 8000 --reload"

echo [2/3] فحص خادم PHP Backend...
set PHP_CMD=php
where php >nul 2>nul
if %errorlevel% neq 0 (
    if exist "C:\xampp\php\php.exe" (
        set PHP_CMD="C:\xampp\php\php.exe"
    )
)

echo بدء خادم الـ REST API على http://localhost:8080 ...
start "PHP Backend Server" cmd /k "%PHP_CMD% -S localhost:8080 -t backend"

echo [3/3] فتح واجهة النظام في المتصفح...
timeout /t 2 >nul
start frontend\index.html

echo.
echo تم تشغيل جميع خدمات النظام بنجاح!
pause
