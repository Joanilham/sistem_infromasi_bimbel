import http from 'k6/http';
import { check, sleep } from 'k6';
import { parseHTML } from 'k6/html';
import { SharedArray } from 'k6/data';

// 1. Membaca data siswa yang dihasilkan oleh GenerateLoadTestData
const siswaData = new SharedArray('siswa', function () {
    return JSON.parse(open('./storage/app/siswa_data.json')); 
});

export const options = {
    // Skenario pengujian (Warm up -> Peak load -> Cool down)
    stages: [
        { duration: '1m', target: 50 },  // Naik ke 50 siswa dalam 1 menit
        { duration: '2m', target: 100 }, // Naik ke 100 siswa
        { duration: '3m', target: 200 }, // Puncak: 200 siswa bersamaan
        { duration: '1m', target: 0 },   // Selesai
    ],
    insecureSkipTLSVerify: true, // Abaikan error sertifikat SSL
};

const BASE_URL = 'https://bimbel.geniusedu.my.id';

export default function () {
    // K6 akan men-generate Virtual User ID (__VU) dari 1 sampai 200.
    // Kita gunakan ID ini untuk mengambil 1 akun siswa unik agar tidak berebut akun.
    const userIndex = (__VU - 1) % siswaData.length;
    const siswa = siswaData[userIndex];

    // ==============================================================
    // STEP 1: Akses Halaman Login & Ambil CSRF Token
    // ==============================================================
    let res = http.get(`${BASE_URL}/login`);
    check(res, { 'GET /login berhasil': (r) => r.status === 200 });

    let doc = parseHTML(res.body);
    let csrfToken = doc.find('input[name="_token"]').attr('value');

    if (!csrfToken) {
        console.error(`VU ${__VU}: Gagal mendapatkan CSRF Token di /login`);
        return; // Berhenti jika gagal
    }
    sleep(1);

    // ==============================================================
    // STEP 2: Melakukan Login
    // ==============================================================
    let loginRes = http.post(`${BASE_URL}/login`, {
        _token: csrfToken,
        email: siswa.email,
        password: siswa.password,
    });

    // Laravel meredirect (302) setelah sukses login, dan K6 mengikutinya secara otomatis
    check(loginRes, { 'Login berhasil': (r) => r.status === 200 && r.url.indexOf('/dashboard') !== -1 });
    sleep(2);

    // ==============================================================
    // STEP 3: Mulai Ujian
    // ==============================================================
    // K6 secara default mengikuti redirect. Supaya kita bisa mengambil sesi_id
    // dari header Location, kita matikan auto-redirect untuk request ini.
    let mulaiRes = http.post(
        `${BASE_URL}/siswa/ujian/${siswa.ujian_id}/mulai`, 
        { _token: csrfToken }, 
        { redirects: 0 }
    );

    check(mulaiRes, { 'Mulai Ujian berhasil (Redirect 302)': (r) => r.status === 302 });

    let redirectUrl = mulaiRes.headers['Location'];
    if (!redirectUrl) {
        console.error(`VU ${__VU}: Gagal menekan Mulai Ujian (Header Location tidak ada)`);
        return;
    }

    // URL redirect formatnya: https://.../siswa/ujian/123/soal/1
    // Kita ekstrak angka 123 sebagai sesi_id
    let match = redirectUrl.match(/ujian\/([0-9]+)\/soal/);
    if (!match) {
        console.error(`VU ${__VU}: Sesi ID tidak ditemukan di URL: ${redirectUrl}`);
        return;
    }
    let sesi_id = match[1];
    sleep(1);

    // ==============================================================
    // STEP 4: Mengerjakan 10 Soal Ujian (Loop)
    // ==============================================================
    for (let i = 1; i <= 10; i++) {
        // A. Buka Halaman Soal
        let soalRes = http.get(`${BASE_URL}/siswa/ujian/${sesi_id}/soal/${i}`);
        check(soalRes, { [`Buka Soal ke-${i} berhasil`]: (r) => r.status === 200 });
        
        // Simulasikan waktu berpikir untuk menjawab (3 detik)
        sleep(3);

        // B. Kirim Jawaban via AJAX (Sama seperti Laravel Frontend)
        let jawabRes = http.post(
            `${BASE_URL}/siswa/ujian/${sesi_id}/jawab`,
            {
                _token: csrfToken,
                urutan: i.toString(),
                jawaban_essay: `Ini adalah jawaban otomatis K6 untuk soal ke-${i}`
            },
            {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Tanda request AJAX
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }
        );
        check(jawabRes, { [`Jawab Soal ke-${i} sukses`]: (r) => r.status === 200 });
    }

    // ==============================================================
    // STEP 5: Selesai / Submit Ujian
    // ==============================================================
    sleep(2);
    let submitRes = http.post(`${BASE_URL}/siswa/ujian/${sesi_id}/submit`, {
        _token: csrfToken,
    });
    
    // Biasanya akan diredirect ke halaman hasil ujian
    check(submitRes, { 'Submit Ujian sukses': (r) => r.status === 200 || r.status === 302 });
}
