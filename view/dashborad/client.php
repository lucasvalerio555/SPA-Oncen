<?php
// Puedes agregar aquí lógica PHP si lo necesitas, por ejemplo, para manejar sesiones o cargar datos dinámicos.
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Cliente</title>

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
          <a class="menu__bar__a" href="client.php">
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
    </nav>

    <!-- Contenido principal -->
    <main class="main">
      <!-- Aquí va el contenido de tu página -->
      <section class="section__card">
        <article class="card">
          <header>
            <div class="container__images">
              <img src="../img/bicicletas.jpg" alt="Avatar" class="imagen paseo en bicicleta">
            </div>
            <h2 class="card__title">title</h2>
          </header>

          <p class="card__text">
            Lorem ipsum dolor sit amet,
            consectetur adipiscing elit.
            Sed do eiusmod tempor incididunt ut
            labore et dolore magna aliqua.
          </p>
        </article>

        <article class="card">
          <header>
            <div class="container__images">
              <img src="../img/Traslados.jpg" alt="Avatar" class="imagen traslados">
            </div>
            <h2 class="card__title">title2</h2>
          </header>

          <p class="card__text">
            Lorem ipsum dolor sit amet,
            consectetur adipiscing elit.
            Sed do eiusmod tempor incididunt ut
            labore et dolore magna aliqua.
          </p>
        </article>

        <article class="card">
          <header>
            <div class="container__images">
              <img src="../img/botes.jpg" alt="Avatar" class="imagen paseo en bote">
            </div>
            <h2 class="card__title">title3</h2>
          </header>

          <p class="card__text">
            Lorem ipsum dolor sit amet,
            consectetur adipiscing elit.
            Sed do eiusmod tempor incididunt ut
            labore et dolore magna aliqua.
          </p>
        </article>
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
              <input type="text" class="container__commit--input" placeholder="Dejar un Comentario...">

              <button class="container__commit--input__submit image_send">
                 <span class="material-symbols-outlined icon__button">
                    send
                </span>
              </button>
              <div class="container__commit__containaer__like">
                <span class="material-symbols-outlined">
                  thumb_up
                </span>
                <span class="material-symbols-outlined">
                  thumb_down
                </span> 
              </div>
            </form>
          </div>
          <p>comentario:</p>
        </article>
      </section>
    </main>

    <!-- Scripts -->
    <script src="../js/RenderMenu.js"></script>
    <script src="../js/Toggle.js"></script>
    <script src="../js/Chart.js"></script>
  </div>
</body>

</html>
