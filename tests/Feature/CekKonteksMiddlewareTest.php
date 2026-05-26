<?php

use App\Http\Middleware\CekKonteks;
use App\Models\Kantor;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    // Dynamic route registration for testing middleware
    Route::get('/test-cek-konteks', function () {
        return 'success';
    })->middleware(['web', CekKonteks::class]);

    $this->kantor = Kantor::create(['nama_kantor' => 'Kantor A', 'alamat' => 'Alamat']);
    $this->periode = Periode::create(['tahun_periode' => '2026', 'is_active' => true]);
});

test('it proceeds if session already has context', function () {
    $user = User::factory()->create(['level' => 'Super Admin', 'is_active' => true]);
    
    $response = $this->actingAs($user)
        ->withSession(['kantor_id' => $this->kantor->id, 'periode_id' => $this->periode->id])
        ->get('/test-cek-konteks');
        
    $response->assertStatus(200);
    $response->assertSee('success');
});

test('it auto-assigns default context for super admin/admin if empty in session', function () {
    $user = User::factory()->create([
        'level' => 'Super Admin',
        'is_active' => true,
        'kantor_id' => $this->kantor->id,
        'periode_id' => $this->periode->id,
    ]);
    
    $response = $this->actingAs($user)
        ->get('/test-cek-konteks');
        
    $response->assertStatus(200);
    $response->assertSessionHas('kantor_id', $this->kantor->id);
    $response->assertSessionHas('periode_id', $this->periode->id);
});

test('it falls back to first kantor and active periode if user has no assigned defaults', function () {
    $user = User::factory()->create([
        'level' => 'Admin',
        'is_active' => true,
        'kantor_id' => null,
        'periode_id' => null,
    ]);
    
    $response = $this->actingAs($user)
        ->get('/test-cek-konteks');
        
    $response->assertStatus(200);
    $response->assertSessionHas('kantor_id', $this->kantor->id);
    $response->assertSessionHas('periode_id', $this->periode->id);
});

test('it redirects to context selection if no kantor or periode exists at all', function () {
    $user = User::factory()->create([
        'level' => 'Admin',
        'is_active' => true,
        'kantor_id' => null,
        'periode_id' => null,
    ]);
    
    // Force delete all Kantor and Periode
    Kantor::query()->forceDelete();
    Periode::query()->forceDelete();
    
    $response = $this->actingAs($user)
        ->get('/test-cek-konteks');
        
    $response->assertRedirect(route('konteks.select'));
    $response->assertSessionHas('error');
});

test('it redirects to dashboard if guest or non-admin attempts to access without context', function () {
    $user = User::factory()->create([
        'level' => 'Siswa',
        'is_active' => true,
    ]);
    
    $response = $this->actingAs($user)
        ->get('/test-cek-konteks');
        
    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('error', 'Fitur belum tersedia untuk akses spesifik ini.');
});
