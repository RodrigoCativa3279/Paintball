document.querySelectorAll('.dropdown-content a').forEach(item => {
    item.addEventListener('click', event => {
        event.preventDefault(); // Evita que se recargue la página
        alert('Opción seleccionada: ' + item.textContent);
    });
});

// Manejo del menú de países
const countryToggle = document.querySelector('.country-toggle');
const countryDropdown = document.querySelector('.country-dropdown-content');

countryToggle.addEventListener('click', (event) => {
    event.preventDefault();
    countryDropdown.classList.toggle('active'); // Alterna la clase para mostrar/ocultar el menú
});

// Cerrar el menú al hacer clic fuera
window.addEventListener('click', (event) => {
    if (!countryToggle.contains(event.target) && !countryDropdown.contains(event.target)) {
        countryDropdown.classList.remove('active');
    }
});
document.querySelectorAll('.faq-item h3').forEach(item => {
    item.addEventListener('click', () => {
        const parent = item.parentElement;
        parent.classList.toggle('active'); // Alterna la clase 'active'
    });
});




