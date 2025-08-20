// ===================================================
// 🔹 Variables globales
// ===================================================
let currentIndex = 0;
let totalCardsGlobal = 0;

const cards = document.querySelectorAll('.card');
const indicators = document.querySelectorAll('.indicator');
const leftBtn = document.getElementById('leftBtn');
const rightBtn = document.getElementById('rightBtn');
const buttonReserva = document.getElementById('buttonReserva');
const buttonAcompanante = document.getElementById('buttonAcompanante');
const cardSelect = document.getElementById('cardSelect');
const form = document.getElementById('reservaForm'); // ⚡ form definido


const reservaData = {
        validation: {
            requiredFields: [
                { id: "nombre", message: "El nombre es obligatorio" },
                { id: "apellido", message: "El apellido es obligatorio" },
                { id: "fecha", message: "La fecha es obligatoria" },
                { id: "hora", message: "La hora es obligatoria" },
                { id: "servicio", message: "Debe seleccionar un servicio" }
            ],
            cardValidation: {
                emptyMessage: "Debe ingresar un número de tarjeta",
                invalidMessage: "Número de tarjeta inválido"
            },
            successMessage: "¡Reserva procesada exitosamente!"
        }
    };


// ===================================================
// 🔹 Funciones auxiliares
// ===================================================

// Muestra una tarjeta en el índice dado
function showCard(index) {
    cards.forEach((card, i) => {
        card.style.display = i === index ? 'block' : 'none';
    });
    indicators.forEach((ind, i) => {
        ind.classList.toggle('active', i === index);
    });
    currentIndex = index;
}

// Navegación entre tarjetas
function navigateCards(step) {
    const newIndex = (currentIndex + step + totalCardsGlobal) % totalCardsGlobal;
    showCard(newIndex);
}

// Formatea el número de tarjeta
function formatCardNumber(value) {
    return value
        .replace(/\D/g, '')                 // Solo dígitos
        .substring(0, 19)                   // Máximo 19 dígitos
        .replace(/(\d{4})(?=\d)/g, '$1 ')   // Espacios cada 4
        .trim();
}

// Devuelve el input de la tarjeta actual
function getCurrentCardInput() {
    return cards[currentIndex]?.querySelector('.card-number input') || null;
}

// Recopila datos del formulario dinámicamente
function getFormData() {
    return {
        nombre: document.getElementById('nombre')?.value.trim() || "",
        apellido: document.getElementById('apellido')?.value.trim() || "",
        fecha: document.getElementById('fecha')?.value || "",
        hora: document.getElementById('hora')?.value || "",
        servicio: document.getElementById('servicio')?.value || "",
        numeroTarjeta: getCurrentCardInput()?.value.replace(/\s/g, "") || "",
        generarPDF: document.getElementById('pdf')?.checked || false,
        tipoTarjeta: cardSelect?.value || "",
        comentarios: document.getElementById('comentarios')?.value.trim() || ""
    };
}

// Validación Luhn
function validarTarjetaLuhn(num) {
    let arr = num.split('').reverse().map(x => parseInt(x));
    let sum = arr.reduce((acc, val, i) => {
        if (i % 2 === 1) {
            val *= 2;
            if (val > 9) val -= 9;
        }
        return acc + val;
    }, 0);
    return sum % 10 === 0;
}

// Simulación detección de tipo de tarjeta
function detectCardType(cardNumber) {
    if (cardNumber.startsWith("4")) return "Visa";
    if (cardNumber.startsWith("5")) return "MasterCard";
    return "Desconocida";
}

// ===================================================
// 🔹 addEvent Mejorado
// ===================================================
function addEvent(typeComponent, eventType, component, extraData) {
    if (!component) {
        console.warn(`Component not found for ${typeComponent}`);
        return;
    }

    const handlers = {
        input: (e) => {
            e.target.value = formatCardNumber(e.target.value);
        },
        
        leftBtn: () => navigateCards(-1),
        rightBtn: () => navigateCards(1),
        keydown: (e) => {
            const actions = {
                ArrowLeft: () => leftBtn?.click(),
                ArrowRight: () => rightBtn?.click(),
            };
            actions[e.key]?.();
        },
        
        indicators: () => showCard(extraData),
        buttonReserva: (e) => {
            e.preventDefault();
            submitForm();
        },
        
        buttonAcompanante: (e) => {
            e.preventDefault();
            console.log("Redirigiendo a acompañante...");
            setTimeout(() => window.location.href = "acompanante.html", 500);
        }
    };

    const handler = handlers[typeComponent];
    if (handler) {
        component.addEventListener(eventType, handler);
    } else {
        console.warn(`Unknown component type: ${typeComponent}`);
    }
}

// ===================================================
// 🔹 Submit mejorado
// ===================================================
function submitForm() {

    const formData = getFormData();

    // Validar campos obligatorios
    for (const field of reservaData.validation.requiredFields) {
        const input = document.getElementById(field.id);
        if (!input.value.trim()) {
            alert(field.message);
            input.focus();
            return;
        }
    }

    // Validar tarjeta
    if (!formData.numeroTarjeta) {
        alert(reservaData.validation.cardValidation.emptyMessage);
        return;
    }
    if (!validarTarjetaLuhn(formData.numeroTarjeta)) {
        alert(reservaData.validation.cardValidation.invalidMessage);
        return;
    }

    // Todo ok
    const cardType = detectCardType(formData.numeroTarjeta);
    console.log("Datos de la reserva:", { ...formData, cardType });
    alert(reservaData.validation.successMessage);
}

// ===================================================
// 🔹 Inicialización Limpia
// ===================================================
function initializeReservaForm(totalCards) {
    totalCardsGlobal = totalCards;

    // Inputs de tarjeta
    cards.forEach((card, i) => {
        const input = card.querySelector('.card-number input');
        if (input) addEvent('input', 'input', input, i);
    });

    // Navegación
    addEvent('leftBtn', 'click', leftBtn);
    addEvent('rightBtn', 'click', rightBtn);
    addEvent('keydown', 'keydown', document);

    // Indicadores
    indicators.forEach((ind, i) => addEvent('indicators', 'click', ind, i));

    // Botones principales
    addEvent('buttonReserva', 'click', buttonReserva);
    addEvent('buttonAcompanante', 'click', buttonAcompanante);

    // Mostrar primera tarjeta
    if (cards.length > 0) showCard(0);
}

// ===================================================
// 🔹 Auto-inicialización
// ===================================================
document.addEventListener('DOMContentLoaded', () => {
    if (cards.length > 0) {
        console.log(`Inicializando formulario con ${cards.length} tarjetas`);
        initializeReservaForm(cards.length);
    } else {
        console.error("No se encontraron tarjetas en el DOM");
    }
});

