<?php

use App\Console\Commands\AutoAlphaCommand;
use App\Models\Akademik\Absensi;
use App\Models\Akademik\PesertaDidik;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
});

test('auto alpha command defaults to enabled and saves settings', function () {
    $settings = AutoAlphaCommand::getSettings();
    expect($settings['auto_alpha_enabled'])->toBeTrue()
        ->and($settings['auto_alpha_time'])->toBe('23:00');

    AutoAlphaCommand::saveSettings([
        'auto_alpha_enabled' => false,
        'auto_alpha_time'    => '22:30',
        'exclude_sunday'     => true,
    ]);

    expect(AutoAlphaCommand::isAutoAlphaEnabled())->toBeFalse();
    $updated = AutoAlphaCommand::getSettings();
    expect($updated['auto_alpha_time'])->toBe('22:30')
        ->and($updated['exclude_sunday'])->toBeTrue();
});

test('auto alpha command skips when disabled unless forced', function () {
    AutoAlphaCommand::saveSettings(['auto_alpha_enabled' => false]);

    // Command should output warning and exit
    Artisan::call('absensi:auto-alpha');
    $output = Artisan::output();
    expect($output)->toContain('Fitur Auto Alpha sedang NONAKTIF.');

    // Command with --force should bypass disabled state
    Artisan::call('absensi:auto-alpha', ['--force' => true]);
    $forcedOutput = Artisan::output();
    expect($forcedOutput)->toContain('Memulai pengecekan absensi harian...')
        ->and($forcedOutput)->toContain('Pengecekan selesai.');
});

test('admin can update auto alpha settings via web endpoint', function () {
    $admin = User::factory()->create([
        'level' => 'Super Admin',
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($admin)->post(route('absensi.auto-alpha.settings'), [
        'auto_alpha_enabled' => '1',
        'auto_alpha_time'    => '21:45',
        'exclude_sunday'     => '1',
    ]);

    $response->assertRedirect();
    $settings = AutoAlphaCommand::getSettings();
    expect($settings['auto_alpha_enabled'])->toBeTrue()
        ->and($settings['auto_alpha_time'])->toBe('21:45')
        ->and($settings['exclude_sunday'])->toBeTrue();
});

test('admin can trigger auto alpha instantly via ajax endpoint', function () {
    $admin = User::factory()->create([
        'level' => 'Super Admin',
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($admin)->postJson(route('absensi.auto-alpha.run'));

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);
});
