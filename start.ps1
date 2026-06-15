# IT Learning Hub - ローカル起動スクリプト
Write-Host "🚀 IT Learning Hub を起動します..." -ForegroundColor Cyan

# バックエンド起動（バックグラウンド）
Write-Host "📡 Laravel API サーバーを起動中... (http://localhost:8000)" -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "Set-Location '$PSScriptRoot'; php artisan serve"

Start-Sleep -Seconds 2

# フロントエンド起動（バックグラウンド）
Write-Host "🎨 Vue3 開発サーバーを起動中... (http://localhost:5173)" -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "Set-Location '$PSScriptRoot\frontend'; npm run dev"

Write-Host ""
Write-Host "✅ 起動完了！" -ForegroundColor Green
Write-Host "  フロントエンド: http://localhost:5173" -ForegroundColor Cyan
Write-Host "  バックエンドAPI: http://localhost:8000/api/v1" -ForegroundColor Cyan
Write-Host ""
Write-Host "  デモアカウント: demo@example.com / password" -ForegroundColor White
