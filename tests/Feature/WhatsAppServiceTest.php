<?php

use App\Models\Master;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    // Clean Master table
    Master::query()->delete();
});

test('sendMessage returns error if Master configuration or token is missing', function () {
    $service = new WhatsAppService();
    $result = $service->sendMessage('08123456789', 'Halo Test');

    expect($result)->toBeArray();
    expect($result['status'])->toBe('error');
    expect($result['message'])->toContain('Token WhatsApp belum dikonfigurasi');
});

test('sendMessage successfully sends request using default Fonnte API url', function () {
    // Create Master with wa_token but empty wa_url (triggering Fonnte default)
    Master::create([
        'nama_lembaga' => 'Genius Edu',
        'wa_token' => 'my-fonnte-token',
        'wa_url' => null
    ]);

    Http::fake([
        'https://api.fonnte.com/send' => Http::response(['status' => true, 'detail' => 'sent'], 200)
    ]);

    $service = new WhatsAppService();
    $result = $service->sendMessage('08123456789', 'Halo Fonnte');

    expect($result['status'])->toBe('success');
    expect($result['data']['detail'])->toBe('sent');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.fonnte.com/send'
            && $request->hasHeader('Authorization', 'my-fonnte-token')
            && $request['target'] === '08123456789'
            && $request['message'] === 'Halo Fonnte';
    });
});

test('sendMessage successfully sends request using custom gateway URL', function () {
    Master::create([
        'nama_lembaga' => 'Genius Edu',
        'wa_token' => 'my-custom-token',
        'wa_url' => 'https://api.customgateway.com/v1/messages',
        'instance_id' => 'inst-123'
    ]);

    Http::fake([
        'https://api.customgateway.com/*' => Http::response(['success' => true], 200)
    ]);

    $service = new WhatsAppService();
    $result = $service->sendMessage('08123456789', 'Halo Custom');

    expect($result['status'])->toBe('success');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.customgateway.com/v1/messages'
            && $request->hasHeader('Authorization', 'Bearer my-custom-token')
            && $request['number'] === '08123456789'
            && $request['message'] === 'Halo Custom'
            && $request['instance_id'] === 'inst-123';
    });
});

test('sendMessage returns error when API returns non-successful response', function () {
    Master::create([
        'nama_lembaga' => 'Genius Edu',
        'wa_token' => 'my-fonnte-token',
        'wa_url' => null
    ]);

    Http::fake([
        'https://api.fonnte.com/send' => Http::response('Unauthorized', 401)
    ]);

    Log::shouldReceive('error')
        ->once()
        ->with(Mockery::on(function ($message) {
            return str_contains($message, 'WhatsApp API Error:');
        }));

    $service = new WhatsAppService();
    $result = $service->sendMessage('08123456789', 'Halo Error');

    expect($result['status'])->toBe('error');
    expect($result['message'])->toContain('API Error: 401');
});

test('sendMessage catches exceptions and logs error', function () {
    Master::create([
        'nama_lembaga' => 'Genius Edu',
        'wa_token' => 'my-fonnte-token',
        'wa_url' => null
    ]);

    Http::fake(function () {
        throw new \Exception('Connection failure');
    });

    Log::shouldReceive('error')
        ->once()
        ->with(Mockery::on(function ($message) {
            return str_contains($message, 'WhatsApp API Exception: Connection failure');
        }));

    $service = new WhatsAppService();
    $result = $service->sendMessage('08123456789', 'Halo Exception');

    expect($result['status'])->toBe('error');
    expect($result['message'])->toContain('Exception: Connection failure');
});

test('sendAsync dispatches closure queue task after response', function () {
    Master::create([
        'nama_lembaga' => 'Genius Edu',
        'wa_token' => 'my-fonnte-token',
        'wa_url' => null
    ]);

    Http::fake([
        'https://api.fonnte.com/send' => Http::response(['status' => true], 200)
    ]);

    // Send async
    WhatsAppService::sendAsync('08123456789', 'Halo Async');
    
    // Trigger termination event which processes terminating/afterResponse callbacks
    app()->terminate();
    
    // In testing, queues are synced, so we can verify if the request was made
    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.fonnte.com/send'
            && $request['message'] === 'Halo Async';
    });
});

test('sendAsync ignores empty phone number', function () {
    Http::fake();

    WhatsAppService::sendAsync('', 'Message');

    Http::assertNothingSent();
});

test('sendAsync catches and logs exception if dispatch fails', function () {
    Log::shouldReceive('error')
        ->once()
        ->with(Mockery::on(function ($message) {
            return str_contains($message, 'Gagal kirim WA async: Query failed');
        }));

    $originalResolver = Master::getConnectionResolver();

    $resolver = Mockery::mock(\Illuminate\Database\ConnectionResolverInterface::class);
    $resolver->shouldReceive('connection')
             ->andThrow(new \Exception('Query failed'));

    Master::setConnectionResolver($resolver);

    WhatsAppService::sendAsync('08123456789', 'Halo Async Fail');

    app()->terminate();

    // Restore original resolver
    Master::setConnectionResolver($originalResolver);
});
