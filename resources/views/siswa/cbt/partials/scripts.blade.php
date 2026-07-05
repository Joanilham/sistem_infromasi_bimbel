<script>
// Timer Logic
let sisaWaktu = {{ $sisaWaktu }};
const totalWaktu = {{ $sesi->ujian->durasi * 60 }};
const timerText = document.getElementById('timer-text');
const timerDisplay = document.getElementById('timer-display');
const timerBar = document.getElementById('timer-bar');
const timerProgress = document.getElementById('timer-progress');

function updateTimer() {
    if (sisaWaktu <= 0) {
        document.querySelector('form[action*="submit"]').submit();
        return;
    }
    const safeSisaWaktu = Math.floor(sisaWaktu);
    const h = Math.floor(safeSisaWaktu / 3600);
    const m = Math.floor((safeSisaWaktu % 3600) / 60);
    const s = safeSisaWaktu % 60;
    timerText.textContent = (h > 0 ? String(h).padStart(2,'0') + ':' : '') +
        String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');

    const pct = (safeSisaWaktu / totalWaktu) * 100;
    timerProgress.style.width = pct + '%';

    if (safeSisaWaktu <= 300) {
        timerDisplay.classList.add('danger');
        timerBar.classList.add('danger');
    }

    sisaWaktu--;
}
updateTimer();
setInterval(updateTimer, 1000);

// Anti Cheat
document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('copy', e => e.preventDefault());
document.addEventListener('paste', e => e.preventDefault());

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        fetch('{{ route("siswa.ujian.log-blur", $sesi->id) }}', {
            method: 'POST',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
            }
        });
    }
});

// Option selection styling
function selectOption(label) {
    document.querySelectorAll('.option-label').forEach(l => l.classList.remove('selected'));
    label.classList.add('selected');
    label.querySelector('input').checked = true;
    autoSave();
}

// Navigation
function navigasi(dir) {
    let target = {{ $no }};
    if (dir === 'next') target++;
    if (dir === 'prev') target--;
    
    if (target < 1 || target > {{ $totalSoal }}) return;
    
    // Disable buttons to prevent double click
    document.querySelectorAll('.btn-nav').forEach(btn => btn.disabled = true);
    
    autoSave().finally(() => {
        if (typeof App !== 'undefined' && App.Progress) App.Progress.start();
        window.location.href = '{{ url("siswa/ujian/" . $sesi->id . "/soal") }}/' + target;
    });
}

// Mobile Navigator Toggle
function toggleNav() {
    const panel = document.getElementById('navigator-panel');
    const overlay = document.getElementById('nav-overlay');
    panel.classList.toggle('open');
    overlay.classList.toggle('show');
}

// Modal
function showSubmitModal() { 
    // Hitung soal terjawab (ambil dari navbar + cek soal saat ini)
    let answered = [];
    let unanswered = [];
    
    document.querySelectorAll('.nav-btn').forEach(btn => {
        let isBtnAnswered = btn.classList.contains('answered');
        let num = parseInt(btn.textContent.trim());
        
        if (btn.classList.contains('active')) {
            // Cek real-time form current question
            const form = document.getElementById('jawaban-form');
            const checkedOption = form.querySelector('input[type="radio"]:checked');
            const essayBox = form.querySelector('.essay-box');
            
            if (checkedOption || (essayBox && essayBox.value.trim() !== '')) {
                isBtnAnswered = true;
            } else {
                isBtnAnswered = false;
            }
        }
        
        if (isBtnAnswered) {
            answered.push(num);
        } else {
            unanswered.push(num);
        }
    });

    document.getElementById('modal-answered-count').textContent = answered.length;
    
    const unList = document.getElementById('modal-unanswered-list');
    const unNums = document.getElementById('unanswered-numbers');
    
    if (unanswered.length > 0) {
        unList.classList.remove('hidden');
        unNums.innerHTML = unanswered.map(n => `<span class="px-2 py-1 bg-white dark:bg-zinc-800 rounded-md border border-amber-200 dark:border-amber-700/50 shadow-sm text-xs font-bold">${n}</span>`).join('');
    } else {
        unList.classList.add('hidden');
    }

    document.getElementById('submit-modal').classList.add('show'); 
}
function closeSubmitModal() { document.getElementById('submit-modal').classList.remove('show'); }

// Auto-save via AJAX
let saveTimeout;
function debounceAutoSave() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(autoSave, 1000);
}

function autoSave() {
    const form = document.getElementById('jawaban-form');
    const data = new FormData(form);
    
    return fetch('{{ route("siswa.ujian.jawab", $sesi->id) }}', {
        method: 'POST',
        body: data,
        headers: { 
            'X-Requested-With': 'XMLHttpRequest', 
            'Accept': 'application/json' 
        }
    }).then(res => {
          if (res.status === 419 || res.status === 401) {
              alert('⚠️ Sesi Anda telah berakhir / kedaluwarsa. Halaman akan memuat ulang agar Anda dapat masuk kembali dan melanjutkan ujian.');
              window.location.reload();
              return;
          }
          if (!res.ok) throw new Error('Simpan jawaban gagal.');
          return res.json();
      })
      .then(res => {
          if(res && res.status === 'saved') {
              showToast();
              const currentNavBtn = document.querySelector('.nav-btn.active');
              if(currentNavBtn) {
                  currentNavBtn.classList.add('answered');
                  const raguCheck = document.getElementById('ragu_check');
                  if (raguCheck && raguCheck.checked) {
                      currentNavBtn.classList.add('ragu');
                  } else {
                      currentNavBtn.classList.remove('ragu');
                  }
              }
          }
          return res;
      })
      .catch(err => {
          console.error('Error saving answer:', err);
      });
}

function showToast() {
    const toast = document.getElementById('autosave-toast');
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2000);
}

// Keep-Alive Ping (Perpanjang sesi Laravel siswa secara otomatis setiap 5 menit)
setInterval(() => {
    fetch(window.location.href, {
        method: 'HEAD',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).catch(err => console.warn('Keep-alive ping failed:', err));
}, 300000); // 5 menit
</script>
