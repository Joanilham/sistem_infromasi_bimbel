<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\PaketBimbingan;
use App\Models\Testimonial;
use App\Models\Faq;
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
        $galleries = \App\Models\Gallery::inContext()->orderBy('urutan')->get();
        
        return view('admin.landing_page.index', compact('master', 'pakets', 'testimonials', 'faqs', 'galleries'));
    }

    public function storeGallery(Request $request)
    {
        $validated = $request->validate([
            'judul'    => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:50',
            'foto'     => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'urutan'   => 'nullable|integer',
        ]);

        try {
            $validated['kantor_id'] = session('kantor_id');
            if ($request->hasFile('foto')) {
                $validated['foto'] = $this->compressAndStore($request->file('foto'), 'gallery', 75);
            }

            \App\Models\Gallery::create($validated);
            return back()->with('success', 'Foto berhasil ditambahkan ke gallery.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunggah foto.');
        }
    }

    public function destroyGallery(\App\Models\Gallery $gallery)
    {
        try {
            if ($gallery->foto) Storage::disk('public')->delete($gallery->foto);
            $gallery->delete();
            return back()->with('success', 'Foto berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus foto.');
        }
    }

    /**
     * Update General Settings (Master table)
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'nama_lembaga'   => 'nullable|string|max:255',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'wa_number'      => 'nullable|string|max:20',
            'wa_widget_status' => 'nullable|boolean',
            'wa_widget_message' => 'nullable|string|max:255',
            'instagram_url'  => 'nullable|string|max:255',
            'hero_title'     => 'nullable|string|max:255',
            'hero_subtitle'  => 'nullable|string',
            'hero_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'hero_overlay_opacity' => 'nullable|integer|min:0|max:100',
            'tentang_kami'   => 'nullable|string',
            'stats_siswa'    => 'nullable|string|max:50',
            'stats_tutor'    => 'nullable|string|max:50',
            'stats_modul'    => 'nullable|string|max:50',
            'stats_kepuasan' => 'nullable|string|max:50',
        ]);

        try {
            $master = Master::first() ?? new Master();
            $validated['wa_widget_status'] = $request->has('wa_widget_status');

            if ($request->hasFile('logo')) {
                if ($master->logo) Storage::disk('public')->delete($master->logo);
                $validated['logo'] = $this->compressAndStore($request->file('logo'), 'logos', 80);
            }

            if ($request->hasFile('hero_image')) {
                if ($master->hero_image) Storage::disk('public')->delete($master->hero_image);
                $validated['hero_image'] = $this->compressAndStore($request->file('hero_image'), 'landing', 70);
            }

            if (isset($validated['hero_overlay_opacity'])) {
                $validated['hero_overlay_opacity'] = $validated['hero_overlay_opacity'] / 100;
            }

            $master->fill($validated);
            $master->save();

            return back()->with('success', 'Konfigurasi Landing Page berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update Landing Page Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui konfigurasi.');
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
            'foto'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            if ($request->hasFile('foto')) {
                $validated['foto'] = $this->compressAndStore($request->file('foto'), 'testimonials', 80);
            }

            Testimonial::create($validated);
            return back()->with('success', 'Testimonial berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Store Testimonial Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan testimonial.');
        }
    }

    public function destroyTestimonial(Testimonial $testimonial)
    {
        if ($testimonial->foto) Storage::disk('public')->delete($testimonial->foto);
        $testimonial->delete();
        return back()->with('success', 'Testimonial berhasil dihapus.');
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
        return back()->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function destroyFaq(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ berhasil dihapus.');
    }
}
