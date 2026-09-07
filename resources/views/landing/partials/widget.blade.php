<!-- Floating WhatsApp Consultation Widget: Subtle & Professional -->
@if($masterData && $masterData->wa_widget_status && $masterData->wa_number)
    <div class="fixed bottom-6 right-6 z-50 group">
        
        <!-- Clean Tooltip on Hover -->
        <div class="absolute right-full mr-3 top-1/2 -translate-y-1/2 px-3.5 py-1.5 rounded-lg bg-[#141413] text-white text-xs font-medium whitespace-nowrap shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none hidden sm:block">
            Konsultasi WhatsApp
            <div class="absolute left-full top-1/2 -translate-y-1/2 -ml-1 border-4 border-transparent border-l-[#141413]"></div>
        </div>

        <!-- Floating Button -->
        <a href="https://wa.me/{{ $masterData->wa_number }}?text={{ urlencode($masterData->wa_widget_message ?? ('Halo Admin ' . ($masterData->nama_lembaga ?? 'Nivora') . ', saya ingin konsultasi mengenai program bimbingan belajar.')) }}" 
           target="_blank"
           rel="noopener noreferrer"
           class="relative bg-[#141413] hover:bg-[#292524] text-white rounded-xl w-13 h-13 sm:w-14 sm:h-14 flex items-center justify-center border border-[#3E3835] shadow-lg transition-all duration-200 hover:scale-105 active:scale-95"
           aria-label="Konsultasi via WhatsApp">
            
            <!-- Static Notification Indicator (No aggressive pulsing radar) -->
            <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-[#E14D2A] border-2 border-white"></span>
            </span>

            <!-- Clean WhatsApp Vector Icon -->
            <svg class="w-6 h-6 fill-current text-white" viewBox="0 0 24 24">
                <path d="M12.013 2.003c-5.506 0-9.98 4.475-9.98 9.982 0 1.761.458 3.473 1.328 4.996L2.016 22l5.161-1.353c1.472.801 3.123 1.222 4.836 1.222 5.503 0 9.977-4.475 9.977-9.982 0-5.507-4.474-9.984-9.977-9.984z"/>
            </svg>
        </a>

    </div>
@endif
