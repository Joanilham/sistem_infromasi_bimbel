<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\CameraPermissionHeaders;
use App\Models\User;
use App\Models\AuditLog;

beforeEach(function () {
    Storage::fake('local');

    $this->app['router']->get('/_test/role-middleware', function () {
        return 'ok';
    })->middleware(['web', RoleMiddleware::class . ':Siswa,Guru']);

    $this->app['router']->get('/_test/security-headers', function () {
        return 'ok';
    })->middleware(['web', SecurityHeaders::class]);

    $this->app['router']->get('/_test/camera-headers', function () {
        return 'ok';
    })->middleware(['web', CameraPermissionHeaders::class]);
});

// ── RoleMiddleware Tests ──

test('role middleware redirects guests to login', function () {
    $response = $this->get('/_test/role-middleware');
    $response->assertRedirect('login');
});

test('role middleware allows correct roles', function () {
    $user = User::factory()->create(['level' => 'Siswa']);
    
    $response = $this->actingAs($user)->get('/_test/role-middleware');
    $response->assertStatus(200);
    $response->assertSee('ok');
});

test('role middleware blocks incorrect roles and redirects to dashboard', function () {
    $user = User::factory()->create(['level' => 'Admin']);
    
    $response = $this->actingAs($user)->get('/_test/role-middleware');
    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHasErrors(['error']);
});

// ── SecurityHeaders Tests ──

test('security headers are added and power headers removed', function () {
    $response = $this->get('/_test/security-headers');
    
    $response->assertStatus(200);
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('X-XSS-Protection', '1; mode=block');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Permissions-Policy', 'camera=*, microphone=*, geolocation=()');
    
    // Ensure standard info headers are removed
    expect($response->headers->has('X-Powered-By'))->toBeFalse();
});

// ── CameraPermissionHeaders Tests ──

test('camera permission headers are set correctly', function () {
    $response = $this->get('/_test/camera-headers');
    
    $response->assertStatus(200);
    $response->assertHeader('Permissions-Policy', 'camera=*, microphone=*');
    $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
    $response->assertHeader('Cross-Origin-Embedder-Policy', 'unsafe-none');
    $response->assertHeader('Feature-Policy', "camera 'self'; microphone 'self'");
});

// ── BackupController Tests ──

test('backup controller index shows list of backups and settings', function () {
    $admin = User::factory()->create(['level' => 'Super Admin']);
    
    // Seed fake files
    Storage::put('backup_settings.json', json_encode([
        'backup_frequencies' => ['daily', 'weekly'],
        'backup_time'        => '03:30',
        'updated_at'         => '2026-05-19 12:00:00'
    ]));
    
    // Seed a fake sql backup
    Storage::put('backups/backup_daily_test.sql', 'SELECT 1;');
    
    $response = $this->actingAs($admin)->get(route('admin.backup.index'));
    
    $response->assertStatus(200);
    $response->assertSee('backup_daily_test.sql');
    $response->assertSee('03:30');
});

test('backup controller can save automatic backup settings', function () {
    $admin = User::factory()->create(['level' => 'Super Admin']);
    
    $response = $this->actingAs($admin)
        ->from(route('admin.backup.index'))
        ->post(route('admin.backup.toggle'), [
            'backup_frequencies' => ['daily', 'monthly'],
            'backup_time'        => '12:00',
        ]);
        
    $response->assertRedirect(route('admin.backup.index'));
    $response->assertSessionHas('success');
    
    // Assert settings file is updated
    expect(Storage::exists('backup_settings.json'))->toBeTrue();
    $settings = json_decode(Storage::get('backup_settings.json'), true);
    expect($settings['backup_frequencies'])->toBe(['daily', 'monthly']);
    expect($settings['backup_time'])->toBe('12:00');
});

test('backup controller trigger manual backup creation successfully', function () {
    $admin = User::factory()->create(['level' => 'Super Admin']);
    Artisan::shouldReceive('call')
        ->once()
        ->with('db:auto-backup', ['--force' => true, '--type' => 'manual'])
        ->andReturn(0);
        
    $response = $this->actingAs($admin)
        ->from(route('admin.backup.index'))
        ->post(route('admin.backup.create'));
        
    $response->assertRedirect(route('admin.backup.index'));
    $response->assertSessionHas('success');
});

test('backup controller can upload database backup', function () {
    $admin = User::factory()->create(['level' => 'Super Admin']);
    $file = \Illuminate\Http\UploadedFile::fake()->create('custom_backup.sql', 100);
    
    $response = $this->actingAs($admin)
        ->from(route('admin.backup.index'))
        ->post(route('admin.backup.upload'), [
            'backup_file' => $file
        ]);
        
    $response->assertRedirect(route('admin.backup.index'));
    $response->assertSessionHas('success');
    expect(Storage::exists('backups/custom_backup.sql'))->toBeTrue();
});

test('backup controller can download backup file', function () {
    $admin = User::factory()->create(['level' => 'Super Admin']);
    
    $dir = storage_path('app/backups');
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $filePath = $dir . '/download_test.sql';
    file_put_contents($filePath, 'SELECT * FROM users;');

    $response = $this->actingAs($admin)->get(route('admin.backup.download', 'download_test.sql'));
    
    $response->assertStatus(200);
    $response->assertHeader('Content-Disposition', 'attachment; filename=download_test.sql');

    if (file_exists($filePath)) {
        unlink($filePath);
    }
});

test('backup controller can delete backup file', function () {
    $admin = User::factory()->create(['level' => 'Super Admin']);
    Storage::put('backups/delete_test.sql', 'SELECT * FROM users;');
    
    $response = $this->actingAs($admin)
        ->from(route('admin.backup.index'))
        ->delete(route('admin.backup.destroy', 'delete_test.sql'));
        
    $response->assertRedirect(route('admin.backup.index'));
    $response->assertSessionHas('success');
    expect(Storage::exists('backups/delete_test.sql'))->toBeFalse();
});
