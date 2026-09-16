import './bootstrap';
import Swal from 'sweetalert2';
import flatpickr from 'flatpickr';

window.Swal = Swal;
window.flatpickr = flatpickr;

// Listener SweetAlert2 untuk modal pemberitahuan
window.addEventListener('swal:modal', event => {
    const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
    Swal.fire({
        title: data.title,
        text: data.text,
        icon: data.icon,
        confirmButtonColor: '#3085d6',
    });
});

// Listener SweetAlert2 untuk konfirmasi reboot
window.addEventListener('swal:confirm', event => {
    const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
    Swal.fire({
        title: data.title,
        text: data.text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Jalankan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch(data.action, { id: data.id });
        }
    });
});