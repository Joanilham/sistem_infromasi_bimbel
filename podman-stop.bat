@echo off
echo Menghentikan Genius Education Stack Podman...

taskkill /f /im ssh.exe >nul 2>nul

where podman-compose >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    podman-compose -f podman-compose.yml down
) else (
    podman compose -f podman-compose.yml down
)

echo Selesai.
