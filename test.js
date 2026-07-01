import http from 'k6/http';
import { check } from 'k6';
import { parseHTML } from 'k6/html';

const siswa = {email: 'loadtest1@example.com', password: 'password', ujian_id: 1};

export default function () {
    let res = http.get('http://host.docker.internal:8080/login');
    let doc = parseHTML(res.body);
    let csrfToken = doc.find('input[name="_token"]').attr('value');
    console.log('Old CSRF Token:', csrfToken);
    
    let loginRes = http.post('http://host.docker.internal:8080/login', {
        _token: csrfToken,
        email: siswa.email,
        password: siswa.password,
    });
    console.log('Login Response Status:', loginRes.status);
    console.log('Login Response URL:', loginRes.url);
    
    let dashboardDoc = parseHTML(loginRes.body);
    let newCsrfToken = dashboardDoc.find('meta[name="csrf-token"]').attr('content');
    if (newCsrfToken) {
        csrfToken = newCsrfToken;
        console.log('New CSRF Token:', csrfToken);
    } else {
        console.log('Could not find new CSRF token on dashboard!');
    }
    
    let mulaiRes = http.post('http://host.docker.internal:8080/siswa/ujian/1/mulai', { _token: csrfToken }, { redirects: 0 });
    console.log('Mulai Response Status:', mulaiRes.status);
    console.log('Mulai Response Location:', mulaiRes.headers['Location']);
}
