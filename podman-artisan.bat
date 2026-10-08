@echo off
REM Jalankan php artisan di dalam kontainer app podman
podman exec -it geniusedu-app php artisan %*
