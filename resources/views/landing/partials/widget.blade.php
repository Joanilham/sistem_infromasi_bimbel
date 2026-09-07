<!-- Floating WA Widget -->
@if($masterData && $masterData->wa_widget_status && $masterData->wa_number)
    <div class="fixed bottom-6 right-6 z-50 group">
        <!-- Floating Tooltip -->
        <div class="absolute right-full mr-3 top-1/2 -translate-y-1/2 px-3.5 py-1.5 rounded-xl bg-slate-900 text-white text-xs font-semibold whitespace-nowrap shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none hidden sm:block">
            Konsultasi via WhatsApp
            <div class="absolute left-full top-1/2 -translate-y-1/2 -ml-1 border-4 border-transparent border-l-slate-900"></div>
        </div>

        <a href="https://wa.me/{{ $masterData->wa_number }}?text={{ urlencode($masterData->wa_widget_message ?? 'Halo, saya ingin mendapatkan informasi lebih lanjut seputar pendaftaran bimbingan belajar.') }}" 
           target="_blank"
           rel="noopener noreferrer"
           class="relative bg-[#25D366] text-white rounded-2xl w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center shadow-xl shadow-emerald-500/25 transition-all duration-300 hover:scale-110 hover:shadow-2xl hover:shadow-emerald-500/35 active:scale-95 group"
           aria-label="Hubungi WhatsApp">
            <!-- Pulsing Notification Ring -->
            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
            </span>

            <svg class="w-7 h-7 sm:w-8 sm:h-8 fill-current" viewBox="0 0 24 24">
                <path d="M12.013 2.003c-5.506 0-9.98 4.475-9.98 9.982 0 1.761.458 3.473 1.328 4.996L2.016 22l5.161-1.353c1.472.801 3.123 1.222 4.836 1.222 5.503 0 9.977-4.475 9.977-9.982 0-5.507-4.474-9.984-9.977-9.984z"/>
            </svg>
        </a>
    </div>
@endif
