/* =============================================
   LOADING & ERROR HANDLER — Laravel Website
   ============================================= */

const App = (() => {

  /* -------- TOP PROGRESS BAR -------- */
  const Progress = {
    el: null,
    timer: null,
    current: 0,

    init() {
      this.el = document.getElementById('top-progress');
    },

    start() {
      if (!this.el) return;
      this.current = 0;
      this.el.style.width = '0%';
      this.el.style.opacity = '1';
      this._tick();
    },

    _tick() {
      clearTimeout(this.timer);
      if (this.current < 90) {
        this.current += Math.random() * 10;
        this.el.style.width = this.current + '%';
        const delay = 200 + Math.random() * 400;
        this.timer = setTimeout(() => this._tick(), delay);
      }
    },

    done() {
      if (!this.el) return;
      clearTimeout(this.timer);
      this.el.style.width = '100%';
      setTimeout(() => {
        this.el.style.opacity = '0';
        setTimeout(() => {
          this.el.style.width = '0%';
          this.el.style.opacity = '1';
        }, 400);
      }, 300);
    },

    fail() {
      if (!this.el) return;
      clearTimeout(this.timer);
      this.el.style.background = '#E24B4A';
      this.el.style.width = '100%';
      setTimeout(() => {
        this.el.style.opacity = '0';
        setTimeout(() => {
          this.el.style.background = '';
          this.el.style.width = '0%';
          this.el.style.opacity = '1';
        }, 400);
      }, 800);
    }
  };

  /* -------- PAGE LOADER -------- */
  const Loader = {
    el: null,

    init() {
      this.el = document.getElementById('page-loader');
    },

    hide() {
      if (!this.el) return;
      this.el.classList.add('fade-out');
      setTimeout(() => {
        if (this.el) this.el.remove();
      }, 500);
    }
  };

  /* -------- TOAST SISTEM -------- */
  const Toast = {
    container: null,
    queue: [],

    init() {
      this.container = document.getElementById('toast-container');
      if (!this.container) {
        this.container = document.createElement('div');
        this.container.id = 'toast-container';
        document.body.appendChild(this.container);
      }
    },

    show(type = 'info', title, message, duration = 5000) {
      const icons = {
        success: '✅',
        error:   '❌',
        warning: '⚠️',
        info:    'ℹ️'
      };

      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.style.position = 'relative';
      toast.innerHTML = `
        <span class="toast-icon">${icons[type] || 'ℹ️'}</span>
        <div class="toast-body">
          ${title ? `<div class="toast-title">${title}</div>` : ''}
          ${message ? `<div class="toast-msg">${message}</div>` : ''}
        </div>
        <button class="toast-close" aria-label="Tutup">×</button>
      `;

      this.container.appendChild(toast);

      toast.querySelector('.toast-close').addEventListener('click', () => {
        this._dismiss(toast);
      });

      const timer = setTimeout(() => this._dismiss(toast), duration);
      toast._timer = timer;

      return toast;
    },

    _dismiss(toast) {
      clearTimeout(toast._timer);
      toast.classList.add('toast-out');
      setTimeout(() => {
        if (toast.parentNode) toast.parentNode.removeChild(toast);
      }, 300);
    },

    success: (title, msg, d) => Toast.show('success', title, msg, d),
    error:   (title, msg, d) => Toast.show('error', title, msg, d),
    warning: (title, msg, d) => Toast.show('warning', title, msg, d),
    info:    (title, msg, d) => Toast.show('info', title, msg, d),
  };

  /* -------- DETEKSI KONEKSI INTERNET -------- */
  const Network = {
    offlineToast: null,

    init() {
      window.addEventListener('offline', () => this._onOffline());
      window.addEventListener('online',  () => this._onOnline());
    },

    _onOffline() {
      this.offlineToast = Toast.show(
        'error',
        'Koneksi Terputus',
        'Anda sedang offline. Beberapa fitur mungkin tidak tersedia.',
        0 // tidak auto-close
      );

      // Tampilkan banner offline jika ada
      const banner = document.getElementById('offline-banner');
      if (banner) banner.style.display = 'flex';
    },

    _onOnline() {
      if (this.offlineToast) {
        Toast._dismiss(this.offlineToast);
        this.offlineToast = null;
      }

      Toast.success(
        'Kembali Online',
        'Koneksi internet Anda telah pulih.',
        4000
      );

      const banner = document.getElementById('offline-banner');
      if (banner) banner.style.display = 'none';
    }
  };

  /* -------- FETCH WRAPPER (dengan error handling) -------- */
  const Http = {
    async get(url, options = {}) {
      return this._request('GET', url, null, options);
    },

    async post(url, data = {}, options = {}) {
      return this._request('POST', url, data, options);
    },

    async _request(method, url, data, options) {
      Progress.start();

      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      const csrf = csrfMeta ? csrfMeta.content : '';

      const config = {
        method,
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          ...options.headers
        },
        ...options
      };

      if (data) config.body = JSON.stringify(data);

      try {
        const res = await fetch(url, config);

        if (!res.ok) {
          Progress.fail();
          this._handleHttpError(res.status, url);
          const errData = await res.json().catch(() => ({}));
          throw { status: res.status, data: errData };
        }

        Progress.done();
        return await res.json();

      } catch (err) {
        Progress.fail();

        if (!navigator.onLine) {
          Toast.error('Tidak Ada Koneksi', 'Periksa internet Anda lalu coba lagi.');
        } else if (err.status) {
          // sudah dihandle di _handleHttpError
        } else {
          Toast.error('Gagal Terhubung', 'Tidak dapat menghubungi server. Coba lagi.');
        }

        throw err;
      }
    },

    _handleHttpError(status, url) {
      const messages = {
        400: { title: 'Permintaan Tidak Valid',   msg: 'Data yang dikirim tidak sesuai.' },
        401: { title: 'Sesi Habis',               msg: 'Silakan login kembali.' },
        403: { title: 'Akses Ditolak',            msg: 'Anda tidak memiliki izin.' },
        404: { title: 'Tidak Ditemukan',          msg: 'Data yang diminta tidak ada.' },
        419: { title: 'Token Kedaluwarsa',        msg: 'Halaman akan dimuat ulang.' },
        422: { title: 'Validasi Gagal',           msg: 'Periksa kembali input Anda.' },
        429: { title: 'Terlalu Banyak Permintaan', msg: 'Tunggu sebentar lalu coba lagi.' },
        500: { title: 'Kesalahan Server',         msg: 'Terjadi masalah di server. Coba lagi.' },
        502: { title: 'Server Tidak Tersedia',   msg: 'Server sedang dalam perbaikan.' },
        503: { title: 'Layanan Tidak Tersedia',  msg: 'Sedang dalam pemeliharaan.' },
        504: { title: 'Waktu Habis',             msg: 'Server lambat merespons. Coba lagi.' },
      };

      const info = messages[status] || { title: `Error ${status}`, msg: 'Terjadi kesalahan.' };
      Toast.error(info.title, info.msg);

      // Redirect untuk error auth
      if (status === 401) setTimeout(() => window.location.href = '/login', 2000);
      if (status === 419) setTimeout(() => window.location.reload(), 2000);
    }
  };

  /* -------- SKELETON LOADER HELPER -------- */
  const Skeleton = {
    show(containerEl) {
      if (!containerEl) return;
      containerEl.dataset.original = containerEl.innerHTML;
      containerEl.innerHTML = `
        <div style="padding: 16px; display: flex; flex-direction: column; gap: 12px;">
          <div style="display: flex; gap: 12px; align-items: center;">
            <div class="skeleton skeleton-circle" style="width:44px;height:44px;flex-shrink:0"></div>
            <div style="flex:1;display:flex;flex-direction:column;gap:8px">
              <div class="skeleton skeleton-text" style="width:60%"></div>
              <div class="skeleton skeleton-text" style="width:40%;height:10px"></div>
            </div>
          </div>
          <div class="skeleton skeleton-block" style="height:120px;width:100%"></div>
          <div class="skeleton skeleton-block" style="height:80px;width:100%"></div>
          <div class="skeleton skeleton-text" style="width:70%"></div>
        </div>
      `;
    },

    hide(containerEl) {
      if (!containerEl || !containerEl.dataset.original) return;
      containerEl.innerHTML = containerEl.dataset.original;
      delete containerEl.dataset.original;
    }
  };

  /* -------- INIT -------- */
  function init() {
    Progress.init();
    Loader.init();
    Toast.init();
    Network.init();

    // Sembunyikan page loader saat halaman siap
    if (document.readyState === 'complete') {
      Loader.hide();
    } else {
      window.addEventListener('load', () => Loader.hide());
    }

    // Progress bar untuk navigasi link biasa
    document.addEventListener('click', (e) => {
      const link = e.target.closest('a[href]');
      if (!link) return;
      const href = link.getAttribute('href');
      if (!href || href.startsWith('#') || href.startsWith('javascript') ||
          link.target === '_blank' || link.hasAttribute('download')) return;
      Progress.start();
    });

    // Error global JS
    window.addEventListener('error', (e) => {
      console.error('[App Error]', e.message, e.filename, e.lineno);
      // Uncomment jika ingin toast untuk JS error:
      // Toast.error('Terjadi Kesalahan', 'Silakan muat ulang halaman.');
    });

    // Unhandled promise rejection
    window.addEventListener('unhandledrejection', (e) => {
      console.error('[Unhandled Promise]', e.reason);
    });
  }

  return { init, Progress, Loader, Toast, Http, Skeleton, Network };
})();

// Auto init
document.addEventListener('DOMContentLoaded', () => App.init());
