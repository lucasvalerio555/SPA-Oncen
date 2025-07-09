#!/bin/bash

# 1. Pedir HTML desde terminal (fin con línea vacía)
echo "Ingresá el código HTML (finalizá con una línea vacía): "
html=""
while IFS= read -r linea; do
    [ -z "$linea" ] && break
    html+="$linea"$'\n'
done

# Verificar que se haya ingresado algo
[ -z "$html" ] && echo "⚠ No se ingresó ningún HTML." && exit 1

# 2. Pedir nombres de archivos separados por espacios
echo "Ingresá los nombres de archivos separados por espacio (ej: uno.html dos.html):"
read -r -a archivos

# 3. Formatear HTML con indentación (requiere xmllint)
html_indentado=$(echo "$html" | xmllint --html --format - 2>/dev/null)

# Si xmllint falló, usar HTML original
[ -z "$html_indentado" ] && html_indentado="$html"

# 4. Agregar el HTML en cada archivo
for archivo in "${archivos[@]}"; do
    echo -e "$html_indentado" >> "$archivo"
    echo "✔ HTML agregado a $archivo"
done
