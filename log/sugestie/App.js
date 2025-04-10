// import { AlertManager } from './AlertManager.js';
//import { UserSuggestions } from './UserSuggestions.js';
import { LoginValidator } from '../sesja/LoginValidator.js';

document.addEventListener('DOMContentLoaded', () => {
    //const usernameInput = document.getElementById('username');
    //const passwordInput = document.getElementById('password');
    //const suggestions = document.getElementById('suggestions');
    // const alertManager = new AlertManager(alertContainer);

    //new UserSuggestions(usernameInput, suggestions, alertManager, passwordInput);
    //new LoginValidator(usernameInput, passwordInput, kodInput, alertManager); 
    // new LoginValidator(kodInput, alertManager);

    const kodInput = document.getElementById('kodID');
    const alertContainer = document.querySelector('.alert-container');
    LoginValidator.kodValidator(kodInput, alertContainer); 
});
