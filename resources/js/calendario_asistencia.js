export function ajustarResolucionCanvas(canvasId, options = {}) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) {
        return null;
    }

    const ctx = canvas.getContext('2d');
    const dpr = window.devicePixelRatio || 1;
    const keepBaseCoordinates = Boolean(options.keepBaseCoordinates);

    const baseWidth = Number(canvas.dataset.baseWidth || canvas.getAttribute('width') || canvas.width || 605);
    const baseHeight = Number(canvas.dataset.baseHeight || canvas.getAttribute('height') || canvas.height || 380);

    canvas.dataset.baseWidth = String(baseWidth);
    canvas.dataset.baseHeight = String(baseHeight);

    const parentWidth = canvas.parentElement ? Math.floor(canvas.parentElement.clientWidth) : baseWidth;
    const cssWidth = Math.max(220, Math.min(parentWidth, baseWidth));
    const cssHeight = Math.round((cssWidth * baseHeight) / baseWidth);

    canvas.style.width = cssWidth + "px";
    canvas.style.height = cssHeight + "px";

    if (keepBaseCoordinates) {
        canvas.width = Math.round(baseWidth * dpr);
        canvas.height = Math.round(baseHeight * dpr);
    } else {
        canvas.width = Math.round(cssWidth * dpr);
        canvas.height = Math.round(cssHeight * dpr);
    }

    if (typeof ctx.resetTransform === 'function') {
        ctx.resetTransform();
    } else {
        ctx.setTransform(1, 0, 0, 1, 0, 0);
    }

    ctx.scale(dpr, dpr);

    return {
        dpr,
        width: cssWidth,
        height: cssHeight,
    };
}


function dibujarCheck(ctx, x, y) {
    const check = "m -20,0 c 6.235992,1.47376 9.06987,6.49138 12.223585,11.17585 3.309806,-5.62524 6.678333,-11.29339 23.499214,-26.7921 -8.49648,2.74147 -16.19704,9.19724 -23.499214,17.51215 -6.669553,-4.39774 -9.285172,-2.89888 -12.223585,-1.8959 z";
    dibujarSvg(ctx, check, x, y, "#8BC34A");
}

function dibujarTache(ctx, x, y) {
    //const tache = "m 119.00096,108.39202 c 7.25145,-0.80422 12.48846,-5.63731 17.63451,-10.652328 3.77847,4.329618 7.9835,10.621448 10.65232,9.846688 1.8643,-2.86792 -4.96343,-8.551251 -7.96686,-13.785361 l 5.81849,-5.370917 c 1.79096,0.04551 3.12793,-0.438611 3.4911,-2.058853 -0.22589,-1.312891 -0.45213,-2.625782 -2.77497,-3.938673 -2.26566,-0.279527 -4.85675,-1.393622 -5.99753,1.253214 -1.25243,1.529838 -0.61332,1.978793 -0.26855,2.595945 l -3.04351,3.849158 c -4.40639,-6.495684 -6.23517,-9.054072 -9.39911,-8.235409 -4.02291,3.300937 2.96703,5.29453 6.35558,10.562805 -5.0212,8.309161 -15.47538,11.669261 -14.50147,15.933731 z";
    const tache = "M -20.803884,10.774689 C -13.55243,9.9704689 -8.3154199,5.1373789 -3.1693699,0.12236091 c 3.77847002,4.32961799 7.9835,10.62144809 10.65232,9.84668799 1.8643,-2.86792 -4.96343,-8.551251 -7.96685998,-13.785361 l 5.81848998,-5.370917 c 1.79096,0.04551 3.12793,-0.438611 3.4911,-2.0588529 -0.22589,-1.312891 -0.45213,-2.625782 -2.77497,-3.938673 -2.26566,-0.279527 -4.85675,-1.393622 -5.99752998,1.253214 -1.25243002,1.529838 -0.61332,1.978793 -0.26855,2.595945 l -3.04351002,3.8491579 c -4.40639,-6.4956839 -6.23517,-9.0540719 -9.3991101,-8.2354089 -4.02291,3.300937 2.9670301,5.29453 6.3555801,10.5628049 C -11.32361,3.1501189 -21.777794,6.5102189 -20.803884,10.774689 Z";
    dibujarSvg(ctx, tache, x, y, "#EC008C");
}

function formatearFechaLocal(fecha) {
    const año = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const dia = String(fecha.getDate()).padStart(2, '0');

    return `${año}-${mes}-${dia}`;
}

function esDiaInhabil(fecha, datos) {
    const diaSemana = fecha.getDay();

    if (diaSemana === 0 || diaSemana === 6) {
        return true;
    }

    const diasInhabiles = Array.isArray(datos?.diasInhabiles) ? datos.diasInhabiles : [];
    const fechaFormateada = formatearFechaLocal(fecha);

    return diasInhabiles.includes(fechaFormateada);
}

function esFechaPasada(fecha, referencia = new Date()) {
    const fechaNormalizada = new Date(fecha.getFullYear(), fecha.getMonth(), fecha.getDate());
    const referenciaNormalizada = new Date(referencia.getFullYear(), referencia.getMonth(), referencia.getDate());

    return fechaNormalizada < referenciaNormalizada;
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
    if (!canvas || !datos) {
        return;
    }

    const ctx = canvas.getContext('2d');
    
    const anchoReal = canvas.clientWidth || parseInt(canvas.style.width, 10) || canvas.width;
    const altoReal = canvas.clientHeight || parseInt(canvas.style.height, 10) || canvas.height;

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
                const fechaDia = new Date(datos.año, datos.mes - 1, diaActual);
                const diaSemana = fechaDia.getDay();
                const esHabil = diaSemana >= 1 && diaSemana <= 5;
                
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

                if (esHabil && esFechaPasada(fechaDia) && !esDiaInhabil(fechaDia, datos) && (!infoDia || !infoDia.asistio)) {
                    dibujarTache(ctx, x + anchoCelda - 26, y + altoCelda - 18);
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
