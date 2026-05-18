<!-- Modal Create -->
<x-modal-form id="modal-create" title="Tambah Pengguna Baru" action="{{ route('pengguna.store') }}">
    <div class="pd-grid" style="margin-top: 10px;">
        <div class="pd-field">
            <label for="name">Nama Pengguna <span>*</span></label>
            <input type="text" name="name" id="name" required placeholder="Misal: John Doe">
        </div>

        <div class="pd-field">
            <label for="username">Username <span>*</span></label>
            <input type="text" name="username" id="username" required placeholder="johndoe123">
        </div>

        <div class="pd-field">
            <label for="email">Email <span>*</span></label>
            <input type="email" name="email" id="email" required placeholder="johndoe@example.com">
        </div>

        <div class="pd-field">
            <label for="level">Level / Role <span>*</span></label>
            <select name="level" id="level" required>
                <option value="" disabled selected>Pilih Level</option>
                <option value="Super Admin">Super Admin</option>
                <option value="Admin">Admin</option>
            </select>
        </div>

        <div class="pd-field">
            <label for="password">Password <span>*</span></label>
            <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter">
        </div>

        <div class="pd-field">
            <label for="password_confirmation">Konfirmasi Password <span>*</span></label>
            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password di atas">
        </div>
    </div>
</x-modal-form>