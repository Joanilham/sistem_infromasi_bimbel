@extends('layouts.siswa')

@section('title', 'Edit Profil')

@section('content')
<div style="max-width: 640px; margin: 0 auto;">

    <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 8px;">Pengaturan Akun</h2>
    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 28px;">Kelola informasi profil, foto, dan kata sandi Anda.</p>

    @if(session('success'))
        <div style="padding: 16px 20px; border-radius: 16px; margin-bottom: 24px; font-size: 0.9rem; font-weight: 600; display: flex; gap: 12px; align-items: center; background: #E6F8F1; color: #01B574; border: 1px solid #A7F3D0;">
            <svg style="width: 24px; height: 24px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if($errors->any() && !session('success'))
        <div style="padding: 16px 20px; border-radius: 16px; margin-bottom: 24px; font-size: 0.9rem; font-weight: 600; display: flex; gap: 12px; align-items: center; background: #FDE8E8; color: #EE5D50; border: 1px solid #FECACA;">
            <svg style="width: 24px; height: 24px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <div>Mohon periksa kembali form pengisian di bawah.</div>
        </div>
    @endif

    {{-- Foto Profil --}}
    <div style="background: var(--white); border-radius: 24px; padding: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border-color); margin-bottom: 24px;" x-data="{ preview: null }">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: #E6F8F1; color: #01B574; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 28px; height: 28px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <div style="font-size: 1.2rem; font-weight: 800; color: var(--text-main);">Foto Profil</div>
                <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500; margin-top: 4px;">Ubah foto profil Anda</div>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
            {{-- Avatar --}}
            <div style="width: 96px; height: 96px; border-radius: 20px; overflow: hidden; border: 3px solid var(--border-color); box-shadow: 0 4px 12px rgba(0,0,0,0.08); flex-shrink: 0;">
                @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;" x-show="!preview">
                @else
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #4318FF, #8F9BFA); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 800;" x-show="!preview">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <img :src="preview" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;" x-show="preview" x-cloak>
            </div>

            <div style="flex: 1;">
                <form method="POST" action="{{ route('siswa.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <label style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--primary); color: white; font-size: 0.9rem; font-weight: 700; border-radius: 14px; cursor: pointer; transition: all 0.2s; box-shadow: 0 6px 16px rgba(67,24,255,0.2); font-family: inherit;">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Pilih Foto
                        <input type="file" name="photo" accept="image/*" style="display:none;" @change="const file = $event.target.files[0]; if(file) { preview = URL.createObjectURL(file); $el.closest('form').submit(); }">
                    </label>
                </form>

                @if($user->photo)
                <form method="POST" action="{{ route('siswa.profile.update') }}" style="margin-top: 8px;">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="remove_photo" value="1">
                    <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: none; border: none; color: #EE5D50; font-size: 0.8rem; font-weight: 600; cursor: pointer; font-family: inherit; border-radius: 8px; transition: background 0.2s;">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus Foto
                    </button>
                </form>
                @endif

                @error('photo') <span style="color: #EE5D50; font-size: 0.8rem; font-weight: 600; margin-top: 6px; display: block;">{{ $message }}</span> @enderror
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 8px;">JPG, PNG atau WebP. Maks 2MB.</p>
            </div>
        </div>
    </div>

    {{-- Form Data Pribadi --}}
    <div style="background: var(--white); border-radius: 24px; padding: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border-color); margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 30px;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: #F4F7FE; color: var(--primary); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 28px; height: 28px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </div>
            <div>
                <div style="font-size: 1.2rem; font-weight: 800; color: var(--text-main);">Informasi Akun</div>
                <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500; margin-top: 4px;">Perbarui nama dan alamat email.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('siswa.profile.update') }}">
            @csrf
            @method('patch')

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 16px 20px; border-radius: 16px; border: 1px solid var(--border-color); background: var(--bg-body); font-size: 0.95rem; color: var(--text-main); font-weight: 500; font-family: inherit; transition: all 0.2s; outline: none;">
                @error('name') <span style="color: #EE5D50; font-size: 0.8rem; font-weight: 600; margin-top: 6px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">Alamat Email / Username</label>
                <input type="text" name="email" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 16px 20px; border-radius: 16px; border: 1px solid var(--border-color); background: var(--bg-body); font-size: 0.95rem; color: var(--text-main); font-weight: 500; font-family: inherit; transition: all 0.2s; outline: none;">
                @error('email') <span style="color: #EE5D50; font-size: 0.8rem; font-weight: 600; margin-top: 6px; display: block;">{{ $message }}</span> @enderror
            </div>

            <button type="submit" style="width: 100%; padding: 18px; border-radius: 16px; border: none; background: var(--primary); color: white; font-size: 1rem; font-weight: 800; font-family: inherit; cursor: pointer; transition: all 0.2s; box-shadow: 0 10px 20px rgba(67, 24, 255, 0.2); display: flex; align-items: center; justify-content: center; gap: 10px;">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Form Ubah Password --}}
    <div style="background: var(--white); border-radius: 24px; padding: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border-color); margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 30px;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: #FFF8D6; color: #FFCE20; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 28px; height: 28px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            </div>
            <div>
                <div style="font-size: 1.2rem; font-weight: 800; color: var(--text-main);">Ubah Kata Sandi</div>
                <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500; margin-top: 4px;">Pastikan akun Anda tetap aman.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('siswa.profile.update') }}">
            @csrf
            @method('patch')

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required style="width: 100%; padding: 16px 20px; border-radius: 16px; border: 1px solid var(--border-color); background: var(--bg-body); font-size: 0.95rem; color: var(--text-main); font-weight: 500; font-family: inherit; transition: all 0.2s; outline: none;">
                @error('current_password') <span style="color: #EE5D50; font-size: 0.8rem; font-weight: 600; margin-top: 6px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">Kata Sandi Baru</label>
                <input type="password" name="password" required style="width: 100%; padding: 16px 20px; border-radius: 16px; border: 1px solid var(--border-color); background: var(--bg-body); font-size: 0.95rem; color: var(--text-main); font-weight: 500; font-family: inherit; transition: all 0.2s; outline: none;">
                @error('password') <span style="color: #EE5D50; font-size: 0.8rem; font-weight: 600; margin-top: 6px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required style="width: 100%; padding: 16px 20px; border-radius: 16px; border: 1px solid var(--border-color); background: var(--bg-body); font-size: 0.95rem; color: var(--text-main); font-weight: 500; font-family: inherit; transition: all 0.2s; outline: none;">
            </div>

            <button type="submit" style="width: 100%; padding: 18px; border-radius: 16px; border: none; background: var(--text-main); color: white; font-size: 1rem; font-weight: 800; font-family: inherit; cursor: pointer; transition: all 0.2s; box-shadow: 0 10px 20px rgba(43, 54, 116, 0.2); display: flex; align-items: center; justify-content: center; gap: 10px;">
                Perbarui Kata Sandi
            </button>
        </form>
    </div>

</div>
@endsection
