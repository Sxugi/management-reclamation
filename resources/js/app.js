import './bootstrap';
import Alpine from 'alpinejs';

import maplibregl from 'maplibre-gl';
import 'maplibre-gl/dist/maplibre-gl.css';

import MaplibreGeocoder from '@maplibre/maplibre-gl-geocoder';
import '@maplibre/maplibre-gl-geocoder/dist/maplibre-gl-geocoder.css';

window.maplibregl = maplibregl;
window.MaplibreGeocoder = MaplibreGeocoder;

import './components/map-component.js';

window.Alpine = Alpine;

Alpine.start();

// Password visibility toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const togglePasswordVisibility = document.querySelector('.toggle-password');
    const passwordInput = document.getElementById('password');
    
    if (togglePasswordVisibility && passwordInput) {
        togglePasswordVisibility.addEventListener('click', function() {
            // Toggle password visibility
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle eye icon
            const iconSrc = type === 'password' 
                ? '/images/eye-enabled.svg'  // Hidden password
                : '/images/eye-disabled.svg';  // Visible password
                
            this.setAttribute('src', iconSrc);
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const loginButton = document.querySelector('.login-button');
    
    if (loginButton) {
        // Mouse enter (hover) effect
        loginButton.addEventListener('mouseenter', function() {
            this.classList.add('bg-grey-100');
            this.classList.remove('bg-slategray-200');
            this.classList.add('text-darkslategray-200');
            this.classList.remove('text-white');
        });
        
        // Mouse leave effect
        loginButton.addEventListener('mouseleave', function() {
            this.classList.add('bg-slategray-200');
            this.classList.remove('bg-grey-100');
            this.classList.add('text-white');
            this.classList.remove('text-darkslategray-200');
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const functionalButton = document.querySelector('.functional-button');
    
    if (functionalButton) {
        // Mouse enter (hover) effect
        functionalButton.addEventListener('mouseenter', function() {
            this.classList.add('bg-slategray-200');
            this.classList.remove('bg-darkslategray');
        });
        
        // Mouse leave effect
        functionalButton.addEventListener('mouseleave', function() {
            this.classList.add('bg-darkslategray');
            this.classList.remove('bg-slategray-200');
        });
    }
});

//Simple script to handle dropdown toggle
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButtons = document.querySelectorAll('.dropdown button');
    
    dropdownButtons.forEach(button => {
        button.addEventListener('click', function() {
            const menu = this.nextElementSibling;
            menu.classList.toggle('hidden');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        dropdownButtons.forEach(button => {
            if (!button.contains(event.target)) {
                const menu = button.nextElementSibling;
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            }
        });
    });
});

