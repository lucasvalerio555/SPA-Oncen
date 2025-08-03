<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <script src="https://kit.fontawesome.com/25b6da0530.js" crossorigin="anonymous"></script>
    <title>Panel Duración</title>
</head>

<body>
    <nav class="nav__bar" id="nav-menu">
        <div class="conteiner__toggle" id="toggle"> <span class="material-symbols-outlined"> menu </span> </div>
        <ul class="menu__bar__dashboard menu" id="menu-dashboard">
            <li class="menu__bar__li"> 
                <a class="menu__bar__a" href="admin.html">
                     <span class="material-icons-outlined material__move__slider">
                        home
                    </span>
                     Home 
                    </a>
                 </li>
            <li class="menu__bar__li"> 
                <a class="menu__bar__a" href="reserva.php">
                     <span class="material-icons-outlined material__move__slider">
                        edit_calendar
                    </span> 
                    Realizar Reserva
                </a> 
            </li>
            <li class="menu__bar__li"> 
                <a class="menu__bar__a" href="viewreserva.php"> 
                    <span class="material-icons-outlined material__move__slider">
                        receipt_long
                    </span>
                     Ver Reserva
                 </a>
            </li>
            <li class="menu__bar__li">
                 <a class="menu__bar__a" href="print.php"> 
                    <span class="material-icons-outlined material__move__slider">
                        print
                    </span> 
                    Imprimir Reserva
                 </a>
             </li>
        </ul>
    </nav>
    <main class="main">
        <header class="main__header header__main">
            <h1>
                <svg width="64px" height="64px" viewBox="0 0 91 91" enable-background="new 0 0 91 91" id="Layer_1"
                    version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000">

                    <g id="SVGRepo_bgCarrier" stroke-width="0" />

                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

                    <g id="SVGRepo_iconCarrier">
                        <g>
                            <g>
                                <path
                                    d="M23.932,87.037V64.561c0-10.395,10.014-20.498,22.377-20.498h0.639c12.359,0,22.371,10.104,22.371,20.498 v22.477"
                                    fill="#f6d32d" />
                                <path
                                    d="M23.932,3.638v22.48c0,10.395,10.014,20.501,22.377,20.501h0.639c12.359,0,22.371-10.106,22.371-20.501 V3.638"
                                    fill="#e5a50a" />
                                <path
                                    d="M75.018,6.978H16.561c-1.846,0-3.34-1.496-3.34-3.34c0-1.846,1.494-3.34,3.34-3.34h58.457 c1.842,0,3.34,1.494,3.34,3.34C78.357,5.481,76.859,6.978,75.018,6.978z"
                                    fill="#26a269" />
                                <path
                                    d="M75.018,90.377H16.561c-1.846,0-3.34-1.496-3.34-3.34s1.494-3.34,3.34-3.34h58.457 c1.842,0,3.34,1.496,3.34,3.34S76.859,90.377,75.018,90.377z"
                                    fill="#26a269" />
                            </g>
                        </g>
                    </g>

                </svg>
                Bienvenido a duración de servicio
            </h1>
            <p>Tiempo de finalización de la reserva sería</p>
            <span>19:45</span>
        </header>

        <section class="dashboard">
            <div class="dashboard__card">
                <h2 class="dashboard__title">Tiempo estimado de finalización de Reserva:</h2>
                <input type="time" id="end-time" name="end-time" value="19:45" step="900">
            </div>

            <div class="container__service">
                <h3>Servicio Elejido:</h3>
                <section class="selection__service">
                    <article class="crad__service">
                        <header class="header__servicio">
                            <div class="service__container__images">
                                <img src="Traslados.jpg" class="server__card__container__images__images"
                                    alt="serivicio de traslados">
                            </div>
                            <h3>Traslados</h3>
                        </header>
                        <div>
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus nobis aliquid cum
                                repellat sit tenetur asperiores vel fugit nesciunt quos ipsa debitis doloremque eveniet
                                rerum, at, aperiam sapiente temporibus dolore!
                            </p>
                        </div>
                    </article>

                    <article class="crad__service">
                        <header class="header__servicio">
                            <div class="service__container__images">
                                <img src="bicicletas.jpg" class="server__card__container__images__images"
                                    alt="servicio de bicicletas">
                            </div>
                            <h3>Bicicletas</h3>
                        </header>
                        <div>
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus nobis aliquid cum
                                repellat sit tenetur asperiores vel fugit nesciunt quos ipsa debitis doloremque eveniet
                                rerum, at, aperiam sapiente temporibus dolore!
                            </p>
                        </div>
                    </article>
                </section>
            </div>
        </section>
    </main>
    <script src="../js/RenderMenu.js"></script>
    <script src="../js/Toggle.js"></script> 
</body>

</html>
