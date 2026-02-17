$url = "https://storage.googleapis.com/chrome-for-testing-public/145.0.7632.67/win64/chromedriver-win64.zip"
$output = "chromedriver.zip"
$destination = "vendor/laravel/dusk/bin/chromedriver-win.exe"

Write-Host "Downloading ChromeDriver 145.0.7632.67..."
try {
    Invoke-WebRequest -Uri $url -OutFile $output
} catch {
    Write-Error "Failed to download: $_"
    exit 1
}

Write-Host "Extracting..."
Expand-Archive -Path $output -DestinationPath "temp_driver" -Force

Write-Host "Stopping any running ChromeDriver processes..."
Stop-Process -Name "chromedriver-win" -ErrorAction SilentlyContinue

Write-Host "Installing to $destination..."
if (Test-Path "temp_driver/chromedriver-win64/chromedriver.exe") {
    Move-Item -Path "temp_driver/chromedriver-win64/chromedriver.exe" -Destination $destination -Force
    Write-Host "Installation successful."
} else {
    Write-Error "Could not find extracted chromedriver.exe"
}

Write-Host "Cleaning up..."
Remove-Item $output -ErrorAction SilentlyContinue
Remove-Item "temp_driver" -Recurse -Force -ErrorAction SilentlyContinue

Write-Host "Done! Please run the driver again with: ./vendor/laravel/dusk/bin/chromedriver-win.exe --port=9515"
