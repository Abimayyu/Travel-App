import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
import axios from 'axios';

// Set CSRF Token
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Set Base URL untuk API (sesuaikan dengan API server kamu)
axios.defaults.baseURL = 'http://127.0.0.1:8000/';

// Jika ingin menggunakan token untuk permintaan API, kamu bisa menambahkannya ke header secara global
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
// Jika ingin menggunakan token untuk permintaan API, kamu bisa menambahkannya ke header    