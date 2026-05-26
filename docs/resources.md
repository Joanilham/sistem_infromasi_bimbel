# `resources/` — Rincian

Folder `resources/` berisi sumber frontend dan template Blade.

- `css/` : Sumber styling (Tailwind config dan file CSS utama). Jalankan `npm run dev` untuk mem-build.
- `js/` : Script frontend (komunikasi API dengan `axios`, interaktivitas dengan `alpine.js`).
- `views/` : Blade templates. Struktur umum:
  - `layouts/` : Template dasar (master layout, sidebar, header)
  - `components/` : Partial blade untuk komponen UI (alerts, modals)
  - `auth/` : Halaman login/register/reset
  - `admin/`, `guru/`, `siswa/`, `keuangan/` : Domain-specific views

Tip editing views:

- Gunakan `@include` untuk komponen yang di-reuse.
- Simpan scoped CSS kecil di file blade jika perlu, tapi prefer external CSS di `resources/css`.
