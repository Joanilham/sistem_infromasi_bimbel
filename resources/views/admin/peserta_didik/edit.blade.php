{{-- Modal Edit Peserta Didik --}}
<div id="modal-edit" class="fixed inset-0 z-[60] overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[60]" onclick="document.getElementById('modal-edit').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="relative z-[70] inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:w-full border border-slate-100 dark:border-slate-700" style="max-width:680px">
            <form method="POST" id="form-edit-peserta">
                @csrf
                @method('PUT')
                {{-- Header modal --}}
                <div class="px-6 pt-5 pb-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Edit Peserta Didik</h3>
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-5 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    {{-- Section: Data Pribadi --}}
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-3">Data Pribadi</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">No. Induk <span class="text-red-500">*</span></label>
                                <input type="text" name="nomor_induk" id="edit_nomor_induk" required class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="jenis_kelamin" id="edit_jenis_kelamin" required class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="edit_tempat_lahir" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Agama</label>
                                <select name="agama" id="edit_agama" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- Pilih --</option>
                                    <option>Islam</option>
                                    <option>Kristen</option>
                                    <option>Katolik</option>
                                    <option>Hindu</option>
                                    <option>Buddha</option>
                                    <option>Konghucu</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">No. Telepon</label>
                                <input type="text" name="no_telepon" id="edit_no_telepon" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" id="edit_alamat_lengkap" rows="2" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Data Akademik --}}
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-3">Data Akademik</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Asal Sekolah <span class="text-red-500">*</span></label>
                                <input type="text" name="asal_sekolah" id="edit_asal_sekolah" required class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Paket Bimbingan <span class="text-red-500">*</span></label>
                                <select name="paket_bimbingan_id" id="edit_paket_bimbingan_id" required class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach($paketBimbingans as $paket)
                                    <option value="{{ $paket->id }}">{{ $paket->nama_paket }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Kelompok Belajar <span class="text-red-500">*</span></label>
                                <select name="kelompok_belajar" id="edit_kelompok_belajar" required class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- Pilih Kelompok --</option>
                                    @foreach($kelompokBelajars as $kb)
                                    <option value="{{ $kb->nama_kelompok }}">{{ $kb->nama_kelompok }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Status</label>
                                <select name="status" id="edit_status" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                    <option value="Keluar">Keluar</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Informasi Dari</label>
                                <input type="text" name="informasi_dari" id="edit_informasi_dari" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    {{-- Section: Data Orang Tua --}}
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-3">Data Orang Tua</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Ayah</label>
                                <input type="text" name="nama_ayah" id="edit_nama_ayah" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Pekerjaan Ayah</label>
                                <input type="text" name="pekerjaan_ayah" id="edit_pekerjaan_ayah" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">No. Telepon Ayah</label>
                                <input type="text" name="no_telepon_ayah" id="edit_no_telepon_ayah" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Ibu</label>
                                <input type="text" name="nama_ibu" id="edit_nama_ibu" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Pekerjaan Ibu</label>
                                <input type="text" name="pekerjaan_ibu" id="edit_pekerjaan_ibu" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">No. Telepon Ibu</label>
                                <input type="text" name="no_telepon_ibu" id="edit_no_telepon_ibu" class="block w-full border border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm text-slate-900 dark:text-white bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-50 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>