<?php

namespace App\Http\Controllers\Admin;
use App\Models\System\Gallery;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Master;
use App\Models\Akademik\PaketBimbingan;
use App\Models\System\Testimonial;
use App\Models\System\Faq;
use App\Models\System\Feature;
use App\Models\System\MitraLogo;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    use HandlesImageUpload;

    public function index()
    {
        $master = Master::first() ?? new Master();
        $pakets = PaketBimbingan::orderBy('urutan')->get();
        $testimonials = Testimonial::latest()->get();
        $faqs = Faq::orderBy('urutan')->get();
        $galleries = \App\Models\System\Gallery::inContext()->orderBy('urutan')->get();
        $features = Feature::orderBy('order_num')->get();
        $mitras = MitraLogo::orderBy('order_num')->get();
        
        return view('admin.landing_page.index', compact('master', 'pakets', 'testimonials', 'faqs', 'galleries', 'features', 'mitras'));
    }

    public function storeGallery(Request $request)
    {
        $validated = $request->validate([
            'judul'    => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:50',
            'foto'     => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'urutan'   => 'nullable|integer',
        ]);

        try {
            $validated['kantor_id'] = session('kantor_id');
            if ($request->hasFile('foto')) {
                $validated['foto'] = $this->compressAndStore($request->file('foto'), 'gallery', 75);
            }

            \App\Models\System\Gallery::create($validated);
            $this->clearLandingCache();
            return back()->with('success', 'Foto berhasil ditambahkan ke gallery.')->with('active_tab', 'gallery');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunggah foto.')->with('active_tab', 'gallery');
        }
    }

    public function destroyGallery(\App\Models\System\Gallery $gallery)
    {
        try {
            if ($gallery->foto) Storage::disk('public')->delete($gallery->foto);
            $gallery->delete();
            $this->clearLandingCache();
            return back()->with('success', 'Foto berhasil dihapus.')->with('active_tab', 'gallery');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus foto.')->with('active_tab', 'gallery');
        }
    }

    /**
     * Update General Settings (Master table)
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'nama_lembaga'   => 'nullable|string|max:255',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'wa_number'      => 'nullable|string|max:20',
            'wa_widget_status' => 'nullable|boolean',
            'wa_widget_message' => 'nullable|string|max:255',
            'instagram_url'  => 'nullable|string|max:255',
            'hero_title'     => 'nullable|string|max:255',
            'hero_subtitle'  => 'nullable|string',
            'hero_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'hero_image_2'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'hero_image_3'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'hero_overlay_opacity' => 'nullable|integer|min:0|max:100',
            'tentang_kami'   => 'nullable|string',
            'stats_siswa'    => 'nullable|string|max:50',
            'stats_tutor'    => 'nullable|string|max:50',
            'stats_modul'    => 'nullable|string|max:50',
            'stats_kepuasan' => 'nullable|string|max:50',
            'hero_cta_text'  => 'nullable|string|max:255',
            'hero_cta_link'  => 'nullable|string|max:255',
            'facebook_url'   => 'nullable|string|max:255',
            'youtube_url'    => 'nullable|string|max:255',
            'tiktok_url'     => 'nullable|string|max:255',
            'alamat_lembaga' => 'nullable|string',
            'email_kontak'   => 'nullable|email|max:255',
            'telepon_kantor' => 'nullable|string|max:50',
            'jam_layanan'    => 'nullable|string|max:255',
            'active_tab'     => 'nullable|string|max:50',
        ]);

        try {
            $master = Master::first() ?? new Master();
            
            if ($request->has('wa_widget_form_submitted')) {
                $validated['wa_widget_status'] = $request->has('wa_widget_status');
            } else {
                unset($validated['wa_widget_status']);
            }

            if ($request->hasFile('logo')) {
                if ($master->logo) Storage::disk('public')->delete($master->logo);
                $validated['logo'] = $this->compressAndStore($request->file('logo'), 'logos', 80);
            } else {
                unset($validated['logo']);
            }

            // Handle Slide 1
            if ($request->hasFile('hero_image')) {
                if ($master->hero_image) Storage::disk('public')->delete($master->hero_image);
                $validated['hero_image'] = $this->compressAndStore($request->file('hero_image'), 'landing', 75);
            } elseif ($request->boolean('delete_hero_image_1')) {
                if ($master->hero_image) Storage::disk('public')->delete($master->hero_image);
                $validated['hero_image'] = null;
            } else {
                unset($validated['hero_image']);
            }

            // Handle Slide 2
            if ($request->hasFile('hero_image_2')) {
                if ($master->hero_image_2) Storage::disk('public')->delete($master->hero_image_2);
                $validated['hero_image_2'] = $this->compressAndStore($request->file('hero_image_2'), 'landing', 75);
            } elseif ($request->boolean('delete_hero_image_2')) {
                if ($master->hero_image_2) Storage::disk('public')->delete($master->hero_image_2);
                $validated['hero_image_2'] = null;
            } else {
                unset($validated['hero_image_2']);
            }

            // Handle Slide 3
            if ($request->hasFile('hero_image_3')) {
                if ($master->hero_image_3) Storage::disk('public')->delete($master->hero_image_3);
                $validated['hero_image_3'] = $this->compressAndStore($request->file('hero_image_3'), 'landing', 75);
            } elseif ($request->boolean('delete_hero_image_3')) {
                if ($master->hero_image_3) Storage::disk('public')->delete($master->hero_image_3);
                $validated['hero_image_3'] = null;
            } else {
                unset($validated['hero_image_3']);
            }

            if (isset($validated['hero_overlay_opacity'])) {
                $validated['hero_overlay_opacity'] = $validated['hero_overlay_opacity'] / 100;
            }

            $activeTab = $request->input('active_tab', 'general');
            unset($validated['active_tab'], $validated['wa_widget_form_submitted']);

            $master->fill($validated);
            $master->save();

            $this->clearLandingCache();

            return back()->with('success', 'Konfigurasi Landing Page berhasil diperbarui.')->with('active_tab', $activeTab);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update Landing Page Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui konfigurasi.')->with('active_tab', $request->input('active_tab', 'general'));
        }
    }

    /**
     * Testimonial Management
     */
    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'posisi' => 'nullable|string|max:255',
            'ulasan' => 'required|string',
            'bintang'=> 'required|integer|min:1|max:5',
            'foto'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            if ($request->hasFile('foto')) {
                $validated['foto'] = $this->compressAndStore($request->file('foto'), 'testimonials', 80);
            }

            Testimonial::create($validated);
            $this->clearLandingCache();
            return back()->with('success', 'Testimonial berhasil ditambahkan.')->with('active_tab', 'testimonials');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Store Testimonial Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan testimonial.')->with('active_tab', 'testimonials');
        }
    }

    public function destroyTestimonial(Testimonial $testimonial)
    {
        if ($testimonial->foto) Storage::disk('public')->delete($testimonial->foto);
        $testimonial->delete();
        $this->clearLandingCache();
        return back()->with('success', 'Testimonial berhasil dihapus.')->with('active_tab', 'testimonials');
    }

    /**
     * FAQ Management
     */
    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban'    => 'required|string',
            'urutan'     => 'nullable|integer',
        ]);

        Faq::create($validated);
        $this->clearLandingCache();
        return back()->with('success', 'FAQ berhasil ditambahkan.')->with('active_tab', 'faq');
    }

    public function destroyFaq(Faq $faq)
    {
        $faq->delete();
        $this->clearLandingCache();
        return back()->with('success', 'FAQ berhasil dihapus.')->with('active_tab', 'faq');
    }

    /**
     * Keunggulan Management
     */
    public function storeFeature(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $this->compressAndStore($request->file('icon'), 'features', 80);
        }

        Feature::create($validated);
        $this->clearLandingCache();
        return back()->with('success', 'Keunggulan berhasil ditambahkan.')->with('active_tab', 'features');
    }

    public function destroyFeature(Feature $feature)
    {
        if ($feature->icon) Storage::disk('public')->delete($feature->icon);
        $feature->delete();
        $this->clearLandingCache();
        return back()->with('success', 'Keunggulan berhasil dihapus.')->with('active_tab', 'features');
    }

    /**
     * Mitra Logo Management
     */
    public function storeMitra(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp,svg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->compressAndStore($request->file('logo'), 'mitras', 80);
        }

        MitraLogo::create($validated);
        $this->clearLandingCache();
        return back()->with('success', 'Logo Mitra berhasil ditambahkan.')->with('active_tab', 'mitra');
    }

    public function destroyMitra(MitraLogo $mitra)
    {
        if ($mitra->logo) Storage::disk('public')->delete($mitra->logo);
        $mitra->delete();
        $this->clearLandingCache();
        return back()->with('success', 'Logo Mitra berhasil dihapus.')->with('active_tab', 'mitra');
    }
    private function clearLandingCache()
    {
        \Illuminate\Support\Facades\Cache::forget('welcome_page_data');
        \Illuminate\Support\Facades\Cache::forget('global_master');
    }
}


