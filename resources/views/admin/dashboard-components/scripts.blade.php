<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#4F46E5', '#EF4444'], // Indigo & Red
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.02,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
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
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            grid: {
                borderColor: gridColor,
                strokeDashArray: 4,
                xaxis: { lines: { show: false } }
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
                x: { format: 'dd MMM yyyy' }
            }
        };

        var chartPeserta = new ApexCharts(document.querySelector("#chart-peserta-didik"), optionsPeserta);
        chartPeserta.render();

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
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#10B981', '#F43F5E'], // Emerald & Rose
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.02,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
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
                        return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                    },
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            grid: {
                borderColor: gridColor,
                strokeDashArray: 4,
                xaxis: { lines: { show: false } }
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

        var chartKeuangan = new ApexCharts(document.querySelector("#chart-keuangan"), optionsKeuangan);
        chartKeuangan.render();
    });

    document.addEventListener('alpine:init', () => {
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
    });
</script>
