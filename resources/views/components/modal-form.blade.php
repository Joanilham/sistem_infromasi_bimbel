@props(['id', 'title', 'action'])

<div id="{{ $id }}" class="fixed inset-0 z-[60] overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity z-[60]" aria-hidden="true" onclick="document.getElementById('{{ $id }}').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative z-[70] inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-700">
            <form action="{{ $action }}" method="POST" id="form-{{ $id }}" x-data="{ submitting: false }" @submit="submitting = true">
                @csrf
                {{ $method ?? '' }}
                <div class="bg-white dark:bg-slate-900 px-6 pt-6 pb-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white" id="modal-title">{{ $title }}</h3>
                        <button type="button" class="text-slate-400 dark:text-slate-500 hover:text-slate-500 dark:hover:text-slate-300 focus:outline-none" onclick="document.getElementById('{{ $id }}').classList.add('hidden')">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        {{ $slot }}
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-slate-100 dark:border-slate-700">
                    <button type="submit" x-bind:disabled="submitting" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors disabled:opacity-70 disabled:cursor-not-allowed">
                        <span x-show="!submitting">Simpan</span>
                        <span x-show="submitting" x-cloak>Menyimpan...</span>
                    </button>
                    <button type="button" x-bind:disabled="submitting" onclick="document.getElementById('{{ $id }}').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm px-5 py-2.5 bg-white dark:bg-slate-700 text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors disabled:opacity-70 disabled:cursor-not-allowed">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>