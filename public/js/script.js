const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('sidebar-wrapper');
const contentWrapper = document.getElementById('page-content-wrapper');

// Evento al hacer clic en el botón de alternar barra lateral
sidebarToggle.addEventListener('click', function () {
    sidebar.classList.toggle('toggled');
    contentWrapper.classList.toggle('toggled');
});

document.addEventListener("DOMContentLoaded", function() {
    const tabs = document.querySelectorAll('.tab-title');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remover la clase 'active' de todas las pestañas y contenidos
            tabs.forEach(tab => tab.classList.remove('active'));
            contents.forEach(content => content.classList.remove('active'));

            // Agregar la clase 'active' a la pestaña seleccionada y al contenido correspondiente
            const target = document.getElementById(tab.getAttribute('data-tab'));
            tab.classList.add('active');
            target.classList.add('active');
        });
    });
});
