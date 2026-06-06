<?php

use App\Models\AuditLog;
use App\Models\User;

// ── Index ──────────────────────────────────────────────────────────────────
test('super admin can view audit logs index', function () {
    $admin = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('admin.audit-logs.index'));
    $response->assertStatus(200);
});

test('admin can view audit logs (only their own)', function () {
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);

    AuditLog::create([
        'user_id'        => $admin->id,
        'event'          => 'created',
        'auditable_type' => 'App\\Models\\Kantor',
        'auditable_id'   => 1,
        'old_values'     => null,
        'new_values'     => json_encode(['nama_kantor' => 'Test']),
        'ip_address'     => '127.0.0.1',
        'user_agent'     => 'Test',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.audit-logs.index'));
    $response->assertStatus(200);
});

test('audit logs index supports search filter', function () {
    $admin = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('admin.audit-logs.index', ['search' => 'Kantor']));
    $response->assertStatus(200);
});

test('audit logs index supports event filter', function () {
    $admin = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->get(route('admin.audit-logs.index', ['event' => 'created']));
    $response->assertStatus(200);
});

// ── Destroy ────────────────────────────────────────────────────────────────
test('super admin can delete audit log', function () {
    $admin = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);
    $log   = AuditLog::create([
        'user_id'        => $admin->id,
        'event'          => 'deleted',
        'auditable_type' => 'App\\Models\\Kantor',
        'auditable_id'   => 1,
        'old_values'     => null,
        'new_values'     => null,
        'ip_address'     => '127.0.0.1',
        'user_agent'     => 'Test',
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.audit-logs.destroy', $log->id));
    $response->assertRedirect();
    $this->assertDatabaseMissing('audit_logs', ['id' => $log->id]);
});

test('non super admin cannot delete audit log', function () {
    $admin = User::factory()->create(['level' => 'Admin', 'is_active' => true]);
    $log   = AuditLog::create([
        'user_id'        => $admin->id,
        'event'          => 'created',
        'auditable_type' => 'App\\Models\\Periode',
        'auditable_id'   => 1,
        'old_values'     => null,
        'new_values'     => null,
        'ip_address'     => '127.0.0.1',
        'user_agent'     => 'Test',
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.audit-logs.destroy', $log->id));
    $response->assertStatus(403);
});
