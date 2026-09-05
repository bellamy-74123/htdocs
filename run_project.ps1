Write-Host "===================================================" -ForegroundColor Cyan
Write-Host "  تشغيل نظام إدارة الصيدلية والذكاء الاصطناعي" -ForegroundColor Cyan
Write-Host "===================================================" -ForegroundColor Cyan

# 1. Start Python AI Service
Write-Host "[1/3] تشغيل خادم الذكاء الاصطناعي Python FastAPI..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$PSScriptRoot/ai-engine'; py -m uvicorn api.server:app --host 127.0.0.1 --port 8000 --reload"

# 2. Start PHP Backend Server
Write-Host "[2/3] فحص وتشغيل خادم PHP Backend..." -ForegroundColor Yellow
$phpPath = "php"
if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    if (Test-Path "C:\xampp\php\php.exe") {
        $phpPath = "C:\xampp\php\php.exe"
    }
}

Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$PSScriptRoot'; & '$phpPath' -S localhost:8080 -t backend"

# 3. Open Browser
Write-Host "[3/3] فتح الواجهة الأمامية..." -ForegroundColor Green
Start-Sleep -Seconds 2
Start-Process "$PSScriptRoot/frontend/index.html"

Write-Host "`nتم تشغيل النظام بنجاح! يمكنك الآن تجربة كافة الواجهات." -ForegroundColor Green
