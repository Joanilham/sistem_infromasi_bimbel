<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Schedule::command('pembayaran:check-tenggat')->dailyAt('08:00');
// Ambil jam backup kustom dari berkas pengaturan
$backupTime = '01:00';
if (Storage::exists('backup_settings.json')) {
    $settings = json_decode(Storage::get('backup_settings.json'), true);
    $backupTime = $settings['backup_time'] ?? '01:00';
}

// Stagger mingguan (+5 menit) dan bulanan (+10 menit) untuk mencegah bentrokan resource
$weeklyTime = date('H:i', strtotime($backupTime . ' +5 minutes'));
$monthlyTime = date('H:i', strtotime($backupTime . ' +10 minutes'));

// Harian (Setiap Hari Pukul jam kustom jika dicentang oleh Super Admin)
Schedule::command('db:auto-backup --type=daily')->dailyAt($backupTime)->when(function () {
    if (Storage::exists('backup_settings.json')) {
        $settings = json_decode(Storage::get('backup_settings.json'), true);
        $enabledFreqs = $settings['backup_frequencies'] ?? ['daily'];
        return in_array('daily', $enabledFreqs);
    }
    return true; // Default aktif jika berkas pengaturan belum dibuat
});

// Mingguan (Setiap Hari Minggu Pukul jam kustom + 5 menit jika dicentang oleh Super Admin)
Schedule::command('db:auto-backup --type=weekly')->weeklyOn(0, $weeklyTime)->when(function () {
    if (Storage::exists('backup_settings.json')) {
        $settings = json_decode(Storage::get('backup_settings.json'), true);
        $enabledFreqs = $settings['backup_frequencies'] ?? ['daily'];
        return in_array('weekly', $enabledFreqs);
    }
    return false; // Default nonaktif
});

// Bulanan (Setiap Tanggal 1 Pukul jam kustom + 10 menit jika dicentang oleh Super Admin)
Schedule::command('db:auto-backup --type=monthly')->monthlyOn(1, $monthlyTime)->when(function () {
    if (Storage::exists('backup_settings.json')) {
        $settings = json_decode(Storage::get('backup_settings.json'), true);
        $enabledFreqs = $settings['backup_frequencies'] ?? ['daily'];
        return in_array('monthly', $enabledFreqs);
    }
    return false; // Default nonaktif
});

// Otomatis Alpha (Waktu kustom & toggle status dari berkas pengaturan)
$autoAlphaSettings = \App\Console\Commands\AutoAlphaCommand::getSettings();
$autoAlphaTime = $autoAlphaSettings['auto_alpha_time'] ?? '23:00';

Schedule::command('absensi:auto-alpha')
    ->dailyAt($autoAlphaTime)
    ->when(function () {
        return \App\Console\Commands\AutoAlphaCommand::isAutoAlphaEnabled();
    });

// ✅ Auto-submit ujian CBT yang melewati batas waktu (jalan setiap menit)
// Memberikan toleransi 5 menit setelah durasi habis sebelum submit otomatis
Schedule::command('cbt:auto-submit-timeout')->everyMinute()->withoutOverlapping();
