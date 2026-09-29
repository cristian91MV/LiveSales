import './bootstrap';
import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';
import Swal from 'sweetalert2';

window.Swal = Swal;

document.addEventListener('DOMContentLoaded', () => {
    const flash = window.flashMessages ?? {};

    if (flash.success) {
        Swal.fire({
            icon: 'success',
            title: 'Operación exitosa',
            text: flash.success,
            confirmButtonText: 'Aceptar',
        });
    }

    if (flash.error) {
        Swal.fire({
            icon: 'error',
            title: 'No se pudo realizar la operación',
            text: flash.error,
            confirmButtonText: 'Aceptar',
        });
    }

    if (flash.warning) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: flash.warning,
            confirmButtonText: 'Aceptar',
        });
    }

    document.addEventListener('submit', async (event) => {
        const form = event.target;

        if (!form.matches('.js-confirm')) {
            return;
        }

        if (form.dataset.confirmed === 'true') {
            return;
        }

        event.preventDefault();

        const result = await Swal.fire({
            icon: form.dataset.icon ?? 'question',
            title: form.dataset.title ?? '¿Está seguro?',
            text: form.dataset.text ?? '',
            showCancelButton: true,
            confirmButtonText:
                form.dataset.confirmText ?? 'Sí, continuar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
        });

        if (result.isConfirmed) {
            form.dataset.confirmed = 'true';
            form.submit();
        }
    });
});
