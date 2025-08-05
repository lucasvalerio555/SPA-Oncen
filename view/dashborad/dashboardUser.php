<?php
// Aquí puedes agregar lógica PHP si la necesitas
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Administrador</title>

  <!-- Iconos y fuentes -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <script src="https://kit.fontawesome.com/25b6da0530.js" crossorigin="anonymous"></script>

  <!-- Estilos -->
  <link rel="stylesheet" href="../css/normalize.css" />
  <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
  <div class="wrapper wrapper_dashboard">
    <!-- Navbar -->
    <nav class="nav__bar" id="nav-menu">
      <!-- Botón de menú -->
      <div class="conteiner__toggle" id="toggle">
        <span class="material-symbols-outlined">menu</span>
      </div>

      <!-- Menú dashboard -->
      <ul class="menu__bar__dashboard menu" id="menu-dashboard">
        <div class="conteiner__icon-profile">
          <a class="menu__bar__a" href="admin.php">
            <span class="material-symbols-outlined">person</span>
          </a>
        </div>

        <li class="menu__bar__li">
          <a class="menu__bar__a" href="reserva.php">
            <span class="material-icons-outlined material__move__slider">edit_calendar</span>
            Realizar Reserva
          </a>
        </li>

        <li class="menu__bar__li">
          <a class="menu__bar__a" href="viewreserva.php">
            <span class="material-icons-outlined material__move__slider">receipt_long</span>
            Ver Reserva
          </a>
        </li>

        <li class="menu__bar__li">
          <a class="menu__bar__a" href="print.php">
            <span class="material-icons-outlined material__move__slider">print</span>
            Imprimir Reserva
          </a>
        </li>
      </ul>

      <div class="container_arrow">
        <div class="arrow-down" id="scroll-arrow"></div>
      </div>
    </nav>

    <!-- Contenido principal -->
    <main class="main" id="app"></main>

    <!-- Scripts -->
    <script>
      const app = document.getElementById('app');

      const RenderDasboardAdmin = (data) => {
        const { card, services } = data.dashboard;

        const settingsHTML = Array.from({ length: card.settingsCount }, () => `
          <div class="item__setting"></div>
        `).join('');

        const servicesHTML = services.images.map(img => `
          <div class="server__card__container__images">
            <img src="${img.src}" class="server__card__container__images__images" alt="${img.alt}">
          </div>
        `).join('');

        app.innerHTML = `
          <div class="container__card__space">
            <div class="card__space">
              <div class="container__icon__setting">
                ${settingsHTML}
              </div>
              <div class="card__space__conatainer__images">
                <img src="${card.imageSrc}" alt="Logo Spa">
              </div>
              <p>${card.title}</p>
              <p class="card__text">${card.message}</p>
              <div class="card__space_conainer_form">
                <form action="dashboardUser.php" method="POST">
                  <input type="text" placeholder="${card.placeholder}">
                  <button type="submit">
                    <span class="material-symbols-outlined icon__button">send</span>
                  </button>
                </form>
              </div>
            </div>
          </div>

          <div class="server__card">
            <p class="card__title">${services.title}</p>
            ${servicesHTML}
            <div class="container-chart">
              <canvas id="${services.chartId}"></canvas>
            </div>
          </div>
        `;
      };
      
      const RenderDashboardManager=(data)=>{
            const RenderDashboardManager = (data) => {
  	    const { resumen, reservas } = data.manager;

  	   const resumenHTML = `
    		<h2>Panel de Control</h2>
    		<div class="info-container">
      		  <ul class="info-ul">
        	   <li class="info-ul__li">
          	     reservas de hoy: <strong>${resumen.reservasHoy}</strong>
        	  </li>
        	  <li class="info-ul__li">
          	    Ingreso: <strong>${resumen.ingreso}</strong>
        	 </li>
                 <li>
          	   Capacidad ocupada: <strong>${resumen.capacidad}</strong>
        	 </li>
     	     </ul>
    	  </div>
         `;

  	const reservasHTML = reservas.map(reserva => `
    	      <tr>
      		<td>${reserva.servicio}</td>
      		<td>${reserva.fecha}</td>
      		<td>${reserva.precio}</td>
   	     </tr>
  	  `).join('');

  	app.innerHTML = `
    	<div class="dashboard">
      	   <article class="dashboard__article">
             <header class="dashboard__header">
          	${resumenHTML}
             </header>
             <div class="chart-container-manager">
          	<canvas id="Chart"></canvas>
            </div>
      	  </article>

      	  <article class="dashboard__article">
            <header class="dashboard__header">
              <h3>Reservas</h3>
           </header>

           <table>
             <thead>
                <tr>
              	  <th>Servicio</th>
                  <th>Fecha</th>
                  <th>Precio</th>
                </tr>
              </thead>
          <tbody>
            ${reservasHTML}.addEventListener('click', () => {
	      ${reservasHTML}.innerHTML = '<input type="text"></input>';
	      ${reservasHTML}.focus();
	      ${reservasHTML}.innerHTML = '<button>Guardar</button>';
	      ${reservasHTML}.innerHTML = '<button>Cancelar</button>';
	    });
          </tbody>
        </table>
      </article>
    </div>
  `;
};
      };
      
      
const RenderDashboardClient = (data) => {
  const { servicios, comentarios } = data.client;

  const serviciosHTML = servicios.map(servicio => `
    <article class="card">
      <header>
        <div class="container__images">
          <img src="${servicio.imagen}" alt="Avatar" class="imagen ${servicio.alt}">
        </div>
        <h2 class="card__title">${servicio.titulo}</h2>
      </header>
      <p class="card__text">${servicio.descripcion}</p>
    </article>
  `).join('');

  const comentariosHTML = comentarios.map(c => `
    <p>${c}</p>
  `).join('');

  app.innerHTML = `
    <section class="section__card">
      ${serviciosHTML}
    </section>

    <section class="section__card__commit section__commit">
      <article class="card__commit commit__card">
        <h2>Comentarios...</h2>
        <div class="container__icon__setting icon__container">
          <div class="item__setting items__setting"></div>
          <div class="item__setting items__setting"></div>
          <div class="item__setting items__setting"></div>
        </div>

        <div class="container__commit commit__container">
          <form action="#" class="form__commit commit__form" method="post">
            <input type="text" class="container__commit--input" placeholder="Dejar un Comentario..." />
            <button class="container__commit--input__submit image_send">
              <span class="material-symbols-outlined icon__button">send</span>
            </button>
            <div class="container__commit__containaer__like">
              <span class="material-symbols-outlined">thumb_up</span>
              <span class="material-symbols-outlined">thumb_down</span>
            </div>
          </form>
        </div>

        ${comentariosHTML}
      </article>
    </section>
  `;
};
      
      
      const RenderDashboardDuration = (data) => {
  const container = document.createElement("div");

  // Header principal
  const header = document.createElement("header");
  header.className = "main__header header__main";

  const svgIcon = (data.header.svg && data.header.svg.enabled && data.header.svg.markup)
    ? data.header.svg.markup
    : "";

  header.innerHTML = `
    <h1>
      ${svgIcon}
      ${data.header.title}
    </h1>
    <p>${data.header.description}</p>
    <span>${data.header.endTimeDisplay}</span>
  `;
  container.appendChild(header);

  // Sección dashboard
  const dashboard = document.createElement("section");
  dashboard.className = "dashboard";

  dashboard.innerHTML = `
    <div class="dashboard__card">
      <h2 class="dashboard__title">${data.reservation.label}</h2>
      <input type="time" id="end-time" name="end-time"
        value="${data.reservation.timeInputValue}" step="${data.reservation.timeStep}">
    </div>
  `;

  // Contenedor de servicios
  const serviceContainer = document.createElement("div");
  serviceContainer.className = "container__service";
  serviceContainer.innerHTML = `<h3>Servicio Elegido:</h3>`;

  const selectionService = document.createElement("section");
  selectionService.className = "selection__service";

  (data.services || []).forEach(service => {
    const card = document.createElement("article");
    card.className = "crad__service";

    card.innerHTML = `
      <header class="header__servicio">
        <div class="service__container__images">
          <img src="${service.image}" class="server__card__container__images__images" alt="${service.alt || service.title}">
        </div>
        <h3>${service.title}</h3>
      </header>
      <div>
        <p>${service.description}</p>
      </div>
    `;

    selectionService.appendChild(card);
  });

  serviceContainer.appendChild(selectionService);
  dashboard.appendChild(serviceContainer);
  container.appendChild(dashboard);

  // Insertar en el DOM
  const root = document.getElementById("app") || document.body;
  root.innerHTML = "";
  root.appendChild(container);
};


     
      const RenderDashboardprint=(data)=>{
      
      
      };
      
      
      const RenderDashboardServer=(data)=>{
      
      };
      
      const RenderDashboardRegisterSystem=(data)=>{
      
      
      };
      
      const RenderDashboardLogin=(data)=>{
      
      
      };
      
      const RenderDashboardSystemRol=(data)=>{
      
      
      };
      
      const RenderDashboardReserva=(data)=>{
      
      
      };
      
      
      const RenderDashboardViewReserva=(data)=>{
      
      
      };
      
      
      const RenderDashboardViewServer=(data)=>{
      
      
      };

      // Mapeo de rutas JSON a funciones de renderizado
const dashboardRoutes = [
  {
    json: '../view/json/data_SpaDashboardUser.json',
    render: RenderDasboardAdmin,
    error: 'Error al cargar el JSON del administrador',
  },
  {
    json: '../view/json/Data_SpaDashboardManager.json',
    render: RenderDashboardManager,
    error: 'Error al cargar el JSON del manager',
  },
  {
    json: '../view/json/data_SpaDashboardClient.json',
    render: RenderDashboardClient,
    error: 'Error al cargar el JSON del cliente',
  },
  {
    json: '../view/json/Data_SpaDashboardDuration.json',
    render: RenderDashboardDuration,
    error: 'Error al cargar el JSON de duración del servicio',
  },
];

// Función reutilizable para cargar JSON y renderizar
const loadDashboard = async ({ json, render, error }) => {
  try {
    const response = await fetch(json);
    if (!response.ok) throw new Error(error);
    const data = await response.json();
    render(data);
  } catch (err) {
    console.error(err.message);
    // Aquí puedes mostrar un alert o mensaje visual en el DOM
  }
};

// Ejecutar todos los dashboards que apliquen
dashboardRoutes.forEach(route => loadDashboard(route));
</script>

    <script src="../js/RenderMenu.js"></script>
    <script src="../js/Toggle.js"></script>
    <script src="../js/Chart.js"></script>
    <script type="module" src="../js/RenderGraph.js"></script>
    <script src="../js/MoveArrowScroll.js"></script>
  </div>
</body>
</html>

