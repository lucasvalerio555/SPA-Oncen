let menu = document.querySelector('.menu');

// Devuelve un string HTML con ícono y texto
function createMenuItem(icon, label, URLPage) {
  return `
    <li class="menu__bar__li">
      <a href="${URLPage}" class="menu__bar__a" aria-label="${label}">
        <span class="material-icons-outlined material__move__slider">${icon}</span>
        <span class="menu__bar__label">${label}</span>
      </a>
    </li>`;
}

const URLPage = {
  "rol" : "systemRol.html",
  "viewServer":"viewServer.html",
  "duration":"duration.html",
  "SystemRegister":"systemRegister.html",
  "log":"logs.html",
  "setting":"setting.html",
  "logout": "#"
}


let sharedItems = [
  "item-rol",
  "item-viewServer",
  "item-duration",
  "item-registerSystem",
  "item-logs",
  "item-settings",
  "item-logout"
];

const menuItems = {
  "item-rol": createMenuItem("key", "Rol en el sistema", URLPage.rol),
  "item-viewServer": createMenuItem("wallpaper", "Ver Servidor",URLPage.viewServer),
  "item-duration": createMenuItem("timer", "Duración",URLPage.duration),
  "item-registerSystem": createMenuItem("person_add", "Registrar Sistema",URLPage.SystemRegister),
  "item-logs": createMenuItem("storage", "Ver Logs",URLPage.log),
  "item-settings": createMenuItem("settings", "Configuración",URLPage.setting),
  "item-logout": createMenuItem("logout", "Cerrar Sesión",URLPage.logout)
};

// Crea y renderiza los ítems del menú basados en el array dado
function renderMenuDashboard(items = []) {
  if (!menu) return;

  items.forEach(itemKey => {
    if (menuItems[itemKey]) {
      menu.insertAdjacentHTML('beforeend', menuItems[itemKey]);
    }
  });
}

// Detecta el tipo de dashboard según el archivo actual
function handleDashboardMenu() {
  const filename = window.location.pathname.split('/').pop().toLowerCase();
  let localItems = [...sharedItems];

  if (filename.includes("manager.html")) {
    renderMenuDashboard(localItems);

  } else if (filename.includes("admin.html")) {
    /*
    const index = localItems.indexOf("item-registerSystem");
    if (index !== -1) localItems.splice(index, 1);
    */
    renderMenuDashboard(localItems);

  } else {
     // Elimina 3 ítems específicos si existen
    const itemsToRemove =new Set(
    [
      "item-rol", 
      "item-logs", 
      "item-registerSystem"
    ]);
    localItems = localItems.filter(item => 
      !itemsToRemove.has(item)
    );
    renderMenuDashboard(localItems);
  }
}

window.addEventListener('DOMContentLoaded', handleDashboardMenu);

// This script dynamically renders a menu based on the current page and predefined items.
// It uses a function to create menu items and another to render them based on the current page.
// The menu adapts to different roles (like admin or manager) by including or excluding certain items.
// The menu is built using HTML strings and inserted into the DOM when the page loads.
// It listens for the DOMContentLoaded event to ensure the menu is rendered after the page is fully loaded.
// The menu items include icons and labels, and are structured as list items within a navigation menu.
// The script is designed to be modular and reusable, allowing for easy updates to the menu items
// or the addition of new items in the future.
// It also ensures that the menu is only rendered if the menu element exists in the DOM.
// The menu adapts to different user roles by conditionally rendering items based on the current page
// and the user's permissions. This allows for a flexible and dynamic user interface that can change
// based on the context of the application.
// The script is intended to be used in a single-page application (SPA) context, where
// the menu needs to be responsive to changes in the current view without requiring a full page reload.
// It leverages modern JavaScript features like template literals for cleaner HTML generation.