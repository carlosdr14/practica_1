import './bootstrap';
import 'bootstrap';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

//instancia global de notyf 
window.notyf = new Notyf({
    duration: 3000,
    position: {
        x: 'center',
        y: 'top',
    },
    types: [
        {
            type: 'suceess',
            background: 'green',
            icon: '<i class="fa-solid fa-circle-check"></i>',
        },
        {
            type: 'error',
            background: 'red',
            icon: '<i class="fa-solid fa-circle-xmark"></i>',
        },
    ],
});



