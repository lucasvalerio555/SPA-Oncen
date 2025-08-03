#!/bin/bash

# ============================================
# Script para leer HTML, verificarlo, formatearlo
# y escribirlo en archivos especificados.
# ============================================

# 1. Pedir HTML desde terminal
echo "Ingresá el código HTML (finalizá con una línea vacía):"
html=""
while IFS= read -r linea; do
    [ -z "$linea" ] && break
    html+=$'\t'"$linea"$'\n'
done

# 2. Verificar que se ingresó algo
if [ -z "$html" ]; then
    echo "⚠ No se ingresó ningún HTML."
    exit 1
fi

# 3. Verificar si el contenido parece HTML (busca etiquetas)
if [[ "$html" =~ </?[a-zA-Z]+.*?> ]]; then
    echo "✔ HTML detectado correctamente."

    # 4. Obtener archivos destino
    if [ "$#" -gt 0 ]; then
        # Archivos pasados como argumentos
        archivos=("$@")
    else
        # Preguntar archivos por teclado
        echo "Ingresá los nombres de archivos separados por espacio (ej: uno.html dos.html):"
        read -r -a archivos
    fi

    # Verificar que se ingresaron archivos
    if [ "${#archivos[@]}" -eq 0 ]; then
        echo "⚠ No se ingresaron archivos destino."
        exit 1
    fi

    # 5. Preguntar si sobrescribir o agregar
    echo "¿Querés sobrescribir el contenido (o) o solo agregar al final (a)? [o/a]:"
    read -r modo

    # Validar modo
    if [ "$modo" = "o" ]; then
        operador=">"
    elif [ "$modo" = "a" ]; then
        operador=">>"
    else
        echo "⚠ Opción inválida. Usá 'o' para sobrescribir o 'a' para agregar."
        exit 1
    fi

    # 6. Intentar formatear HTML (requiere xmllint)
    html_indentado=$(echo "$html" | xmllint --html --format - 2>/dev/null)

    # Si xmllint falla, usar HTML original
    if [ -z "$html_indentado" ]; then
        echo "⚠ No se pudo formatear el HTML. Se usará el original."
        html_indentado="$html"
    fi

    # 7. Escribir en cada archivo
    for archivo in "${archivos[@]}"; do
        # Si el archivo no existe, crearlo
        if [ ! -e "$archivo" ]; then
            touch "$archivo"
            echo "📄 Archivo creado: $archivo"
        fi

        # Escribir usando el operador elegido
        echo -e "$html_indentado" $operador "$archivo"
        echo "✔ HTML agregado a $archivo"
    done

    echo "✅ Proceso finalizado."
else
    echo "⚠ HTML ingresado incorrectamente (no se detectaron etiquetas)."
    exit 1
fi

