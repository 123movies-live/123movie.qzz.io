param(
    [string[]]$Files,
    [switch]$Force
)

$ftpHost = "ftpupload.net"
$ftpUser = "if0_41831134"
$ftpPass = "Sheikh0100"
$remoteDir = "/htdocs"

function Upload-File {
    param($localPath, $remotePath)
    try {
        $uri = New-Object System.Uri("ftp://$ftpHost$remotePath")
        $request = [System.Net.FtpWebRequest]::Create($uri)
        $request.Credentials = New-Object System.Net.NetworkCredential($ftpUser, $ftpPass)
        $request.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
        
        $fileBytes = [System.IO.File]::ReadAllBytes($localPath)
        $request.ContentLength = $fileBytes.Length
        
        $requestStream = $request.GetRequestStream()
        $requestStream.Write($fileBytes, 0, $fileBytes.Length)
        $requestStream.Close()
        $requestStream.Dispose()
        
        $response = $request.GetResponse()
        $response.Close()
        $response.Dispose()
        Write-Host "Uploaded: $localPath to $remotePath"
    } catch {
        Write-Host "Failed to upload ${localPath}: $($_.Exception.Message)"
    }
}

function Create-Directory {
    param($remotePath)
    try {
        $uri = New-Object System.Uri("ftp://$ftpHost$remotePath")
        $request = [System.Net.FtpWebRequest]::Create($uri)
        $request.Credentials = New-Object System.Net.NetworkCredential($ftpUser, $ftpPass)
        $request.Method = [System.Net.WebRequestMethods+Ftp]::MakeDirectory
        $response = $request.GetResponse()
        $response.Close()
        Write-Host "Created Directory: $remotePath"
    } catch {
        # Ignore if directory already exists
        Write-Host "Directory might already exist or error: $remotePath"
    }
}

Set-Location $PSScriptRoot
Write-Host "Starting Deployment in: $PSScriptRoot"

$lastDeployFile = Join-Path $PSScriptRoot ".last_deploy"
$lastDeployTime = [DateTime]::MinValue

if (Test-Path $lastDeployFile) {
    $lastDeployTime = (Get-Item $lastDeployFile).LastWriteTime
    Write-Host "Last successful deployment was at: $lastDeployTime"
} else {
    Write-Host "No prior deployment found. Doing a full deployment."
    $Force = $true
}

# Determine which files to upload
$filesToUpload = @()

if ($Files -and $Files.Count -gt 0) {
    Write-Host "Uploading explicitly requested files."
    foreach ($fileName in $Files) {
        $file = Get-Item -Path (Join-Path $PSScriptRoot $fileName) -ErrorAction SilentlyContinue
        if ($file) {
            $filesToUpload += $file
        }
    }
} else {
    # Scan root files
    $allRootFiles = Get-ChildItem -Path $PSScriptRoot -File | Where-Object { $_.Name -notmatch "deploy.*\.ps1" -and $_.Name -ne ".last_deploy" -and $_.Name -ne "scratch_test_db.js" }
    foreach ($file in $allRootFiles) {
        if ($Force -or $file.LastWriteTime -gt $lastDeployTime) {
            $filesToUpload += $file
        }
    }
}

Write-Host "Found $($filesToUpload.Count) root files that need uploading."
foreach ($file in $filesToUpload) {
    Write-Host "Processing: $($file.Name) (Modified: $($file.LastWriteTime))"
    Upload-File -localPath $file.FullName -remotePath "$remoteDir/$($file.Name)"
}

# Scan and upload assets if they changed or we are forcing, and only if not uploading specific files
if (-not $Files -or $Files.Count -eq 0) {
    Create-Directory -remotePath "$remoteDir/assets"
    $assets = Get-ChildItem -Path ./assets -File
    $assetsToUpload = @()
    foreach ($asset in $assets) {
        if ($Force -or $asset.LastWriteTime -gt $lastDeployTime) {
            $assetsToUpload += $asset
        }
    }

    Write-Host "Found $($assetsToUpload.Count) assets that need uploading."
    foreach ($asset in $assetsToUpload) {
        Write-Host "Processing asset: $($asset.Name)"
        Upload-File -localPath $asset.FullName -remotePath "$remoteDir/assets/$($asset.Name)"
    }
}

# Update last deployment timestamp on success
New-Item -Path $lastDeployFile -ItemType File -Force | Out-Null
(Get-Item $lastDeployFile).LastWriteTime = [DateTime]::Now
Write-Host "Deployment Complete! Updated timestamp file."
