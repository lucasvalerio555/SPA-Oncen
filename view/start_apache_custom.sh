#!/bin/bash

# Configuración personalizada
APACHE_SERVICE="apache2"
URL="http://localhost"
WAIT_TIME=3  # segundos para esperar que Apache inicie

echo "🚀 Iniciando el servicio $APACHE_SERVICE..."

# Iniciar Apache si no está activo
if systemctl is-active --quiet $APACHE_SERVICE; then
    echo "✔ El servicio $APACHE_SERVICE ya está activo."
else
    sudo systemctl start $APACHE_SERVICE
    if [ $? -eq 0 ]; then
        echo "✔ Servicio $APACHE_SERVICE iniciado correctamente."
    else
        echo "❌ Error al iniciar $APACHE_SERVICE."
        exit 1
    fi
fi

# Habilitar para que arranque automáticamente
sudo systemctl enable $APACHE_SERVICE >/dev/null 2>&1
echo "🔔 Servicio $APACHE_SERVICE habilitado para inicio automático."

# Esperar para asegurar que Apache esté listo
echo "⏳ Esperando $WAIT_TIME segundos para que el servicio se estabilice..."
sleep $WAIT_TIME

# Verificar estado actual
STATUS=$(systemctl is-active $APACHE_SERVICE)
echo "📊 Estado actual del servicio: $STATUS"

if [ "$STATUS" == "active" ]; then
    # Abrir URL en navegador
    echo "🌐 Abriendo la página web en $URL ..."
    if command -v xdg-open >/dev/null 2>&1; then
        xdg-open "$URL" &>/dev/null &
        echo "✅ Página abierta en el navegador predeterminado."
    else
        echo "⚠️ No se encontró el comando xdg-open para abrir el navegador automáticamente."
        echo "Abre tu navegador y visita: $URL"
    fi
else
    echo "❌ El servicio no está activo. No se abrirá la página."
fi
