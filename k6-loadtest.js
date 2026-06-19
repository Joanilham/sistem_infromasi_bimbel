import http from 'k6/http';
import { check, sleep } from 'k6';
import { parseHTML } from 'k6/html';
import { SharedArray } from 'k6/data';

// Jika Anda memiliki file CSV untuk data siswa, kita bisa me-loadnya di sini.
// Untuk contoh ini kita gunakan data statis, silakan ubah sesuai kebutuhan.
// const siswaData = new SharedArray('siswa', function () {
//   return JSON.parse(open('./siswa.json')); 
// });

export const options = {
    // Skenario pengujian (Sama seperti Artillery: Warm up -> Peak)
    stages: [
        { duration: '1m', target: 10 }, // Naik ke 10 siswa dalam 1 menit
        { duration: '3m', target: 100 }, // Naik perlahan ke 100 siswa
        { duration: '4m', target: 200 }, // Naik ke 200 siswa bersamaan
        { duration: '2m', target: 0 },   // Turun kembali ke 0 (Cool down)
    ],
    // Bypass masalah sertifikat SSL
    insecureSkipTLSVerify: true,
};

const BASE_URL = 'https://bimbel.geniusedu.my.id';

export default function () {
    // 1. Buka halaman Login untuk mengambil CSRF Token
    let res = http.get(`${BASE_URL}/login`);
    check(res, { 'Halaman login berhasil dimuat': (r) => r.status === 200 });

    // K6 memiliki fitur parseHTML seperti jQuery, sangat mudah mencari elemen!
    const doc = parseHTML(res.body);
    const csrfToken = doc.find('input[name="_token"]').attr('value');

    if (!csrfToken) {
        console.error("Gagal mendapatkan CSRF Token!");
        return; // Hentikan virtual user ini jika gagal
    }

    sleep(2); // Siswa membaca halaman login

    // 2. Lakukan Login
    let loginRes = http.post(`${BASE_URL}/login`, {
        _token: csrfToken,
        email: "siswa1@example.com", // Ganti dengan email asli siswa untuk ditest
        password: "password123",     // Ganti dengan password asli
    });

    // Karena sukses login di Laravel me-redirect (302) ke dashboard, kita cek:
    check(loginRes, { 'Login berhasil': (r) => r.status === 200 || r.status === 302 });
    
    sleep(2); // Siswa melihat dashboard

    // Note: Anda bisa melanjutkan flow request ke URL ujian di bawah ini
    // menggunakan pola yang sama (ambil CSRF -> POST -> submit).
    // Karena session dan cookies ditangani otomatis oleh K6!
}
