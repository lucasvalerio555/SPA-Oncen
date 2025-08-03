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
    <title>Panel Rol de Usuarios</title>
</head>

<body>
    <nav class="nav__bar" id="nav-menu">
        <div class="conteiner__toggle" id="toggle"> <span class="material-symbols-outlined"> menu </span> </div>
        <!-- <div class="conteiner__icon-profile">            <i class="fas fa-user mr-3"></i>        </div>    -->
        <ul class="menu__bar__dashboard menu" id="menu-dashboard">
            <li class="menu__bar__li"> <a class="menu__bar__a" href="admin.html"> <span
                        class="material-icons-outlined material__move__slider">home</span> Home </a> </li>
            <li class="menu__bar__li"> <a class="menu__bar__a" href="reserva.html"> <span
                        class="material-icons-outlined material__move__slider">edit_calendar</span> Realizar Reserva
                </a> </li>
            <li class="menu__bar__li"> <a class="menu__bar__a" href="viewreserva.html"> <span
                        class="material-icons-outlined material__move__slider">receipt_long</span> Ver Reserva </a>
            </li>
            <li class="menu__bar__li"> <a class="menu__bar__a" href="print.html"> <span
                        class="material-icons-outlined material__move__slider">print</span> Imprimir Reserva </a> </li>
        </ul>
    </nav>
    <main class="main"> </main>
    <script src="../js/RenderMenu.js"></script>
    <script src="../js/Toggle.js"></script> <!--<script  src="../js/RenderGraph.js"></script>-->
</body>

</html>