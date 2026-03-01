<!-- Modal Create -->
<x-modal-form id="modal-create" title="Tambah Pengguna Baru" action="{{ route('pengguna.store') }}">
    <div>
        <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Pengguna</label>
        <input type="text" name="name" id="name" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="Misal: John Doe">
    </div>
    <div>
        <label for="username" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Username</label>
        <input type="text" name="username" id="username" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="johndoe123">
    </div>
    <div>
        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Email</label>
        <input type="email" name="email" id="email" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="johndoe@example.com">
    </div>
    <div>
        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Password</label>
        <input type="password" name="password" id="password" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="Minimal 8 karakter">
    </div>
    <div>
        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800" placeholder="Ulangi password di atas">
    </div>
    <div>
        <label for="level" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Level / Role</label>
        <select name="level" id="level" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
            <option value="" disabled selected>Pilih Level</option>
            <option value="admin">Admin</option>
            <option value="master">Master</option>
        </select>
    </div>
</x-modal-form>