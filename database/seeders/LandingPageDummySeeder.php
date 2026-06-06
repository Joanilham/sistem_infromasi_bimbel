<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\System\Feature;
use App\Models\System\MitraLogo;

class LandingPageDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add 3 Dummy Features
        Feature::truncate();
        Feature::create([
            'title' => 'Fasilitas Belajar Nyaman',
            'description' => 'Ruangan ber-AC dan fasilitas modern yang mendukung konsentrasi belajar maksimal sehingga siswa lebih fokus.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
            'order_num' => 1,
            'is_active' => true,
        ]);
        
        Feature::create([
            'title' => 'Tutor Berpengalaman',
            'description' => 'Diajar oleh praktisi dan akademisi terbaik lulusan universitas terkemuka yang siap membimbing langkah Anda.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>',
            'order_num' => 2,
            'is_active' => true,
        ]);

        Feature::create([
            'title' => 'Tryout & Evaluasi CBT',
            'description' => 'Simulasi ujian berbasis komputer (CBT) yang akurat menyerupai ujian aslinya dengan sistem grading instan.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
            'order_num' => 3,
            'is_active' => true,
        ]);

        // Create Dummy Gurus by setting 4 Gurus as featured
        User::where('level', 'Guru')->limit(4)->update(['is_featured' => true]);

        // Add Mitra Logos
        MitraLogo::truncate();
        
        MitraLogo::create([
            'name' => 'Telkom University',
            'logo' => 'landing_page/mitra_1.png',
            'order_num' => 1,
            'is_active' => true,
        ]);

        MitraLogo::create([
            'name' => 'Kampus Merdeka',
            'logo' => 'landing_page/mitra_2.png',
            'order_num' => 2,
            'is_active' => true,
        ]);

        MitraLogo::create([
            'name' => 'Kemdikbud',
            'logo' => 'landing_page/mitra_3.png',
            'order_num' => 3,
            'is_active' => true,
        ]);

        MitraLogo::create([
            'name' => 'LPDP',
            'logo' => 'landing_page/mitra_4.png',
            'order_num' => 4,
            'is_active' => true,
        ]);
    }
}
