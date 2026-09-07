<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PddiktiService
{
    protected static ?array $universitiesCache = null;
    protected static ?array $prodiCache = null;

    /**
     * Ambil path file data universitas
     */
    protected function getUniversitasPath(): string
    {
        return database_path('data/universitas.json');
    }

    /**
     * Ambil path file data program studi
     */
    protected function getProdiPath(): string
    {
        return database_path('data/program_studi.json');
    }

    /**
     * Muat seluruh data universitas
     */
    public function getAllUniversities(): array
    {
        if (self::$universitiesCache !== null) {
            return self::$universitiesCache;
        }

        self::$universitiesCache = Cache::remember('pddikti_universitas_all', 86400, function () {
            $path = $this->getUniversitasPath();
            if (!file_exists($path)) {
                return [];
            }
            $content = file_get_contents($path);
            return json_decode($content, true) ?: [];
        });

        return self::$universitiesCache;
    }

    /**
     * Muat data program studi
     */
    public function getAllProdi(): array
    {
        if (self::$prodiCache !== null) {
            return self::$prodiCache;
        }

        self::$prodiCache = Cache::remember('pddikti_prodi_all', 86400, function () {
            $path = $this->getProdiPath();
            if (!file_exists($path)) {
                return [];
            }
            $content = file_get_contents($path);
            return json_decode($content, true) ?: [];
        });

        return self::$prodiCache;
    }

    /**
     * Cari dan filter universitas dengan pagination
     */
    public function searchUniversities(?string $query = null, ?string $filter = null, int $page = 1, int $perPage = 9): array
    {
        $all = $this->getAllUniversities();
        $jawaRegions = ['DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten'];

        $filtered = array_filter($all, function ($univ) use ($query, $filter, $jawaRegions) {
            // Filter Query Text
            if (!empty($query)) {
                $q = strtolower(trim($query));
                $name = strtolower($univ['name'] ?? '');
                $code = strtolower($univ['code'] ?? '');
                $city = strtolower($univ['city'] ?? '');
                $region = strtolower($univ['region'] ?? '');

                $matched = (str_contains($name, $q) || str_contains($code, $q) || str_contains($city, $q) || str_contains($region, $q));
                if (!$matched) {
                    return false;
                }
            }

            // Filter Kategori
            if (!empty($filter) && $filter !== 'all') {
                $isPoltek = str_contains(strtolower($univ['name'] ?? ''), 'politeknik') || str_contains(strtolower($univ['code'] ?? ''), 'pol');
                $isJawa = in_array($univ['region'] ?? '', $jawaRegions);

                if ($filter === 'ptn' && $isPoltek) {
                    return false;
                }
                if ($filter === 'poltek' && !$isPoltek) {
                    return false;
                }
                if ($filter === 'jawa' && !$isJawa) {
                    return false;
                }
                if ($filter === 'luar_jawa' && $isJawa) {
                    return false;
                }
            }

            return true;
        });

        // Re-index array
        $filtered = array_values($filtered);
        $total = count($filtered);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $currentPage = min(max(1, $page), $totalPages);
        $offset = ($currentPage - 1) * $perPage;

        $items = array_slice($filtered, $offset, $perPage);

        return [
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'last_page' => $totalPages,
            'data' => $items,
        ];
    }

    /**
     * Dapatkan detail kampus beserta program studinya
     */
    public function getUniversityDetail(string $code, ?string $category = null): ?array
    {
        $allUniv = $this->getAllUniversities();
        $targetUniv = null;

        foreach ($allUniv as $u) {
            if (strcasecmp($u['code'] ?? '', $code) === 0 || strcasecmp($u['id'] ?? '', $code) === 0) {
                $targetUniv = $u;
                break;
            }
        }

        if (!$targetUniv) {
            return null;
        }

        $allProdi = $this->getAllProdi();
        $prodiList = $allProdi[$targetUniv['code']] ?? $allProdi[$targetUniv['id']] ?? [];

        if (!empty($category) && $category !== 'all') {
            $catUpper = strtoupper($category);
            $prodiList = array_filter($prodiList, function ($p) use ($catUpper) {
                return strtoupper($p['category'] ?? '') === $catUpper;
            });
            $prodiList = array_values($prodiList);
        }

        // Sort by competition ratio descending (paling ketat lebih dulu)
        usort($prodiList, function ($a, $b) {
            return ($b['competition_ratio'] ?? 0) <=> ($a['competition_ratio'] ?? 0);
        });

        return [
            'university' => $targetUniv,
            'prodi_count' => count($prodiList),
            'prodi' => $prodiList,
        ];
    }

    /**
     * Fallback probe ke API PDDikti pihak ketiga (aman, tidak pernah crash)
     */
    public function probeLiveApi(string $keyword): ?array
    {
        try {
            $response = Http::timeout(2)->get('https://pddikti.fastapicloud.dev/api/search/pt/' . urlencode($keyword));
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            // Silently fail to maintain stability
            Log::debug('PDDikti live API unreachable: ' . $e->getMessage());
        }

        return null;
    }
}
