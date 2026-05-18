<!-- Modal Edit -->
<x-modal-form id="modal-edit" title="Edit Pengguna" action="">
    <x-slot name="method">
        @method('PUT')
    </x-slot>
    <div class="pd-grid" style="margin-top: 10px;">
        <div class="pd-field">
            <label for="edit_name">Nama Pengguna <span>*</span></label>
            <input type="text" name="name" id="edit_name" required placeholder="Misal: John Doe">
        </div>

        <div class="pd-field">
            <label for="edit_username">Username <span>*</span></label>
            <input type="text" name="username" id="edit_username" required placeholder="johndoe123">
        </div>

        <div class="pd-field">
            <label for="edit_email">Email <span>*</span></label>
            <input type="email" name="email" id="edit_email" required placeholder="johndoe@example.com">
        </div>

        <div class="pd-field">
            <label for="edit_level">Level / Role <span>*</span></label>
            <select name="level" id="edit_level" required>
                <option value="Super Admin">Super Admin</option>
                <option value="Admin">Admin</option>
            </select>
        </div>

        <div class="pd-field full" style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e5e7eb;">
            <p style="font-size: .78rem; color: #6b7280; font-weight: 500; margin-bottom: 12px;"><i class="fas fa-info-circle mr-1"></i>Kosongkan password jika tidak ingin mengubahnya.</p>
            <div class="pd-grid">
                <div class="pd-field">
                    <label for="edit_password">Password Baru (Opsional)</label>
                    <input type="password" name="password" id="edit_password" placeholder="Minimal 8 karakter">
                </div>
                <div class="pd-field">
                    <label for="edit_password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="edit_password_confirmation" placeholder="Ulangi password baru">
                </div>
            </div>
        </div>
    </div>
</x-modal-form>