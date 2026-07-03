<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    if (window.renderDashboardCharts) {
        document.removeEventListener('turbo:load', window.renderDashboardCharts);
    }

    window.renderDashboardCharts = function () {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? '#334155' : '#f1f5f9';

        // 1. Chart Peserta Didik
        var optionsPeserta = {
            series: [{
                name: 'Peserta Masuk',
                data: @json($chartPesertaMasuk)
            }, {
                name: 'Peserta Keluar',
                data: @json($chartPesertaKeluar)
            }],
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.05
                }
            },
            colors: ['#6366F1', '#F43F5E'], // Indigo & Rose
            dataLabels: { enabled: false },
            stroke: { 
                curve: 'smooth', 
                width: 3 
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            xaxis: {
                categories: @json($chartLabels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            yaxis: {
                decimalsInFloat: 0,
                labels: {
                    formatter: function (val) {
                        return Math.round(val);
                    },
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            grid: {
                borderColor: gridColor,
                strokeDashArray: 4,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontWeight: 700,
                fontSize: '12px',
                labels: { colors: '#64748b' }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function (val) {
                        return val + " Siswa"
                    }
                }
            }
        };

        const containerPeserta = document.querySelector("#chart-peserta-didik");
        if (containerPeserta) {
            if (window.chartPesertaInstance) {
                try { window.chartPesertaInstance.destroy(); } catch(e){}
            }
            containerPeserta.innerHTML = '';
            window.chartPesertaInstance = new ApexCharts(containerPeserta, optionsPeserta);
            window.chartPesertaInstance.render();
        }

        // 2. Chart Keuangan
        var optionsKeuangan = {
            series: [{
                name: 'Uang Masuk',
                data: @json($chartUangMasuk)
            }, {
                name: 'Uang Keluar',
                data: @json($chartUangKeluar)
            }],
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.05
                }
            },
            colors: ['#10B981', '#F43F5E'], // Emerald & Rose
            dataLabels: { enabled: false },
            stroke: { 
                curve: 'smooth', 
                width: 3 
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            xaxis: {
                categories: @json($chartLabels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        if (value >= 1000000000) return "Rp" + (value / 1000000000).toFixed(1).replace(/\.0$/, '') + "M";
                        if (value >= 1000000) return "Rp" + (value / 1000000).toFixed(1).replace(/\.0$/, '') + "Jt";
                        if (value >= 1000) return "Rp" + (value / 1000).toFixed(1).replace(/\.0$/, '') + "K";
                        return "Rp" + value;
                    },
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            grid: {
                borderColor: gridColor,
                strokeDashArray: 4,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontWeight: 700,
                fontSize: '12px',
                labels: { colors: '#64748b' }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function (value) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            }
        };

        const containerKeuangan = document.querySelector("#chart-keuangan");
        if (containerKeuangan) {
            if (window.chartKeuanganInstance) {
                try { window.chartKeuanganInstance.destroy(); } catch(e){}
            }
            containerKeuangan.innerHTML = '';
            window.chartKeuanganInstance = new ApexCharts(containerKeuangan, optionsKeuangan);
            window.chartKeuanganInstance.render();
        }
    };

    document.addEventListener('turbo:load', window.renderDashboardCharts);
    
    // Fallback: render after a short delay if turbo:load is missed
    setTimeout(() => {
        if (document.querySelector("#chart-peserta-didik") && !document.querySelector("#chart-peserta-didik").innerHTML.trim()) {
            window.renderDashboardCharts();
        }
    }, 150);


    window.dashboardClock = () => ({
        time: '00:00:00',
        date: 'Memuat...',
        zonaWaktu: 'WIB',

        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
        },

        updateClock() {
            const now = new Date();

            this.time = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).replace(/\./g, ':');

            this.date = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const offset = -now.getTimezoneOffset() / 60;
            if (offset === 7) this.zonaWaktu = 'Waktu Indonesia Barat (WIB)';
            else if (offset === 8) this.zonaWaktu = 'Waktu Indonesia Tengah (WITA)';
            else if (offset === 9) this.zonaWaktu = 'Waktu Indonesia Timur (WIT)';
            else this.zonaWaktu = 'Waktu Lokal: GMT' + (offset > 0 ? '+' : '') + offset;
        }
    });
</script>
