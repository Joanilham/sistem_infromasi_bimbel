<!-- jQuery & DataTables JS -->
<script defer src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script defer src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>

<!-- SweetAlert2 -->
<script defer src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- Global Delete Confirmation Script -->
<script>
    function confirmDelete(title, text, formElement) {
        Swal.fire({
            title: title || 'Apakah Anda yakin?',
            text: text || "Data yang dihapus tidak dapat direstore!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5', // Indigo 600
            cancelButtonColor: '#ef4444', // Red 500
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
            customClass: {
                popup: 'rounded-2xl border border-slate-100 dark:border-slate-700',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                formElement.submit();
            }
        })
    }
</script>

@yield('scripts')

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ajaxTable', () => ({
            isLoading: false,
            
            fetchData(e) {
                let form = null;
                if (e && e.target && e.target.tagName === 'FORM') {
                    form = e.target;
                } else if (e && e.target && e.target.form) {
                    form = e.target.form;
                } else {
                    form = this.$el.querySelector('form');
                }

                if (!form) return;

                let url = new URL(form.action || window.location.href);
                let formData = new FormData(form);
                
                url.search = '';
                for (let [key, value] of formData.entries()) {
                    if (value) url.searchParams.append(key, value);
                }

                this.doFetch(url.toString());
            },
            
            navigate(e, urlStr) {
                if (e) e.preventDefault();
                this.doFetch(urlStr);
            },

            doFetch(urlStr) {
                this.isLoading = true;
                
                fetch(urlStr, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    
                    // Update Elements safely without losing focus on inputs
                    const updateElement = (id) => {
                        let newEl = doc.getElementById(id);
                        let oldEl = document.getElementById(id);
                        if (newEl && oldEl) {
                            oldEl.innerHTML = newEl.innerHTML;
                        }
                    };

                    updateElement('ajax-summary-cards');
                    updateElement('ajax-table-body');
                    updateElement('ajax-pagination');
                    
                    // Update URL silently
                    window.history.pushState({}, '', urlStr);
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    // optionally show alert or toast
                })
                .finally(() => {
                    this.isLoading = false;
                    if (typeof App !== 'undefined' && App.Progress) {
                        App.Progress.done();
                    }
                });
            }
        }));
    });
</script>