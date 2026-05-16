export function ajustarResolucionCanvas(canvasId) {
    const canvas = document.getElementById(canvasId);
    const ctx = canvas.getContext('2d');
    const dpr = window.devicePixelRatio || 1;

    // Tamaño visual en la página
    canvas.style.width = canvas.width + "px";
    canvas.style.height = canvas.height + "px";

    // Tamaño real de píxeles
    canvas.width = canvas.width * dpr;
    canvas.height = canvas.height * dpr;

    // Normalizar el sistema de coordenadas
    ctx.scale(dpr, dpr);
    return dpr;
}


function dibujarCheck(ctx, x, y) {
    const check = "m -20,0 c 6.235992,1.47376 9.06987,6.49138 12.223585,11.17585 3.309806,-5.62524 6.678333,-11.29339 23.499214,-26.7921 -8.49648,2.74147 -16.19704,9.19724 -23.499214,17.51215 -6.669553,-4.39774 -9.285172,-2.89888 -12.223585,-1.8959 z";
    dibujarSvg(ctx, check, x, y, "#8BC34A");
}

/**
 * Dibuja un path de SVG en una posición específica del canvas
 * @param {CanvasRenderingContext2D} ctx - El contexto del canvas
 * @param {string} svgPath - El string del path (M...Z)
 * @param {number} x - Posición X absoluta
 * @param {number} y - Posición Y absoluta
 * @param {string} color - Color de relleno
 */
function dibujarSvg(ctx, svgPath, x, y, color) {
    // 1. Creamos el objeto Path2D con los datos del SVG
    const p = new Path2D(svgPath);

    // 2. Guardamos el estado del contexto para no afectar otros dibujos
    ctx.save();

    // 3. Movemos el "pincel" a la posición deseada
    // Nota: El SVG ya tiene coordenadas internas, esto lo desplazará desde el origen
    ctx.translate(x, y);

    // 4. Definimos el estilo y dibujamos
    ctx.fillStyle = color;
    ctx.fill(p);

    // 5. Restauramos el contexto
    ctx.restore();
}


export function generarCalendarioCanvas(canvasId, datos) {
    const canvas = document.getElementById(canvasId);
    const ctx = canvas.getContext('2d');
    
    const anchoReal = parseInt(canvas.style.width);
    const altoReal = parseInt(canvas.style.height);

    const paddingSup = 40; 
    const filas = 5;
    const cols = 7;
    const anchoCelda = anchoReal / cols;
    const altoCelda = (altoReal - paddingSup) / filas;
    
    const diasSemana = ['LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO', 'DOMINGO'];

    ctx.clearRect(0, 0, anchoReal, altoReal);

    // --- 1. FONDO DEL ENCABEZADO (#F9F1EF) ---
    ctx.fillStyle = '#F9F1EF';
    ctx.fillRect(0, 0, anchoReal, paddingSup);

    // --- 2. DIBUJAR CABECERA (TEXTO) ---
    ctx.font = 'bold 11px sans-serif';
    ctx.textAlign = 'center';
    ctx.fillStyle = '#444'; // Un gris oscuro para que resalte
    diasSemana.forEach((dia, i) => {
        ctx.fillText(dia, (i * anchoCelda) + anchoCelda / 2, 25);
    });

    // 3. Lógica de fechas
    const primerDia = new Date(datos.año, datos.mes - 1, 1).getDay();
    const inicioDiferido = (primerDia === 0 ? 6 : primerDia - 1);
    const diasEnMes = new Date(datos.año, datos.mes, 0).getDate();

    // 4. Dibujar Rejilla y Contenido
    let diaActual = 1;

    for (let f = 0; f < filas; f++) {
        for (let c = 0; c < cols; c++) {
            const x = c * anchoCelda;
            const y = paddingSup + (f * altoCelda);

            // Bordes internos (delgados)
            ctx.strokeStyle = '#777';
            ctx.lineWidth = 1;
            ctx.strokeRect(x, y, anchoCelda, altoCelda);

            const indiceCeldas = f * 7 + c;
            if (indiceCeldas >= inicioDiferido && diaActual <= diasEnMes) {
                
                // Número del día
                ctx.fillStyle = '#555';
                ctx.font = '10px Arial';
                ctx.textAlign = 'left';
                ctx.fillText(diaActual, x + 8, y + 18);

                const infoDia = datos.actividades[diaActual];
                if (infoDia) {
                    ctx.fillStyle = '#333';
                    ctx.font = '11px sans-serif';
                    ctx.textAlign = 'center';
                    
                    infoDia.nombres.forEach((act, i) => {
                        ctx.fillText(act, x + anchoCelda / 2, y + (altoCelda / 2) + (i * 15));
                    });

                    if (infoDia.asistio) {
                        dibujarCheck(ctx, x + anchoCelda - 20, y + altoCelda - 15);
                    }
                }
                diaActual++;
            }
        }
    }

    // --- 5. BORDE EXTERIOR GRUESO (Para el look final) ---
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;
    ctx.strokeRect(0, 0, anchoReal, altoReal);
}
