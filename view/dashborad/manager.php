<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Gerente</title>

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


      <!-- Botón de menú (único para todas las páginas) -->
      <div class="conteiner__toggle" id="toggle">
        <span class="material-symbols-outlined">menu</span>
      </div>

      <!-- Menú dashboard -->
      <ul class="menu__bar__dashboard menu" id="menu-dashboard">

        <div class="conteiner__icon-profile">
          <a class="menu__bar__a" href="manager.php">
            <span class="material-symbols-outlined">
              person
            </span>
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
    <main class="main">
      <!-- Aquí va el contenido de tu página -->
       <div class="dashboard">
                <article class="dashboard__article">
                    <header class="dashboard__header">
                        <h2>Panel de Control</h2>
                        <div class="info-container">
                            <ul class="info-ul">
                                <li class="info-ul__li">
                                    reservas de hoy: <strong>45</strong>
                                </li>
                                <li class="info-ul__li">
                                    Ingreso: <strong>$U 20.000</strong>
                                </li>
                                <li>
                                    Capacidad ocupada: <strong>75%</strong>
                                </li>
                            </ul>
                        </div>
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
                            <tr>
                                <td>Piscina</td>
                                <td>2025-06-10</td>
                                <td>$U 1.000</td>
                            </tr>
                            <tr>
                                <td>Masaje oriental</td>
                                <td>2025-06-10</td>
                                <td>$U 2.000</td>
                            </tr>
                        </tbody>
                    </table>
                </article>
            </div>
    </main>

    <!-- Scripts -->
    <script src="../js/RenderMenu.js"></script>
    <script src="../js/Toggle.js"></script>
    <script src="../js/Chart.js"></script>
    <script type="module" src="../js/RenderGraph.js"></script>
    <script src="../js/MoveArrowScroll.js"></script>
  </div>
</body>

</html>