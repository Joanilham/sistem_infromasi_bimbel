@echo off
echo ========================================================
echo   Memulai Genius Education Stack dengan Podman
echo ========================================================

where podman-compose >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    echo Menggunakan podman-compose...
    podman-compose -f podman-compose.yml up -d --build
) else (
    echo Menggunakan podman compose...
    podman compose -f podman-compose.yml up -d --build
)

REM Forward port 8080 (Web) & 3307 (MySQL) ke Windows Localhost
taskkill /f /im ssh.exe >nul 2>nul
start "" /b ssh -i "%USERPROFILE%\.local\share\containers\podman\machine\machine" -p 61221 -o StrictHostKeyChecking=no -o UserKnownHostsFile=NUL -N -L 8080:127.0.0.1:8080 -L 3307:127.0.0.1:3307 root@127.0.0.1 >nul 2>nul

echo.
echo Status Kontainer:
podman ps
echo.
echo ========================================================
echo Aplikasi berhasil aktif dan dapat diakses di:
echo 👉 http://localhost:8080/login
echo ========================================================
