<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Kantor;
use App\Models\Periode;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class BenchmarkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:benchmark 
                            {url=http://nginx : Target URL, "multi" (public), "all" (authenticated), or "cbt" (student exam simulation)} 
                            {--requests=60 : Total number of requests to send} 
                            {--concurrency=6 : Number of concurrent requests}
                            {--role=superadmin : Authenticate as specific role (superadmin, admin, guru, siswa)}
                            {--host=http://nginx : Base host URL to override http://nginx in scenario tests}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a local high-performance load test (Uji Beban) natively in Laravel supporting custom base hosts and Nginx ports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $urlArg = $this->argument('url');
        $totalRequests = (int) $this->option('requests');
        $concurrency = (int) $this->option('concurrency');
        $roleOption = strtolower($this->option('role'));
        $host = rtrim($this->option('host'), '/');

        // Handle the special CBT exam simulation scenario
        if (strtolower($urlArg) === 'cbt') {
            $roleOption = 'siswa';
        }

        // Normalize roles names for database queries
        $roleMap = [
            'superadmin' => 'Super Admin',
            'admin' => 'Admin',
            'guru' => 'Guru',
            'siswa' => 'Siswa'
        ];

        $targetRole = $roleMap[$roleOption] ?? 'Super Admin';

        $urls = [];
        $cookieHeader = null;

        if (strtolower($urlArg) === 'all' || strtolower($urlArg) === 'cbt') {
            $this->info("🔑 Establishing authenticated session for role: [<comment>{$targetRole}</comment>]...");
            
            // 1. Get or create user for this specific role
            $user = User::whereRaw('LOWER(level) = ?', [strtolower($targetRole)])->first();
            if (!$user) {
                $user = User::create([
                    'name' => "Load Test {$targetRole}",
                    'email' => str_replace(' ', '', strtolower($targetRole)) . "@geniusedu.com",
                    'password' => bcrypt('secret123'),
                    'level' => $targetRole,
                    'is_active' => true
                ]);
            }

            // 2. Get or create Kantor and Periode contexts
            $kantor = Kantor::first() ?? Kantor::create(['nama_kantor' => 'Kantor Pusat', 'alamat' => 'Kota']);
            $periode = Periode::first() ?? Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);

            // 3. Programmatically start session and inject state
            $sessionStore = app('session')->driver();
            $sessionStore->start();
            $sessionStore->put(Auth::getName(), $user->getKey());
            $sessionStore->put('kantor_id', $kantor->id);
            $sessionStore->put('periode_id', $periode->id);
            $sessionStore->save();

            $sessionId = $sessionStore->getId();
            $encryptedSessionId = Crypt::encryptString($sessionId);
            
            // Build the cookie header
            $cookieName = config('session.cookie');
            $cookieHeader = "Cookie: {$cookieName}=" . rawurlencode($encryptedSessionId);

            $this->line("Session established for: <comment>{$user->email}</comment>");
            $this->line("Context: Kantor: <comment>{$kantor->nama_kantor}</comment>, Periode: <comment>{$periode->tahun_periode}</comment>");
            $this->line("");

            // Define targeted URLs based on selected role / CBT scenario
            if (strtolower($urlArg) === 'cbt') {
                $urls = [
                    "{$host}/siswa/dashboard",       // Siswa Dashboard
                    "{$host}/siswa/ujian",           // Siswa CBT Exam Center (Daftar Ujian)
                    "{$host}/siswa/jadwal",          // Siswa Schedule Page
                    "{$host}/siswa/profile",         // Siswa Profile Page
                ];
            } else {
                if (in_array($roleOption, ['superadmin', 'admin'])) {
                    $urls = [
                        "{$host}/",                      // Landing Page (Public)
                        "{$host}/login",                 // Login Page (Public)
                        "{$host}/dashboard",             // Admin Dashboard (Authenticated)
                        "{$host}/admin/pendaftaran",     // Student Registrations (Authenticated)
                        "{$host}/peserta-didik",         // Active Students list (Authenticated + Contextual)
                        "{$host}/admin/jadwal",          // Schedule Board (Authenticated + Contextual)
                    ];
                } elseif ($roleOption === 'guru') {
                    $urls = [
                        "{$host}/",                      // Landing Page (Public)
                        "{$host}/login",                 // Login Page (Public)
                        "{$host}/guru/dashboard",        // Guru Dashboard (Authenticated)
                        "{$host}/guru/profile",          // Guru Profile Page (Authenticated)
                        "{$host}/guru/jadwal",           // Guru Schedule Page (Authenticated)
                        "{$host}/guru/bank-soal",        // Guru Question Bank (Authenticated)
                    ];
                } elseif ($roleOption === 'siswa') {
                    $urls = [
                        "{$host}/",                      // Landing Page (Public)
                        "{$host}/login",                 // Login Page (Public)
                        "{$host}/siswa/dashboard",       // Siswa Dashboard (Authenticated)
                        "{$host}/siswa/profile",         // Siswa Profile Page (Authenticated)
                        "{$host}/siswa/qr",              // Siswa dynamic QR Absensi (Authenticated)
                        "{$host}/siswa/jadwal",          // Siswa Schedule Page (Authenticated)
                    ];
                }
            }
        } elseif (strtolower($urlArg) === 'multi') {
            $urls = [
                "{$host}/",
                "{$host}/login",
                "{$host}/forgot-password"
            ];
        } else {
            $urls = array_map('trim', explode(',', $urlArg));
        }

        $this->info("🚀 Starting Load Test (Uji Beban)...");
        $this->line("Targeted Routes:");
        foreach ($urls as $u) {
            $isAuth = ($cookieHeader && !in_array($u, ["{$host}/", "{$host}/login", "{$host}/forgot-password"]));
            $authLabel = $isAuth ? " [<comment>Authenticated as {$targetRole}</comment>]" : "";
            $this->line("  - <info>{$u}</info>{$authLabel}");
        }
        $this->line("Total Requests: <info>{$totalRequests}</info>");
        $this->line("Concurrency:    <info>{$concurrency}</info>");
        $this->line("");

        if ($totalRequests <= 0 || $concurrency <= 0) {
            $this->error("Requests and concurrency must be positive integers.");
            return 1;
        }

        if ($concurrency > $totalRequests) {
            $concurrency = $totalRequests;
        }

        $latencies = [];
        $successCount = 0;
        $failureCount = 0;
        $statusCodes = [];
        
        // Per-URL stats tracking
        $urlStats = [];
        foreach ($urls as $u) {
            $urlStats[$u] = [
                'requests' => 0,
                'success' => 0,
                'fail' => 0,
                'latencies' => []
            ];
        }

        $startTime = microtime(true);

        $bar = $this->output->createProgressBar($totalRequests);
        $bar->start();

        $batches = ceil($totalRequests / $concurrency);

        for ($b = 0; $b < $batches; $b++) {
            $currentBatchSize = min($concurrency, $totalRequests - ($b * $concurrency));
            $mh = curl_multi_init();
            $handles = [];

            for ($i = 0; $i < $currentBatchSize; $i++) {
                $reqIndex = ($b * $concurrency) + $i;
                $targetUrl = $urls[$reqIndex % count($urls)];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $targetUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                
                // Add session cookie for authenticated routes
                if ($cookieHeader && !in_array($targetUrl, ["{$host}/", "{$host}/login"])) {
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [$cookieHeader]);
                }
                
                $handles[] = [
                    'ch' => $ch,
                    'url' => $targetUrl,
                    'start' => microtime(true)
                ];
                curl_multi_add_handle($mh, $ch);
            }

            $running = null;
            do {
                curl_multi_exec($mh, $running);
                curl_multi_select($mh);
            } while ($running > 0);

            foreach ($handles as $h) {
                $ch = $h['ch'];
                $targetUrl = $h['url'];
                $requestTime = microtime(true) - $h['start'];
                
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $latencyMs = $requestTime * 1000;

                $latencies[] = $latencyMs;

                $isSuccess = ($httpCode >= 200 && $httpCode < 400);
                if ($isSuccess) {
                    $successCount++;
                } else {
                    $failureCount++;
                }

                $statusCodes[$httpCode] = ($statusCodes[$httpCode] ?? 0) + 1;

                $urlStats[$targetUrl]['requests']++;
                if ($isSuccess) {
                    $urlStats[$targetUrl]['success']++;
                } else {
                    $urlStats[$targetUrl]['fail']++;
                }
                $urlStats[$targetUrl]['latencies'][] = $latencyMs;

                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
                
                $bar->advance();
            }

            curl_multi_close($mh);
        }

        $bar->finish();
        $this->line("\n");

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;

        // Statistics computations
        sort($latencies);
        $minLatency = min($latencies);
        $maxLatency = max($latencies);
        $avgLatency = array_sum($latencies) / count($latencies);
        
        $p50 = $latencies[floor(count($latencies) * 0.50)];
        $p90 = $latencies[floor(count($latencies) * 0.90)];
        $p95 = $latencies[floor(count($latencies) * 0.95)];
        $p99 = $latencies[floor(count($latencies) * 0.99)];

        $rps = $totalRequests / $totalTime;

        // Display OVERALL results
        $this->info("📊 RINGKASAN UJI BEBAN (OVERALL RESULTS)");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Time Taken', number_format($totalTime, 4) . ' seconds'],
                ['Successful Requests', "<info>{$successCount}</info>"],
                ['Failed Requests', $failureCount > 0 ? "<error>{$failureCount}</error>" : $failureCount],
                ['Requests Per Second (RPS)', number_format($rps, 2) . ' req/s'],
                ['Average Response Time', number_format($avgLatency, 2) . ' ms'],
                ['Min Response Time', number_format($minLatency, 2) . ' ms'],
                ['Max Response Time', number_format($maxLatency, 2) . ' ms'],
                ['P50 (Median)', number_format($p50, 2) . ' ms'],
                ['P90 Percentile', number_format($p90, 2) . ' ms'],
                ['P95 Percentile', number_format($p95, 2) . ' ms'],
                ['P99 Percentile', number_format($p99, 2) . ' ms'],
            ]
        );

        // Display PER-ROUTE detailed breakdown
        $this->info("📈 PER-ROUTE PERFORMANCE BREAKDOWN");
        $routeRows = [];
        foreach ($urlStats as $url => $stats) {
            $reqs = $stats['requests'];
            if ($reqs > 0) {
                $avg = array_sum($stats['latencies']) / $reqs;
                $min = min($stats['latencies']);
                $max = max($stats['latencies']);
                $success = $stats['success'];
                $fail = $stats['fail'];
                
                $routeRows[] = [
                    $url,
                    $reqs,
                    "<info>{$success}</info> / " . ($fail > 0 ? "<error>{$fail}</error>" : $fail),
                    number_format($avg, 2) . ' ms',
                    number_format($min, 2) . ' ms',
                    number_format($max, 2) . ' ms'
                ];
            }
        }
        $this->table(
            ['Route / URL', 'Total Reqs', 'Success/Fail', 'Avg Latency', 'Min Latency', 'Max Latency'],
            $routeRows
        );

        $this->line("HTTP Status Codes distribution:");
        foreach ($statusCodes as $code => $count) {
            $label = $code >= 200 && $code < 400 ? 'info' : 'error';
            $this->line("  [<{$label}>{$code}</{$label}>]: {$count} requests");
        }

        return 0;
    }
}
