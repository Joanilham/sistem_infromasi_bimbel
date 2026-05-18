<!-- Modal Create -->
<x-modal-form id="modal-create" title="Tambah Periode Baru" action="{{ route('periode.store') }}">
    <div class="pd-grid" style="margin-top: 10px;">
        <div class="pd-field full">
            <label for="tahun_periode">Tahun Periode <span>*</span></label>
            <input type="text" name="tahun_periode" id="tahun_periode" required placeholder="Contoh: 2025/2026">
            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Format: <span class="font-semibold text-indigo-500">YYYY/YYYY</span> — Contoh: <span class="font-semibold">2025/2026</span></p>
        </div>

        <div class="pd-field full" style="justify-content: center; padding-top: 10px;">
            <label style="display: flex; align-items: flex-start; cursor: pointer; gap: 8px; font-weight: 600;">
                <input type="checkbox" name="is_active" id="is_active" value="1" style="width: auto; margin-top: 3px; margin-right: 4px;">
                <div>
                    <span>Periode Aktif</span>
                    <p style="font-size: .7rem; color: #6b7280; font-weight: 500; margin: 2px 0 0 0;">Jadikan sebagai periode yang sedang berjalan saat ini.</p>
                </div>
            </label>
        </div>
    </div>
</x-modal-form>