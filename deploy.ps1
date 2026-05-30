param(
    [string]$Files
)

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "[*] DEPLOYING FILES TO GITHUB:" -ForegroundColor Cyan
Write-Host "Files: $Files" -ForegroundColor Gray
Write-Host "==========================================" -ForegroundColor Cyan

if (-not $Files) {
    Write-Host "[!] No files provided for deployment." -ForegroundColor Yellow
    Exit 0
}

# Split space-separated files
$fileList = $Files -split '\s+' | Where-Object { $_ -ne "" }

# Add files
foreach ($file in $fileList) {
    if (Test-Path $file) {
        Write-Host "[*] Staging: $file" -ForegroundColor Gray
        git add $file
    } else {
        Write-Host "[!] File not found: $file" -ForegroundColor Yellow
    }
}

# Commit
Write-Host "[*] Committing changes..." -ForegroundColor Gray
git commit -m "feat: auto-import movies/shows [skip ci]"

# Push to main
Write-Host "[*] Pushing to GitHub repository..." -ForegroundColor Gray
git push origin main

Write-Host "[✓] Deployment process completed successfully!" -ForegroundColor Green
