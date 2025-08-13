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
            setTimeout(() => {
                window.location.href = "register.html";
            }, 500);
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