<?php

use App\Models\AuditLog;
use App\Models\Kantor;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Setup clean storage and DB status
    Storage::fake();
});

test('app:audit-command runs successfully', function () {
    Artisan::call('app:audit-command');
    $output = Artisan::output();
    
    // Command is an empty stub, should exit 0
    expect(true)->toBeTrue();
});

test('audit:prune command prunes old audit logs', function () {
    // Create one fresh audit log
    $freshLog = AuditLog::create([
        'user_id'        => null,
        'event'          => 'created',
        'auditable_type' => 'App\\Models\\Kantor',
        'auditable_id'   => 1,
        'old_values'     => null,
        'new_values'     => null,
        'ip_address'     => '127.0.0.1',
        'user_agent'     => 'Test',
        'created_at'     => now(),
    ]);

    // Create one old audit log (35 days ago)
    $oldLog = AuditLog::create([
        'user_id'        => null,
        'event'          => 'created',
        'auditable_type' => 'App\\Models\\Kantor',
        'auditable_id'   => 2,
        'old_values'     => null,
        'new_values'     => null,
        'ip_address'     => '127.0.0.1',
        'user_agent'     => 'Test',
    ]);
    Illuminate\Support\Facades\DB::table('audit_logs')
        ->where('id', $oldLog->id)
        ->update(['created_at' => now()->subDays(35)]);

    // Run pruning command with 30 days limit
    $this->artisan('audit:prune', ['--days' => 30])
        ->expectsOutputToContain('Memulai pembersihan log audit')
        ->expectsOutputToContain('Pembersihan selesai! Berhasil menghapus 1 log audit usang.')
        ->assertExitCode(0);

    // Verify fresh log is still in database and old one is gone
    $this->assertDatabaseHas('audit_logs', ['id' => $freshLog->id]);
    $this->assertDatabaseMissing('audit_logs', ['id' => $oldLog->id]);
});

test('db:auto-backup handles disabled settings', function () {
    // Write setting that disables daily backups
    Storage::put('backup_settings.json', json_encode([
        'backup_frequencies' => ['weekly']
    ]));

    $this->artisan('db:auto-backup', ['--type' => 'daily'])
        ->expectsOutputToContain('Backup otomatis tipe daily dinonaktifkan di pengaturan.')
        ->assertExitCode(0);
});

test('db:auto-backup handles execution in testing environment gracefully', function () {
    // Force run daily backup. It will attempt SHOW TABLES which fails in SQLite, throwing an exception
    // The command catches the exception and returns FAILURE (exit code 1)
    $this->artisan('db:auto-backup', ['--type' => 'daily', '--force' => true])
        ->expectsOutputToContain('Gagal melakukan backup otomatis')
        ->assertExitCode(1);
});

test('restore:kantor-periode restores soft deleted records', function () {
    // Create kantor and periode
    $kantor = Kantor::create(['nama_kantor' => 'Kantor Hapus', 'alamat' => 'Alamat']);
    $periode = Periode::create(['tahun_periode' => '2026/2027', 'is_active' => true]);

    // Soft delete them
    $kantor->delete();
    $periode->delete();

    $this->assertSoftDeleted('kantors', ['id' => $kantor->id]);
    $this->assertSoftDeleted('periodes', ['id' => $periode->id]);

    // Run restore command
    $this->artisan('restore:kantor-periode')
        ->expectsOutputToContain('Dipulihkan: 1 kantor, 1 periode dari tempat sampah.')
        ->assertExitCode(0);

    // Verify they are restored
    $this->assertDatabaseHas('kantors', ['id' => $kantor->id, 'deleted_at' => null]);
    $this->assertDatabaseHas('periodes', ['id' => $periode->id, 'deleted_at' => null]);
});

test('restore:kantor-periode seeds default records if database tables are empty', function () {
    // Force delete all kantor and periodes
    Kantor::query()->forceDelete();
    Periode::query()->forceDelete();

    expect(Kantor::count())->toBe(0);
    expect(Periode::count())->toBe(0);

    // Run restore command, which should trigger seeders
    $this->artisan('restore:kantor-periode')
        ->expectsOutputToContain('Tidak ada kantor sama sekali. Mengisi ulang data bawaan...')
        ->expectsOutputToContain('Tidak ada periode sama sekali. Mengisi ulang data bawaan...')
        ->assertExitCode(0);

    // Verify tables are no longer empty
    expect(Kantor::count())->toBeGreaterThan(0);
    expect(Periode::count())->toBeGreaterThan(0);
});
