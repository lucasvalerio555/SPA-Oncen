<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$basePath = __DIR__ . '/../';

require $basePath . 'config/config.php';
require_once $basePath . 'config/settingDB.php';
require_once $basePath . 'models/Router.php';
require_once $basePath . 'controllers/ValidationRegister.php';
require_once $basePath . 'controllers/ValidationMail.php';
require_once $basePath . 'controllers/ValidationLogin.php';
require_once $basePath . 'models/ModelsLogin.php';

$router = new \App\Models\Router();
$route = trim($_GET['route'] ?? 'index', '/');
$viewFile = $router->resolve($route);

// Aquí seguiría tu HTML y lógica
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="./css/style.css" />
<link rel="stylesheet" href="./css/style2.css" />
<meta name="keywords" content="SPA, spa termal, servicios, tranquilidad, relajación" />
<meta name="description" content="Somos un spa termal con diferentes tratamientos y masajes." />

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
 integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="icon" type="image/ico" href="./img/icon2.ico" />
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<title>SPA Termal Ocean</title>


<style>
/* Sliders ocupan todo el ancho**/ 
/* Contenedor "viewport": muestra solo un slide a la vez */
.container__slider {
  width: 100vw;
  overflow: hidden;
  position: relative;
  height: 400px; /* o la altura que quieras */
}

/* Flex container de los slides */
.slider {
  display: flex;
  transition: transform 0.7s ease;
}

/* Cada slide ocupa todo el ancho del viewport */
.slider__element {
  min-width: 100vw; /* <- clave: cada slide mide 100vw */
  height: 400px;    /* igual que el contenedor para que encaje */
}

/* Imagen ocupa todo el slide */
.slider__element > img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}



.contact-form__filed{
 position: relative;
}


/* Slider de cards */
.conteiner-card-info { display: flex; transition: transform 0.4s ease; padding-bottom: 1rem; }
.card-info {
  flex: 0 0 calc(33.333% - 1rem);
  margin: 0 0.5rem;
  background: #fff; border: 1px solid #ccc;
  border-radius: 6px; padding: 1rem;
  box-sizing: border-box;
  text-align: center;
}

/* Flechas cards */
.cardslider-nav {
  position: absolute; top: 50%;
  transform: translateY(-50%);
  background: rgba(0,0,0,0.5); color: #fff;
  border: none; font-size: 2rem; cursor: pointer;
  z-index: 2; padding: 0.3rem; border-radius: 50%;
}
.cardslider-nav.prev { left: 0.5rem; }
.cardslider-nav.next { right: 0.5rem; }

.contact-form__group{ width: 100%; line-height: 2; }
.aside__contact-form__title{ text-align: center; }
textarea{ width: 100%; height: 200px; resize: none; }
.conteiner-aise__aside > p{ line-height: 1.5; }
.conteiner-aise__aside{ margin-right: .2rem; }
.spa__logo{ height: 80px; transform: scale(1.3); }
.menu-bar__a{ margin-top: 20px; }
</style>

</head>
<body>
<div class="wrapper">
<header class="header-menu">
  <nav>
    <div class="conteiner-toggle">
      <i id="toggle" class="fas fa-bars"></i>
    </div>
    <ul class="menu-bar" id="menu">
      <li class="menu-bar__li"><a class="menu-bar__a" href="?route=services">Servicio</a></li>
      <li class="menu-bar__li"><a class="menu-bar__a" href="?route=contact">Contacto</a></li>
      <li class="menu-bar__li"><a href="index.php"><img class="spa__logo" src="./img/logo-spa.png" alt="logo" /></a></li>
      <li class="menu-bar__li"><a class="menu-bar__a" href="?route=login">Login</a></li>
      <li class="menu-bar__li"><a class="menu-bar__a" href="?route=register">Registrar</a></li>
    </ul>
  </nav>
</header>

<main class="section-main">
  <div id="app"></div>
</main>
<!-- <div id="footer"></div> -->
</div>

<script src="./js/Toggle.js"></script>
<!-- JS de Leaflet -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const app = document.getElementById('app');

// Slider principal automático con transición suave usando transform
// Slider principal automático con transición suave
// Slider principal

function setupImageSlider() {
  const slider = document.querySelector('.slider');
  const slides = document.querySelectorAll('.slider__element');
  const sliderImages = ['',''];
  const totalSlides = slides.length;
  let current = 0;

  const moveSlide = () => {
    current++;
    slider.style.transition = 'transform 0.5s ease-in-out';
    slider.style.transform = `translateX(-${current * 100}vw)`;
  };

  slider.addEventListener('transitionend', () => {
    if (current === totalSlides - 1) {
      slider.style.transition = 'none';
      slider.style.transform = 'translateX(0vw)';
      current = 0;
    }
  });

  setInterval(moveSlide, 5000);
}


// Slider manual (botones SVG)
function setupManualSliderButtons() {
  const slides = document.querySelectorAll('.slider__element');
  let current = 0;
  document.getElementById('button__left')?.addEventListener('click', 
  () => {
    slides[current].classList.remove('active');
    current = (current - 1 + slides.length) % slides.length;
    slides[current].classList.add('active');
  });
  document.getElementById('button__right')?.addEventListener('click', 
  () => {
    slides[current].classList.remove('active');
    current = (current + 1) % slides.length;
    slides[current].classList.add('active');
  });
}

// Slider de cards (dinámico)
function setupCardSlider(data) {
  const container = document.querySelector('.conteiner-card-info');
  const prevBtn = document.querySelector('.cardslider-nav.prev');
  const nextBtn = document.querySelector('.cardslider-nav.next');
  const cardsPerPage = 3;
  let currentPage = 0;
  const totalPages = Math.ceil(data.infoCards.length / cardsPerPage);
  function renderCards() {
    const start = currentPage * cardsPerPage;
    const currentCards = data.infoCards.slice(start, start + cardsPerPage);
    container.innerHTML = currentCards.map(card => `
      <div class="card-info" style="position: relative;">
        <div class="card-info__conteiner-images">
          <img src="${card.image}" class="card-info__images">
         </div>
        <header>
             <h2 class="card-info__span">${card.title}</h2>
         </header>
        <p class="card-info__paragraph">${card.description}</p>
        <div>
          <a href="${card.link}" class="card-info__a">
           Más Información
         </a>
       </div>
      </div>
    `).join('');
  }
  renderCards();
  prevBtn?.addEventListener('click', () => {
  if (currentPage > 0) { 
     currentPage--; renderCards(); 
  } });
  nextBtn?.addEventListener('click', () => {
    currentPage = (currentPage + 1) % totalPages;
    renderCards();
  });
}


// Renderiza la home (slider principal + botones + cards)
const renderHome = (data) => `
  <div class="container__slider">
    <div class="slider">
      ${data.sliderImages.map((url, i) => `
        <div class="slider__element ${i === 0 ? 'active' : ''}">
          <img src="${url}" class="slider__element--images">
        </div>
      `).join('')}
      <div class="slider__element"> 
        <img src="${data.sliderImages[0]}" class="slider__element--images">
      </div>
    </div>

    <div class="container__slider--button">
      <svg id="button__left" xmlns="http://www.w3.org/2000/svg" width="85px" height="85px" viewBox="-11 -11.5 65 66">
        <g>
          <path fill="#474544"
            d="M-10.5,22.118C-10.5,4.132,4.133-10.5,22.118-10.5S54.736,4.132,54.736,22.118
            c0,17.985-14.633,32.618-32.618,32.618S-10.5,40.103-10.5,22.118z M-8.288,22.118c0,16.766,13.639,30.406,30.406,30.406
            c16.765,0,30.405-13.641,30.405-30.406c0-16.766-13.641-30.406-30.405-30.406C5.35-8.288-8.288,5.352-8.288,22.118z" />
          <path fill="#474544"
            d="M25.43,33.243L14.628,22.429c-0.433-0.432-0.433-1.132,0-1.564L25.43,10.051
            c0.432-0.432,1.132-0.432,1.563,0c0.431,0.431,0.431,1.132,0,1.564L16.972,21.647l10.021,10.035
            c0.432,0.433,0.432,1.134,0,1.564c-0.215,0.218-0.498,0.323-0.78,0.323C25.929,33.569,25.646,33.464,25.43,33.243z" />
        </g>
      </svg>

      <svg id="button__right" xmlns="http://www.w3.org/2000/svg" width="85px" height="85px" viewBox="-11 -11.5 65 66">
        <g>
          <path fill="#474544"
            d="M22.118,54.736C4.132,54.736-10.5,40.103-10.5,22.118C-10.5,4.132,4.132-10.5,22.118-10.5
            c17.985,0,32.618,14.632,32.618,32.618C54.736,40.103,40.103,54.736,22.118,54.736z M22.118-8.288
            c-16.765,0-30.406,13.64-30.406,30.406c0,16.766,13.641,30.406,30.406,30.406c16.768,0,30.406-13.641,30.406-30.406
            C52.524,5.352,38.885-8.288,22.118-8.288z" />
          <path fill="#474544"
            d="M18.022,33.569c0.282,0-0.566-0.105-0.781-0.323c-0.432-0.431-0.432-1.132,0-1.564l10.022-10.035
            L17.241,11.615c0.431-0.432-0.431-1.133,0-1.564c0.432-0.432,1.132-0.432,1.564,0l10.803,10.814c0.433,0.432,0.433,1.132,0,1.564
            L18.805,33.243C18.59,33.464,18.306,33.569,18.022,33.569z" />
        </g>
      </svg>
    </div>
  </div>

  <div class="slider-menu__items">
     <div class="slider-menu__items">
  	${data.sliderImages.map((_, i) => `
    	<a href="#slider-${i+1}" class="slider-menu__item-next"></a>
  	`).join('')}
     </div>
  </div>

  <div class="slider-cards-container" style="position: relative;">
    <button class="cardslider-nav prev">
     <span class="material-icons">
       chevron_left
      </span>
    </button>
    <div class="conteiner-card-info">
      ${data.infoCards.map(card => `
        <div class="card-info">
          <div class="card-info__conteiner-images">
            <img src="${card.image}" class="card-info__images">
          </div>
          <header>
            <h2 class="card-info__span">
             ${card.title}
            </h2>
          </header>
          <p class="card-info__paragraph">
            ${card.description}
          </p>
          <div>
            <a href="${card.link}" class="card-info__a">
             Más Información
            </a>
          </div>
        </div>`).join('')}
    </div>
    <button class="cardslider-nav next">
     <span class="material-icons">
      chevron_right
     </span>
    </button>
  </div>
`;


const renderLoginForm = (data) => {
  const { titles, form } = data;
  return `
    <div class="conteiner-from" style="margin-top: 4rem;">
      <form action="index.php" class="${form.class}" method="post">
        <div class="${titles.class}">
          <span>${titles.main}</span>
        </div>

        ${form.inputs.map(input => `
          <div class="contact-form__filed">
            <div class="contact-from__conteiner-label">
              <label for="${input.id}" class="${input.labelClass}">${input.label}</label>
            </div>
            <input type="${input.id.includes('password') ? 
            'password' : 'text'}" 
                   id="${input.id}" 
                   name="${input.name}" 
                   class="${input.class}">
          </div>
        `).join('')}

        <p class="${form.forgotPasswordClass}">
          <a href="#">${form.forgotPasswordText}
        </a></p>

        <div class="contact-form__filed" id="Iniciar-Sesion">
          <input class="${form.submitClass}" name="submit__login" 
          type="submit" value="${form.submitText}">
        </div>

        <div class="contact-form__filed">
          <p>${form.socialLoginText}</p>
          <div class="contact-form__socials">
            ${form.socialButtons.map(button => `
              <button type="button" class="${button.class}" 
              onclick="location.href='${button.url}'">
                <i class="${button.icon}"></i> ${button.text}
              </button>
            `).join('')}
          </div>
        </div>
      </form>
    </div>
  `;
};

const generationMap = () =>{
  // Crear el mapa y centrarlo (ejemplo: Montevideo)
  let map = L.map('map').setView([-34.9056, -56.1882], 15);

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
     maxZoom: 19,
     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);	
  
  var polygon = L.polygon([
    [-34.906, -56.199],
    [-34.907, -56.197],
    [-34.905, -56.195]
], {
    color: 'green',
    fillColor: '#3f0',
    fillOpacity: 0.4
}).addTo(map);

polygon.bindPopup("Zona en 18 de Julio, Montevideo");

};

const renderPepolePartition = (data) => `
  <div class="conteiner-aside">
    <aside class="${data.aside.class}">
      <div class="container__avatar">
        ${data.aside.avatars.map(avatar => `
          <div class="container__avatar__images">
            <img src="${avatar.src}" alt="${avatar.alt}">
          </div>
        `).join('')}
      </div>

      <h3 class="container__avatar__text">
       ${data.aside.title}
      </h3>

      <p>${data.aside.description}</p>
         <h3 style="text-align: center;"> Mapa de Ubicación del Spa Termal Once</h3>
        <!-- Div del mapa agregado aquí -->
        <div id="map" style="height:400px; width: 90vw;
         margin-left: auto; margin-right: auto; 
         margin-top: 1.5rem;">
        </div>

      <div class="aside__contact-form">
        <h3 class="aside__contact-form__title">
         ${data.aside.contactForm.title}
        </h3>
        <form class="${data.aside.contactForm.formClass}" action="index.php" method="POST">
          ${data.aside.contactForm.fields.map(field => `
            <div class="contact-form__group">
              <label for="${field.id}" class="${field.labelClass}">${field.label}</label>
              ${field.type === 'textarea' 
                ? `<textarea id="${field.id}" name="${field.name}" class="${field.class}" placeholder="${field.placeholder}"></textarea>`
                : `<input type="${field.type}" id="${field.id}" name="${field.name}" class="${field.class}" placeholder="${field.placeholder}">`
              }
            </div>
          `).join('')}
          <button type="submit" name="submit_contact" class="${data.aside.contactForm.submitClass}">
          ${data.aside.contactForm.submitText}
          </button>
        </form>
      </div>
    </aside>
  </div>
`;

const renderService = () => {
  // Textos que vamos a mostrar
  const TITLE_TEXT = '🚧 Servicio no disponible';
  const DESCRIPTION_TEXT = 'Lo sentimos, el servicio no está disponible en este momento.';
  const RETRY_TEXT = 'Vuelve a intentarlo más tarde.';
  const GIF_URL = 'https://media.giphy.com/media/3o7aD6t7WvC0JH3v68/giphy.gif'; // Cambia por el que quieras
  const GIF_ALT = 'Persona caminando de puntillas';

  // Función auxiliar para crear elementos con texto
  const createTextElement = (tag, text) => {
    const element = document.createElement(tag);
    element.textContent = text;
    return element;
  };

  // Crear el título y los textos
  const titleElement = createTextElement('h1', TITLE_TEXT);
  const descriptionElement = createTextElement('p', 
  DESCRIPTION_TEXT);
  const retryElement = createTextElement('p', RETRY_TEXT);

/**
  // Crear el elemento de imagen (GIF)
  const gifElement = document.createElement('img');
  gifElement.src = GIF_URL;
  gifElement.alt = GIF_ALT;
  gifElement.style.display = 'block';
  gifElement.style.margin = '1rem auto';
  gifElement.style.width = '150px'; // Ajusta el tamaño según prefieras

**/
  // Crear contenedor y aplicar estilos comunes
  const container = document.createElement('div');
  container.style.marginTop = '4rem';
  container.style.textAlign = 'center';

  // Añadir todos los elementos al contenedor
  container.appendChild(titleElement);
  container.appendChild(descriptionElement);
  container.appendChild(retryElement);

  // Insertar el contenedor en el cuerpo del documento
  document.body.appendChild(container);
};




const renderFooter = (data) => `
<footer class="${data.footer.class}">
  <div class="container__icon">
    ${data.footer.socialLinks.map(link => link.html).join('')}
  </div>

  <p class="text_descrition">
   ${data.footer.description}
  </p>

  <div class="section__main">
    ${data.footer.sections.map(section => `
      <article class="${section.class}">
        <header class="section__main--cards__header">
          <p class="text__title">${section.title}</p>
        </header>
        ${section.content.map(text =>
         `<p class="text">
           ${text}
          </p>`).join('')}
      </article>
    `).join('')}
  </div>

  <p class="paragraph__Copyright">
    ${data.footer.copyright}
  </p>
</footer>`;

const renderRegisterForm = (data) => `
  <section class="form-section" style="margin-top: 2rem;">
    <h2 class="${data.titleClass}">Registro</h2>
    <form class="${data.formClass}" action="index.php" method="post">
      ${data.formFields.map(field => `
        <div class="contact-form__filed">
          <label for="${field.name}" class="${field.labelClass}">
            ${field.label}:
          </label>
          <input type="${field.type}" name="${field.name}" 
          id="${field.name}" class="${field.inputClass}" 
          ${field.required ? 'required' : ''} />
        </div>`).join('')}
      <fieldset class="${data.genderFieldsetClass}">
        <legend>Sexo:</legend>
        ${data.genderOptions.map(opt => `
          <div class="conteiner__radio">
            <div class="contact-form__filed radio">
              <label class="${opt.labelClass}">
                <input type="radio" name="gender" value="${opt.value}"
                required /> ${opt.label}
              </label>
            </div>
          </div>`).join('')}
      </fieldset>
      <div class="contact-form__filed submit">
        <button type="submit" name="submit" class="${data.submitClass}">
         ${data.submitText}
        </button>
      </div>
    </form>
  </section>
`;

// Controlador de rutas
// Cargar página principal
const renderPage = (route) => {
  if(route === 'login'){
    fetch('./json/Data_SpaLogin.json')
    .then(r => r.json())
    .then(d => { app.innerHTML = renderLoginForm(d); });
  } else if(route === 'register'){
    fetch('./json/Data_SpaRegister.json')
    .then(r => r.json())
    .then(d => { app.innerHTML = renderRegisterForm(d); });
  }else if (route === 'services'){ 
    app.innerHTML = renderService();
  } else {
    fetch('./json/Data_SpaIndex.json')
    .then(r => r.json())
    .then(d => {
      app.innerHTML = renderHome(d);
      requestAnimationFrame(() => {
      setupImageSlider();
      setupCardSlider(d);
     });
   })
   .then(() => fetch('./json/Data_SpaAboutMe.json'))
   .then(r2 => r2.json())
   .then(data2 => {
    app.insertAdjacentHTML('beforeend', renderPepolePartition(data2));
    generationMap();
  })
  .catch(err => console.error('Error cargando datos JSON:', err));
 }
};

// Cargar footer
function loadFooter() {
  fetch('./json/Data_SpaLogin.json')
    .then(r => r.json())
    .then(d => {
      document.body.insertAdjacentHTML('beforeend', renderFooter(d));
    })
    .catch(err => console.error('Error cargando footer:', err));
}

// Ejecutar al cargar el DOM
window.addEventListener('DOMContentLoaded', () => {
  loadFooter();
  const initialRoute=new URL(window.location.href).searchParams.get('route')||'index';
  renderPage(initialRoute);
});

window.addEventListener('popstate',()=> {
  const route=new URL(window.location.href).searchParams.get('route')
  ||'index';
  renderPage(route);
});

</script>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Procesar formulario de registro
$registerResult = ValidationRegister::processForm();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
    try {
  	$config = new config();
  	$db = new SettingDB($config::configDB());

  	echo ValidationRegister::generateToastScript(
     	$registerResult['success'] ? 'success' : 'error',
     	$registerResult['success'] ? 
      '¡¡¡Registrado correctamente!!!' : 'No se pudo registrar.',
     	$registerResult['success'] ?
       '#4caf50' : '#e74c3c'
  	);
   }catch(PDOException $e) {
  	error_log("DB connection error: ". $e->getMessage());
  	echo "<script>
  		 Swal.fire({
  		 icon:'error',title:
  		 '❌ Error de conexión.',
  		 showConfirmButton:false,timer:2500});
  	      </script>";
       }
    }

    if (isset($_POST['submit_contact'])) {
        error_log("POST recibido: " . print_r($_POST, true));

        $firstName = trim($_POST['name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $userMsg   = trim($_POST['message'] ?? '');

        error_log("Valores recibidos - Nombre: '$firstName', Email: '$email', Mensaje: '$userMsg'");

        // Validar campos vacíos
        if ($firstName === '' || $email === '' || $userMsg === '') {
            echo "<script>
            	      Swal.fire({icon:'warning',title:
            	     '⚠️ Debes rellenar todos los campos !!!',
            	     showConfirmButton:false,timer:2500});
            	  </script>";
        } else {
            $mailService = new ValidationMail();
            $mailResult = $mailService->validateAndSend($firstName, $email, $userMsg);

            echo match ($mailResult) {
                'success' => "<script>Swal.fire({
                		      icon:'success',title:
                		      '✅ Mensaje enviado correctamente !!!',
                		      showConfirmButton:false,timer:2500});
                	     </script>",
                'error'   => "<script>Swal.fire({
                	      	icon:'error', title:
                	          '❌ Error al enviar el mensaje !!!',
                	          showConfirmButton:false,timer:2500});
                	      </script>",
                default   => "<script>
                	      	Swal.fire({
                	      	icon:'error',title:
                	      	'❌ Error desconocido !!!',
                	      	showConfirmButton:false,timer:2500});
                	      </script>",
            };
        }
    }
    
    if(isset($_POST['submit__login'])){
     	echo "zona de lógin.....";
     	$login =new ValidationLogin();
	    $models =new ModelsLogin();

	    $login->login($models);

      $enable = $login->ValidationField(
      $models->getEmail(),
      $models->getPassword());
      
	    if(!$enable){
	      echo 'Login....';
	      if(isset($_POST['submit-google'])){
	        $login->SignLoginGoogle();
	        $login->requestCode($_GET['code']);
	      
	      }else if(isset($_POST['submit-facebook'])){
	         echo 'Login facebook!!!';
	      }	
	    }
   }
}
?>

</body>
</html>

