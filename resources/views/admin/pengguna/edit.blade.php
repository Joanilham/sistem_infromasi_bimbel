<!-- Modal Edit -->
<x-modal-form id="modal-edit" title="Edit Pengguna" action="">
    <x-slot name="method">
        @method('PUT')
    </x-slot>
    <div>
        <label for="edit_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Pengguna</label>
        <input type="text" name="name" id="edit_name" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
    </div>
    <div>
        <label for="edit_username" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Username</label>
        <input type="text" name="username" id="edit_username" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
    </div>
    <div>
        <label for="edit_email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Email</label>
        <input type="email" name="email" id="edit_email" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
    </div>
    <div>
        <label for="edit_level" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Level / Role</label>
        <select name="level" id="edit_level" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
            <option value="admin">Admin</option>
            <option value="master">Master</option>
        </select>
    </div>
    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-3"><i class="fas fa-info-circle mr-1"></i>Kosongkan password jika tidak ingin mengubahnya.</p>

        <div class="space-y-3">
            <div>
                <label for="edit_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Password Baru (Opsional)</label>
                <input type="password" name="password" id="edit_password" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800 placeholder-slate-400" placeholder="Minimal 8 karakter">
            </div>
            <div>
                <label for="edit_password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="edit_password_confirmation" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800 placeholder-slate-400" placeholder="Ulangi password baru">
            </div>
        </div>
    </div>
</x-modal-form>