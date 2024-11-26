const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('sidebar-wrapper');
const contentWrapper = document.getElementById('page-content-wrapper');

// Evento al hacer clic en el botón de alternar barra lateral
sidebarToggle.addEventListener('click', function () {
    sidebar.classList.toggle('toggled'); // Muestra/oculta la barra lateral
    contentWrapper.classList.toggle('toggled'); // Ajusta el contenido principal
});
