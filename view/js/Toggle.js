(() => {
  'use strict';

  let menu;
  let menuDashboard;
  let toggle;

  const basePages = [
    "index.html",
    "services.html",
    "contact.html",
    "login.html",
    "register.html",
  ];

  const PageSecond = [
    "admin.html",
    "manager.html",
    "client.html"
  ];

  const typeMenu = [
    'active',
    'menu__bar--show'
  ];

  function renderToggle(element, index) {
    if (!element) return;
    element.classList.toggle(typeMenu[index]);
  }

  function handleToggle() {
    if (!menu || !menuDashboard) return;

    if (window.innerWidth > 768) {
      menu.classList.remove(typeMenu[0]);            // Quitar 'active' de menú base
      menuDashboard.classList.remove(typeMenu[1]);   // Quitar 'menu__bar--show' del dashboard
    }
  }

  function getGraphTypeFromFilename() {
    const filename = window.location.pathname.split('/').pop().toLowerCase();

    if (basePages.includes(filename)) {
      toggle?.addEventListener("click", () => renderToggle(menu, 0));
    } else if (PageSecond.includes(filename)) {
      toggle?.addEventListener("click", () => renderToggle(menuDashboard, 1));
    }
  }

  window.addEventListener('DOMContentLoaded', () => {
    menu = document.querySelector('.menu');
    menuDashboard = document.getElementById('menu-dashboard');
    toggle = document.getElementById('toggle');

    getGraphTypeFromFilename();
  });

  window.addEventListener('resize', handleToggle);
})();
