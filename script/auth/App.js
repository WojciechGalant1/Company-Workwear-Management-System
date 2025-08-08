import { LoginValidator } from './LoginValidator.js';

document.addEventListener('DOMContentLoaded', () => {
    const kodInput = document.getElementById('kodID');
    const alertContainer = document.querySelector('.alert-container');
    LoginValidator.kodValidator(kodInput, alertContainer);
});


