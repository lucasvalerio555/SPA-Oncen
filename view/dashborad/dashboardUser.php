<?php
// Aquí puedes agregar lógica PHP si la necesitas
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Panel Administrador - Reservas</title>

	<!-- Iconos y fuentes -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" />
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
	<script src="https://kit.fontawesome.com/25b6da0530.js" crossorigin="anonymous"></script>

	<!-- Estilos -->
	<link rel="stylesheet" href="../css/normalize.css" />
	<link rel="stylesheet" href="../css/style.css" />

	<!-- Estilos específicos para reservas -->
	<style>
		/* Estilos generales */
		* {
			box-sizing: border-box;
		}

		.container__form {
			max-width: 530px;
			width: 100%;
			height: auto;
			overflow-y: auto;
			background: #fff;
			border-radius: 2rem;
			box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
			position: relative;
			padding-bottom: 20px;
			margin: 0 auto;
			margin-top: 2rem;
		}

		/* Contenedores horizontales para formulario */
		.container_form_group {
			display: flex;
			gap: 1rem;
			padding: 0 1.5rem;
			margin-bottom: 1rem;
		}

		.container_form_group .form_group {
			flex: 1;
			padding-left: 0;
			padding-right: 0;
		}

		/* Estilos generales para grupos de formulario */
		.form_group {
			margin-bottom: 1rem;
			flex: 1;
			padding-left: 1rem;
			padding-right: 1rem;
		}

		.form_group label {
			display: block;
			color: #333;
			font-weight: 500;
			margin-bottom: 5px;
			font-size: 0.9rem;
		}

		.form_group input,
		.form_group select,
		.form_group textarea {
			width: 100%;
			padding: 10px;
			border: 2px solid #e0e0e0;
			border-radius: 8px;
			font-size: 0.9rem;
			transition: border-color 0.3s ease;
			background: #fff;
		}

		.form_group input:focus,
		.form_group select:focus,
		.form_group textarea:focus {
			outline: none;
			border-color: #82c262;
			box-shadow: 0 0 0 3px rgba(130, 194, 98, 0.1);
		}

		.form_group textarea {
			height: 80px;
			resize: vertical;
			font-family: inherit;
		}

		.form_group button {
			background: #82C262;
			border: none;
			color: #fff;
			padding: 12px 30px;
			border-radius: 8px;
			cursor: pointer;
			font-size: 1rem;
			font-weight: 500;
			transition: background 0.3s ease, transform 0.2s ease;
		}

		.form_group button:hover {
			background: #6fa851;
			transform: translateY(-1px);
		}

		/* Indicadores de tarjeta */
		.card-indicators {
			display: flex;
			justify-content: center;
			gap: 8px;
			margin: 20px 0;
			padding: 0 20px;
			margin-top: 3rem;
		}

		.indicator {
			width: 8px;
			height: 8px;
			border-radius: 50%;
			background: #ddd;
			cursor: pointer;
			transition: background 0.3s ease;
		}

		.indicator.active {
			background: #82c262;
		}

		/* Tarjetas */
		.container-card {
			position: relative;
			height: 220px;
			margin-bottom: 30px;
		}

		.group_form {
			position: absolute;
			bottom: -3rem;
			left: 6rem;
		}

		.group_form > button {
			width: 150px;
		}

		/* Base card styles */
		.card {
			width: 100%;
			max-width: 430px;
			height: 190px;
			border-radius: 1.2rem;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
			padding: 20px;
			color: #fff;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			opacity: 0;
			pointer-events: none;
			transition: opacity 0.5s ease, transform 0.5s ease;
			background-size: cover;
			background-repeat: no-repeat;
			z-index: 60;
		}

		.card.active {
			opacity: 1;
			pointer-events: all;
		}

		/* Fondos para diferentes tarjetas */
		.card.mastercard {
			background-color: #000;
		}

		.card.visa {
			background: linear-gradient(135deg, #0065b7, #00499b);
		}

		.card.oca {
			background: linear-gradient(to bottom, #4FB3E1 0%, #87CEEB 30%, #B0E0E6 100%);
		}

		.card.bbva {
			background: linear-gradient(135deg, #004481, #0066cc);
		}

		.card.prex {
			background: linear-gradient(135deg, #1e3c72, #2a5298);
		}

		/* Contenido de las tarjetas */
		.card-header {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
		}

		.card-brand {
			font-size: 1.5rem;
			font-weight: bold;
			text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
		}

		.container__chips {
			width: 50px;
			height: 35px;
			border-radius: 4px;
			background: linear-gradient(135deg, #f6e27a, #e3b647, #c89e29);
			box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.2);
			position: relative;
		}

		.chip-contact {
			position: absolute;
			background: #333;
			border-radius: 1px;
		}

		.chip-left { width: 8px; height: 20px; top: 7px; left: 5px; }
		.chip-center { width: 20px; height: 20px; top: 7px; left: 15px; }
		.chip-right { width: 8px; height: 20px; top: 7px; right: 5px; }

		.card-number input {
			font-family: 'Courier New', monospace;
			font-size: 1.4rem;
			letter-spacing: 2px;
			text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
			background: transparent;
			border: none;
			color: #fff;
			width: 100%;
		}

		.card-footer {
			display: flex;
			justify-content: space-between;
			align-items: flex-end;
		}

		.card-holder, .card-expiry {
			font-size: 0.8rem;
			text-transform: uppercase;
			text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
		}

		.card-logos {
			display: flex;
			align-items: center;
			gap: 5px;
		}

		.logo-circle {
			width: 25px;
			height: 25px;
			border-radius: 50%;
		}

		.logo-red { background: #eb001b; }
		.logo-orange { background: #f79e1b; }

		/* Controles de navegación de las tarjetas */
		.slider {
			list-style: none;
			display: flex;
			justify-content: space-between;
			position: absolute;
			width: 100%;
			height: 50px;
			top: 50%;
			transform: translateY(-50%);
			align-items: center;
			padding: 0 15px;
			margin: 0;
			z-index: 65;
		}

		.slider_left, .slider_rigth {
			cursor: pointer;
			background: rgba(255, 255, 255, 0.95);
			border-radius: 50%;
			width: 45px;
			height: 45px;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
			transition: background 0.3s ease, transform 0.2s ease;
			user-select: none;
		}

		.slider_left:hover, .slider_rigth:hover {
			background: #82c262;
			transform: scale(1.15);
		}

		.slider svg {
			fill: #333;
			width: 24px;
			height: 24px;
		}

		.slider_left:hover svg, .slider_rigth:hover svg {
			fill: #fff;
		}

		@media (max-width: 768px) {
			.container_form_group {
				flex-direction: column;
			}
			
			.container__form {
				max-width: 95%;
				margin-top: 1rem;
			}
			
			.group_form {
				position: relative;
				left: auto;
				bottom: auto;
				text-align: center;
				margin-top: 1rem;
			}
		}
	</style>
</head>

<body>
	<div class="wrapper wrapper_dashboard">
		<!-- Navbar -->
		<nav class="nav__bar" id="nav-menu">
			<!-- Botón de menú -->
			<div class="conteiner__toggle" id="toggle">
				<span class="material-symbols-outlined">
					menu
				</span>
			</div>

			<!-- Menú dashboard -->
			<ul class="menu__bar__dashboard menu" id="menu-dashboard">
				<div class="conteiner__icon-profile">
					<a class="menu__bar__a" href="admin.php">
						<span class="material-symbols-outlined">
						 person
					    </span>
					</a>
				</div>

				<li class="menu__bar__li">
					<a class="menu__bar__a" href="#" onclick="loadReservaForm()">
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

			<div class="container_arrow">
				<div class="arrow-down" id="scroll-arrow"></div>
			</div>
		</nav>

		<!-- Contenido principal -->
		<main class="main" id="app">
			<!-- Aquí se cargará el contenido dinámico -->
			<div style="text-align: center; padding: 2rem; color: #666;">
				<h2>Bienvenido al Panel de Administración</h2>
				<p>Selecciona "Realizar Reserva" del menú para comenzar.</p>
			</div>
		</main>

		<!-- Scripts -->
		<script>
			const app = document.getElementById('app');

			// Función RenderDashboardReserva adaptada
			const RenderDashboardReserva = (data) => {
				const {
					header,
					cards,
					formFields,
					cardTypes,
					navigation
				} = data.reserva;

				// Generar HTML para las tarjetas
				const cardsHTML = cards.map((card, index) => `
					<div class="card ${card.type} ${index === 0 ? 'active' : ''}" data-card="${card.type}">
						<div class="card-header">
							<div class="card-brand">${card.brand}</div>
							<div class="container__chips">
								<div class="chip-contact chip-left"></div>
								<div class="chip-contact chip-center"></div>
								<div class="chip-contact chip-right"></div>
							</div>
						</div>
						<div class="card-number">
							<input type="text" value="${card.number}" maxlength="19">
						</div>
						<div class="card-footer">
							<div class="card-holder">${card.holder}</div>
							<div class="card-expiry">${card.expiry}</div>
							<div class="card-logos">
								${card.logos ? card.logos.map(logo => `
									<div class="logo-circle logo-${logo}"></div>
								`).join('') : `<div style="font-weight: bold; font-size: 1.2rem;">${card.brand}</div>`}
							</div>
						</div>
					</div>
				`).join('');

				// Generar indicadores de tarjetas
				const indicatorsHTML = cards.map((_, index) => `
					<div class="indicator ${index === 0 ? 'active' : ''}" data-target="${index}"></div>
				`).join('');

				// Generar opciones del select de tarjetas
				const cardOptionsHTML = cardTypes.map(type => `
					<option value="${type.value}">${type.label}</option>
				`).join('');

				// Generar opciones del select de servicios
				const serviceOptionsHTML = formFields.servicio.options.map(option => `
					<option value="${option.value}">${option.label}</option>
				`).join('');

				app.innerHTML = `
					<div class="container__form">
						<form id="reservaForm">
							<article class="container-card">
								<ul class="slider">
									<li class="slider_left" id="left">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
											<path d="M15.293 3.293 6.586 12l8.707 8.707 1.414-1.414L9.414 12l7.293-7.293-1.414-1.414z"/>
										</svg>
									</li>
									<li class="slider_rigth" id="right">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
											<path d="M7.293 4.707 14.586 12l-7.293 7.293 1.414 1.414L17.414 12 8.707 3.293 7.293 4.707z"/>
										</svg>
									</li>
								</ul>

								${cardsHTML}

								<div class="form_group group_form">
									<button type="button" id="reserva">${navigation.reserva}</button>
									<button type="button" id="acompanante">${navigation.acompanante}</button>
								</div>
							</article>
							
							<!-- Indicadores de tarjeta -->
							<div class="card-indicators">
								${indicatorsHTML}
							</div>

							<!-- Campos del formulario -->
							<div class="container_form_group">
								<div class="form_group">
									<label for="nombre">${formFields.nombre.label}:</label>
									<input type="text" id="nombre" name="nombre" placeholder="${formFields.nombre.placeholder}" required>
								</div>

								<div class="form_group">
									<label for="apellido">${formFields.apellido.label}:</label>
									<input type="text" id="apellido" name="apellido" placeholder="${formFields.apellido.placeholder}" required>
								</div>
							</div>

							<div class="container_form_group">
								<div class="form_group">
									<label for="fecha">${formFields.fecha.label}:</label>
									<input type="date" id="fecha" name="fecha" required>
								</div>

								<div class="form_group">
									<label for="hora">${formFields.hora.label}:</label>
									<input type="time" id="hora" name="hora" required>
								</div>
							</div>

							<div class="container_form_group">
								<div class="form_group">
									<label for="servicio">${formFields.servicio.label}:</label>
									<select id="servicio" name="servicio" required>
										<option value="">${formFields.servicio.defaultOption}</option>
										${serviceOptionsHTML}
									</select>
								</div>

								<div class="form_group">
									<label for="pdf">${formFields.pdf.label}:</label>
									<select id="pdf" name="pdf">
										<option value="si">Sí</option>
										<option value="no">No</option>
									</select>
								</div>
							</div>

							<div class="form_group">
								<label for="tarjeta">${formFields.tarjeta.label}:</label>
								<select id="tarjeta" name="tarjeta">
									${cardOptionsHTML}
								</select>
							</div>

							<div class="form_group">
								<label for="comentarios">${formFields.comentarios.label}:</label>
								<textarea id="comentarios" name="comentarios" placeholder="${formFields.comentarios.placeholder}"></textarea>
							</div>

							<div class="form_group">
								<button type="submit">${header.submitButton}</button>
							</div>
						</form>
					</div>
				`;

				// Inicializar funcionalidad después de renderizar
				setTimeout(() => {
					initializeReservaForm(cards.length);
				}, 100);
			};

			// Función auxiliar para inicializar la funcionalidad del formulario
			function initializeReservaForm(totalCards) {
				const leftBtn = document.getElementById('left');
				const rightBtn = document.getElementById('right');
				const cards = document.querySelectorAll('.card');
				const indicators = document.querySelectorAll('.indicator');
				const form = document.getElementById('reservaForm');
				const cardSelect = document.getElementById('tarjeta');
				const buttonReserva = document.getElementById('reserva');
				const buttonAcompanante = document.getElementById('acompanante');

				let currentIndex = 0;

				// Función para mostrar tarjeta activa
				function showCard(index) {
					cards.forEach((card, i) => {
						card.classList.toggle('active', i === index);
						if (indicators[i]) {
							indicators[i].classList.toggle('active', i === index);
						}
					});

					currentIndex = index;
					const activeCard = cards[index];
					const tipo = activeCard.dataset.card;
					cardSelect.value = tipo;
				}

				// Validación Luhn para tarjetas
				function validarTarjetaLuhn(numero) {
					const num = numero.replace(/\D/g, '');
					
					if (num.length < 13 || num.length > 19) return false;
					
					let suma = 0;
					let debeDuplicar = false;

					for (let i = num.length - 1; i >= 0; i--) {
						let digito = parseInt(num.charAt(i), 10);

						if (debeDuplicar) {
							digito *= 2;
							if (digito > 9) digito -= 9;
						}

						suma += digito;
						debeDuplicar = !debeDuplicar;
					}

					return suma % 10 === 0;
				}

				// Formatear número de tarjeta
				cards.forEach(card => {
					const input = card.querySelector('.card-number input');
					if (input) {
						input.addEventListener('input', () => {
							let value = input.value.replace(/\D/g, '');
							value = value.substring(0, 19);
							input.value = value.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
						});
					}
				});

				// Navegación con botones
				if (leftBtn) {
					leftBtn.addEventListener('click', () => {
						let index = (currentIndex - 1 + totalCards) % totalCards;
						showCard(index);
					});
				}

				if (rightBtn) {
					rightBtn.addEventListener('click', () => {
						let index = (currentIndex + 1) % totalCards;
						showCard(index);
					});
				}

				// Navegación con indicadores
				indicators.forEach((indicator, index) => {
					indicator.addEventListener('click', () => {
						showCard(index);
					});
				});

				// Navegación con teclado
				document.addEventListener('keydown', (e) => {
					if (e.key === 'ArrowLeft' && leftBtn) {
						leftBtn.click();
					} else if (e.key === 'ArrowRight' && rightBtn) {
						rightBtn.click();
					}
				});

				// Botones de navegación
				if (buttonReserva) {
					buttonReserva.addEventListener('click', (e) => {
						e.preventDefault();
						// Mantener en la misma página
						console.log('Manteniendo formulario de reserva activo');
					});
				}

				if (buttonAcompanante) {
					buttonAcompanante.addEventListener('click', (e) => {
						e.preventDefault();
						setTimeout(() => {
							window.location.href = "acompanante.html";
						}, 500);
					});
				}

				// Validación del formulario
				if (form) {
					form.addEventListener('submit', (e) => {
						e.preventDefault();

						const requiredFields = [
							{ id: 'nombre', message: 'Por favor ingresa tu nombre' },
							{ id: 'apellido', message: 'Por favor ingresa tu apellido' },
							{ id: 'fecha', message: 'Por favor selecciona la fecha' },
							{ id: 'hora', message: 'Por favor selecciona la hora' },
							{ id: 'servicio', message: 'Por favor selecciona el servicio' }
						];

						// Validar campos obligatorios
						for (const field of requiredFields) {
							const input = document.getElementById(field.id);
							if (!input.value.trim()) {
								alert(field.message);
								input.focus();
								return;
							}
						}

						// Validar número de tarjeta
						const activeCard = cards[currentIndex];
						const numeroTarjeta = activeCard.querySelector('.card-number input').value;

						if (!numeroTarjeta.trim()) {
							alert('Por favor ingresa el número de tarjeta');
							return;
						}

						if (!validarTarjetaLuhn(numeroTarjeta)) {
							alert('Número de tarjeta inválido. Por favor verifica e intenta de nuevo.');
							return;
						}

						// Recopilar datos del formulario
						const formData = {
							nombre: document.getElementById('nombre').value,
							apellido: document.getElementById('apellido').value,
							fecha: document.getElementById('fecha').value,
							hora: document.getElementById('hora').value,
							servicio: document.getElementById('servicio').value,
							numeroTarjeta,
							generarPDF: document.getElementById('pdf').value,
							tipoTarjeta: cardSelect.value,
							comentarios: document.getElementById('comentarios').value.trim(),
						};

						console.log('Datos de la reserva:', formData);
						alert('¡Reserva enviada exitosamente!\nRevisa la consola para ver los datos.');
					});
				}

				// Inicializar primera tarjeta
				showCard(0);
			}

			// Función para cargar el formulario de reserva desde el menú
			const loadReservaForm = async () => {
				try {
					// Datos de ejemplo - deberías reemplazar esto con tu JSON real
					const reservaData = {
						reserva: {
							header: {
								title: "Sistema de Reservas",
								subtitle: "Spa Termal Onsen",
								submitButton: "Confirmar Reserva"
							},
							cards: [
								{
									type: "mastercard",
									brand: "MasterCard",
									number: "5555 4444 3333 2222",
									holder: "Juan Pérez",
									expiry: "12/28",
									logos: ["red", "orange"]
								},
								{
									type: "visa",
									brand: "VISA",
									number: "4532 1234 5678 9012",
									holder: "María García",
									expiry: "06/27",
									logos: null
								},
								{
									type: "oca",
									brand: "OCA",
									number: "6789 0123 4567 8901",
									holder: "Carlos López",
									expiry: "09/26",
									logos: ["red", "orange"]
								},
								{
									type: "bbva",
									brand: "BBVA",
									number: "4000 5678 9012 3456",
									holder: "Ana Rodríguez",
									expiry: "03/29",
									logos: null
								},
								{
									type: "prex",
									brand: "PREX",
									number: "5432 1098 7654 3210",
									holder: "Luis Fernández",
									expiry: "11/25",
									logos: null
								}
							],
							formFields: {
								nombre: {
									label: "Nombre",
									placeholder: "Ingresa tu nombre"
								},
								apellido: {
									label: "Apellido",
									placeholder: "Ingresa tu apellido"
								},
								fecha: {
									label: "Fecha de Reserva"
								},
								hora: {
									label: "Hora"
								},
								servicio: {
									label: "Servicio / Habitación",
									defaultOption: "Selecciona una opción",
									options: [
										{ value: "habitacion1", label: "Habitación 1" },
										{ value: "habitacion2", label: "Habitación 2" },
										{ value: "sala-reunion", label: "Sala de Reunión" },
										{ value: "suite", label: "Suite Presidential" },
										{ value: "spa-completo", label: "Paquete Spa Completo" },
										{ value: "masajes", label: "Sesión de Masajes" }
									]
								},
								pdf: {
									label: "Generar PDF"
								},
								tarjeta: {
									label: "Tipo de Tarjeta"
								},
								comentarios: {
									label: "Comentarios adicionales",
									placeholder: "Deja tu comentario..."
								}
							},
							cardTypes: [
								{ value: "mastercard", label: "Master Card" },
								{ value: "visa", label: "Visa" },
								{ value: "oca", label: "OCA" },
								{ value: "bbva", label: "Visa Internacional (BBVA)" },
								{ value: "prex", label: "PREX" }
							],
							navigation: {
								reserva: "Reserva",
								acompanante: "Acompañante"
							}
						}
					};

					RenderDashboardReserva(reservaData);

					/* 
					// Para usar con archivo JSON real, descomenta esto:
					const response = await fetch('../view/json/Data_SpaDashboardReserva.json');
					if (!response.ok) throw new Error('Error al cargar el JSON de reservas');
					const data = await response.json();
					RenderDashboardReserva(data);
					*/

				} catch (err) {
					console.error('Error al cargar formulario de reservas:', err.message);
					app.innerHTML = `
						<div style="text-align: center; padding: 2rem; color: #e74c3c;">
							<h3>Error al cargar el formulario</h3>
							<p>No se pudo cargar el formulario de reservas. Por favor, intenta de nuevo.</p>
						</div>
					`;
				}
			};
		</script>

		<script src="../js/Toggle.js"></script>
		<script src="../js/MoveArrowScroll.js"></script>
	</div>
</body>

</html>