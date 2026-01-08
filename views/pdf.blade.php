<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Tránsito - Datos Propietario</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        /* ESTILOS BASE */
        body {
            margin: 0;
            padding: 0;
            background: #666;
            font-family: Arial, sans-serif;
            overflow-x: auto;
        }

        /* CONTENEDOR DEL FORMULARIO - HORIZONTAL */
        .form-container {
            width: 297mm; /* Ancho: A4 horizontal (297mm) */
            height: 210mm; /* Alto: A4 horizontal (210mm) */
            margin: 20px auto;
            position: relative;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        /* IMAGEN DE FONDO */
        .imagen-fondo {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            object-fit: contain;
        }

        /* CUADRICULA DE AYUDA (cuando no hay imagen) */
        .cuadricula-fondo {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            background:
                linear-gradient(90deg, rgba(0,0,0,0.1) 1px, transparent 1px) 0 0 / 20mm 20mm,
                linear-gradient(rgba(0,0,0,0.1) 1px, transparent 1px) 0 0 / 20mm 20mm;
        }

        /* CONTENEDOR DE DATOS */
        .datos-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
        }

        /* ESTILOS PARA LOS DATOS */
        .dato {
            position: absolute;
            font-family: Arial, sans-serif;
            font-size: 8pt;
            color: #000000;
            text-transform: uppercase;
            line-height: 1;
            overflow: hidden;
            white-space: nowrap;
            font-weight: bold;
            background: transparent;
        }

        /* ===================================== */
        /* POSICIONES DE LOS DATOS - AJUSTAR ESTOS VALORES */
        /* ===================================== */
       
        /* PRIMERA FILA - NOMBRES */
        .primer-apellido {
            top: 106mm;
            left: 25mm;
            width: 60mm;
        }

        .segundo-apellido {
            top: 106mm;
            left: 60mm;
            width: 60mm;
        }

        .nombres {
            top: 106mm;
            left: 103mm;
            width: 80mm;
        }

        /* SEGUNDA FILA - DOCUMENTO */
        .tipo-doc {
            top: 105mm;
            left: 40mm;
            width: 30mm;
        }

        .numero-doc {
            top: 115.5mm;
            left: 120mm;
            width: 80mm;
        }

        /* TERCERA FILA - DIRECCIÓN */
        .direccion {
            top: 124.5mm;
            left: 25mm;
            width: 100mm;
        }

        .ciudad {
            top: 124.5mm;
            left: 81mm;
            width: 60mm;
        }

        .telefono {
            top: 124.5mm;
            left: 120mm;
            width: 60mm;
        }

        /* PANEL DE CONTROL */
        .control-panel {
            position: fixed;
            top: 20px;
            left: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            width: 320px;
            z-index: 1000;
            max-height: 90vh;
            overflow-y: auto;
        }

        .control-panel h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .control-panel label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #555;
            font-size: 12px;
        }

        .control-panel input {
            width: 100%;
            padding: 6px;
            margin-top: 2px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 12px;
        }

        /* SECCIÓN PARA CARGAR IMAGEN (ahora oculta por defecto) */
        .cargar-imagen-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            display: none; /* OCULTADO POR DEFECTO */
        }

        .cargar-imagen-section.mostrar {
            display: block; /* Mostrar solo si se necesita */
        }

        .cargar-imagen-section label {
            font-size: 11px;
            color: #666;
        }

        .cargar-imagen-section input[type="file"] {
            width: 100%;
            margin-bottom: 10px;
            font-size: 11px;
        }

        .control-panel .botones {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
        }

        .control-panel button {
            flex: 1;
            padding: 8px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
            min-width: 140px;
        }

        .btn-cargar {
            background: #007bff;
        }

        .btn-cargar:hover {
            background: #0056b3;
        }

        .btn-limpiar {
            background: #6c757d;
        }

        .btn-limpiar:hover {
            background: #545b62;
        }

        .btn-pdf {
            background: #28a745;
        }

        .btn-pdf:hover {
            background: #1e7e34;
        }

        .btn-imagen {
            background: #6f42c1;
        }

        .btn-imagen:hover {
            background: #5936a3;
        }

        /* MENSAJES */
        .mensaje {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: none;
            z-index: 1001;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .mensaje.error {
            background: #dc3545;
        }

        .mensaje.warning {
            background: #ffc107;
            color: #212529;
        }

        /* HERRAMIENTA DE AJUSTE */
        .herramienta-ajuste {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            width: 200px;
            z-index: 999;
            display: none;
        }

        .herramienta-ajuste h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }

        .herramienta-ajuste input {
            width: 100%;
            margin-bottom: 8px;
            padding: 4px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- MENSAJE -->
    <div class="mensaje" id="mensaje"></div>

    <!-- PANEL DE CONTROL -->
    <div class="control-panel">
        <h3>📋 Datos del Propietario</h3>
       
        <label>Primer Apellido:</label>
        <input type="text" id="inputPrimerApellido" placeholder="Ej: PÉREZ" value="PÉREZ">
       
        <label>Segundo Apellido:</label>
        <input type="text" id="inputSegundoApellido" placeholder="Ej: GÓMEZ" value="GÓMEZ">
       
        <label>Nombres:</label>
        <input type="text" id="inputNombres" placeholder="Ej: JUAN CARLOS" value="JUAN CARLOS">
       
        <label>Tipo Documento:</label>
        <input type="text" id="inputTipoDoc" placeholder="Ej: C.C." value="C.C.">
       
        <label>Número Documento:</label>
        <input type="text" id="inputNumeroDoc" placeholder="Ej: 1234567890" value="1234567890">
       
        <label>Dirección:</label>
        <input type="text" id="inputDireccion" placeholder="Ej: CRA 10 # 20-30" value="CRA 10 # 20-30">
       
        <label>Ciudad:</label>
        <input type="text" id="inputCiudad" placeholder="Ej: BOGOTÁ" value="BOGOTÁ">
       
        <label>Teléfono:</label>
        <input type="text" id="inputTelefono" placeholder="Ej: 3001234567" value="3001234567">
       
        <!-- SECCIÓN PARA CARGAR IMAGEN (solo aparece si se necesita) -->
        <div class="cargar-imagen-section" id="cargarImagenSection">
            <label>Imagen de fondo alternativa:</label>
            <input type="file" id="fileInput" accept=".png,.jpg,.jpeg,.gif">
            <div class="botones">
                <button onclick="cargarDatos()" class="btn-cargar">✅ Cargar Datos</button>
                <button onclick="limpiarDatos()" class="btn-limpiar">🗑️ Limpiar</button>
                <button onclick="cargarImagenManual()" class="btn-imagen">📁 Cambiar Imagen</button>
                <button onclick="generarPDF()" class="btn-pdf">📄 Generar PDF</button>
                <button onclick="mostrarHerramienta()" style="background: #fd7e14;">⚙️ Ajustar</button>
            </div>
            <div style="margin-top: 10px; font-size: 10px; color: #888;">
                <p><strong>Nota:</strong> Si deseas usar una imagen diferente, cárgala aquí</p>
                <p><strong>Formato:</strong> Horizontal A4 (297×210 mm)</p>
            </div>
        </div>

        <!-- BOTONES PRINCIPALES (siempre visibles) -->
        <div class="botones" style="margin-top: 15px;">
            <button onclick="cargarDatos()" class="btn-cargar">✅ Cargar Datos</button>
            <button onclick="limpiarDatos()" class="btn-limpiar">🗑️ Limpiar</button>
            <button onclick="mostrarSeccionImagen()" class="btn-imagen">🖼️ Cambiar Imagen</button>
            <button onclick="generarPDF()" class="btn-pdf">📄 Generar PDF</button>
            <button onclick="mostrarHerramienta()" style="background: #fd7e14;">⚙️ Ajustar</button>
        </div>
    </div>

    <!-- HERRAMIENTA DE AJUSTE -->
    <div class="herramienta-ajuste" id="herramientaAjuste">
        <h4>⚙️ Ajustar Posición</h4>
        <select id="campoSeleccionado">
            <option value="primer-apellido">Primer Apellido</option>
            <option value="segundo-apellido">Segundo Apellido</option>
            <option value="nombres">Nombres</option>
            <option value="tipo-doc">Tipo Documento</option>
            <option value="numero-doc">Número Documento</option>
            <option value="direccion">Dirección</option>
            <option value="ciudad">Ciudad</option>
            <option value="telefono">Teléfono</option>
        </select>
        <input type="number" id="ajustarTop" placeholder="Top (mm)" step="1">
        <input type="number" id="ajustarLeft" placeholder="Left (mm)" step="1">
        <button onclick="aplicarAjuste()" style="width: 100%; background: #007bff; color: white; padding: 5px;">Aplicar</button>
        <button onclick="ocultarHerramienta()" style="width: 100%; background: #6c757d; color: white; padding: 5px; margin-top: 5px;">Cerrar</button>
    </div>

    <!-- FORMULARIO PRINCIPAL - HORIZONTAL -->
    <div class="form-container" id="formContainer">
        <!-- La imagen se cargará automáticamente aquí -->
        <div class="datos-container">
            <div class="dato primer-apellido" id="primerApellido"></div>
            <div class="dato segundo-apellido" id="segundoApellido"></div>
            <div class="dato nombres" id="nombres"></div>
            <div class="dato tipo-doc" id="tipoDoc"></div>
            <div class="dato numero-doc" id="numeroDoc"></div>
            <div class="dato direccion" id="direccion"></div>
            <div class="dato ciudad" id="ciudad"></div>
            <div class="dato telefono" id="telefono"></div>
        </div>
    </div>

    <script>
        // VARIABLES GLOBALES
        let datosActuales = {
            primerApellido: "PÉREZ",
            segundoApellido: "GÓMEZ",
            nombres: "JUAN CARLOS",
            tipoDoc: "X",
            numeroDoc: "1234567890",
            direccion: "CRA 10 # 20-30",
            ciudad: "BOGOTÁ",
            telefono: "3001234567"
        };

        let imagenCargada = false;
        let imagenBase64 = '';

        // FUNCIÓN PARA MOSTRAR MENSAJE
        function mostrarMensaje(texto, tipo = 'success') {
            const mensaje = document.getElementById('mensaje');
            mensaje.textContent = texto;
            mensaje.className = 'mensaje' + (tipo === 'error' ? ' error' : tipo === 'warning' ? ' warning' : '');
            mensaje.style.display = 'block';
            setTimeout(() => {
                mensaje.style.display = 'none';
            }, 3000);
        }

        // MOSTRAR/OCULTAR SECCIÓN DE IMAGEN
        function mostrarSeccionImagen() {
            const seccion = document.getElementById('cargarImagenSection');
            seccion.classList.toggle('mostrar');
        }

        // CARGAR IMAGEN AUTOMÁTICAMENTE DESDE UN PATH FIJO
        async function cargarImagenPredefinida() {
            console.log('Cargando imagen predefinida...');
           
            const imagenesPredefinidas = [
                'transito.png',          // Nombre principal
                './transito.png',        // Ruta relativa
                '/images/transito.png',   // En carpeta images
                '/assets/transito.png'    // En carpeta assets
            ];
           
            for (const rutaImagen of imagenesPredefinidas) {
                console.log(`Intentando cargar: ${rutaImagen}`);
               
                try {
                    const cargada = await cargarImagenDesdeRuta(rutaImagen);
                    if (cargada) {
                        console.log(`✓ Imagen cargada desde: ${rutaImagen}`);
                        return true;
                    }
                } catch (error) {
                    console.log(`✗ No se pudo cargar: ${rutaImagen}`);
                }
            }
           
            // Si no se encontró la imagen, mostrar advertencia
            console.log('✗ No se encontró transito.png');
            mostrarMensaje('No se encontró transito.png. Usando cuadrícula de ayuda.', 'warning');
            agregarCuadricula();
            return false;
        }

        // FUNCIÓN PARA CARGAR IMAGEN DESDE UNA RUTA
        function cargarImagenDesdeRuta(ruta) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.crossOrigin = "Anonymous";
               
                img.onload = function() {
                    // Limpiar cualquier imagen existente
                    const imagenes = document.querySelectorAll('.imagen-fondo');
                    imagenes.forEach(img => img.remove());
                   
                    // Crear canvas para convertir a base64
                    const canvas = document.createElement('canvas');
                    canvas.width = this.naturalWidth;
                    canvas.height = this.naturalHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(this, 0, 0);
                   
                    imagenBase64 = canvas.toDataURL('image/png');
                    imagenCargada = true;
                   
                    // Agregar al formulario
                    const imgElement = document.createElement('img');
                    imgElement.className = 'imagen-fondo';
                    imgElement.src = imagenBase64;
                   
                    // Insertar al principio del contenedor
                    const container = document.getElementById('formContainer');
                    container.insertBefore(imgElement, container.firstChild);
                   
                    // Remover cuadrícula si existe
                    const cuadricula = document.querySelector('.cuadricula-fondo');
                    if (cuadricula) {
                        cuadricula.remove();
                    }
                   
                    resolve(true);
                };
               
                img.onerror = function() {
                    reject(new Error(`No se pudo cargar la imagen: ${ruta}`));
                };
               
                img.src = ruta + '?t=' + new Date().getTime(); // Evitar cache
            });
        }

        // CARGAR IMAGEN MANUALMENTE DESDE ARCHIVO (solo si se necesita cambiar)
        function cargarImagenManual() {
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];
           
            if (!file) {
                mostrarMensaje('Selecciona una imagen primero', 'error');
                return;
            }
           
            const reader = new FileReader();
           
            reader.onload = function(e) {
                // Limpiar cualquier imagen existente
                const imagenes = document.querySelectorAll('.imagen-fondo');
                imagenes.forEach(img => img.remove());
               
                // Crear nueva imagen
                const imgElement = document.createElement('img');
                imgElement.className = 'imagen-fondo';
                imgElement.src = e.target.result;
                imgElement.onload = function() {
                    imagenBase64 = e.target.result;
                    imagenCargada = true;
                   
                    // Insertar la imagen al inicio del contenedor
                    const container = document.getElementById('formContainer');
                    container.insertBefore(imgElement, container.firstChild);
                   
                    // Remover cuadrícula si existe
                    const cuadricula = document.querySelector('.cuadricula-fondo');
                    if (cuadricula) {
                        cuadricula.remove();
                    }
                   
                    mostrarMensaje('Imagen cambiada correctamente');
                };
            };
           
            reader.onerror = function() {
                mostrarMensaje('Error al cargar la imagen', 'error');
            };
           
            reader.readAsDataURL(file);
        }

        // AGREGAR CUADRICULA DE AYUDA (solo si no hay imagen)
        function agregarCuadricula() {
            const cuadricula = document.createElement('div');
            cuadricula.className = 'cuadricula-fondo';
            const container = document.getElementById('formContainer');
            container.insertBefore(cuadricula, container.firstChild);
        }

        // CARGAR DATOS DESDE OBJETO
        function cargarDatosDesdeObjeto(datos) {
            const campos = [
                'primerApellido',
                'segundoApellido',
                'nombres',
                'tipoDoc',
                'numeroDoc',
                'direccion',
                'ciudad',
                'telefono'
            ];
           
            campos.forEach(campo => {
                const elemento = document.getElementById(campo);
                if (elemento) {
                    elemento.textContent = datos[campo] || '';
                    elemento.style.color = '#000000';
                    elemento.style.fontWeight = 'bold';
                    elemento.style.textShadow = '1px 1px 2px rgba(255,255,255,0.8)';
                }
            });
           
            mostrarMensaje("Datos cargados en el formulario");
        }

        // CARGAR DATOS DESDE FORMULARIO
        function cargarDatos() {
            const nuevosDatos = {
                primerApellido: document.getElementById('inputPrimerApellido').value.trim().toUpperCase(),
                segundoApellido: document.getElementById('inputSegundoApellido').value.trim().toUpperCase(),
                nombres: document.getElementById('inputNombres').value.trim().toUpperCase(),
                tipoDoc: document.getElementById('inputTipoDoc').value.trim().toUpperCase(),
                numeroDoc: document.getElementById('inputNumeroDoc').value.trim(),
                direccion: document.getElementById('inputDireccion').value.trim().toUpperCase(),
                ciudad: document.getElementById('inputCiudad').value.trim().toUpperCase(),
                telefono: document.getElementById('inputTelefono').value.trim()
            };
           
            datosActuales = nuevosDatos;
            cargarDatosDesdeObjeto(nuevosDatos);
        }

        // LIMPIAR DATOS
        function limpiarDatos() {
            const inputs = document.querySelectorAll('.control-panel input[type="text"]');
            inputs.forEach(input => {
                input.value = '';
            });
           
            const datosVacios = {
                primerApellido: "",
                segundoApellido: "",
                nombres: "",
                tipoDoc: "",
                numeroDoc: "",
                direccion: "",
                ciudad: "",
                telefono: ""
            };
           
            cargarDatosDesdeObjeto(datosVacios);
            mostrarMensaje("Datos limpiados");
        }

        // GENERAR PDF
        async function generarPDF() {
            if (!imagenCargada) {
                mostrarMensaje('No hay imagen cargada. Generando PDF con cuadrícula...', 'warning');
            }
           
            mostrarMensaje("Generando PDF...");
           
            try {
                // Ocultar controles temporalmente
                const controles = document.querySelector('.control-panel');
                const herramienta = document.getElementById('herramientaAjuste');
                const estiloControles = controles.style.display;
                const estiloHerramienta = herramienta.style.display;
               
                controles.style.display = 'none';
                herramienta.style.display = 'none';
               
                await new Promise(resolve => setTimeout(resolve, 300));
               
                const formulario = document.getElementById('formContainer');
               
                // Capturar el formulario
                const canvas = await html2canvas(formulario, {
                    scale: 2,
                    useCORS: false,
                    backgroundColor: '#ffffff',
                    logging: false,
                    allowTaint: false,
                    width: formulario.offsetWidth,
                    height: formulario.offsetHeight
                });
               
                // Crear PDF horizontal
                const pdf = new jspdf.jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: 'a4'
                });
               
                // Dimensiones
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();
               
                const imgWidth = pageWidth;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
               
                let finalWidth = imgWidth;
                let finalHeight = imgHeight;
                let x = 0;
                let y = (pageHeight - finalHeight) / 2;
               
                if (finalHeight > pageHeight) {
                    finalHeight = pageHeight;
                    finalWidth = (canvas.width * finalHeight) / canvas.height;
                    x = (pageWidth - finalWidth) / 2;
                    y = 0;
                }
               
                // Agregar al PDF
                const imgData = canvas.toDataURL('image/jpeg', 0.95);
                pdf.addImage(imgData, 'JPEG', x, y, finalWidth, finalHeight);
               
                // Nombre del archivo
                const fecha = new Date();
                const nombreArchivo = `formulario_transito_${fecha.getFullYear()}-${fecha.getMonth()+1}-${fecha.getDate()}_${fecha.getHours()}${fecha.getMinutes()}.pdf`;
               
                // Guardar
                pdf.save(nombreArchivo);
               
                // Restaurar controles
                controles.style.display = estiloControles;
                herramienta.style.display = estiloHerramienta;
               
                mostrarMensaje(`PDF generado: ${nombreArchivo}`);
               
            } catch (error) {
                console.error('Error al generar PDF:', error);
               
                // Restaurar controles
                document.querySelector('.control-panel').style.display = 'block';
                document.getElementById('herramientaAjuste').style.display = 'none';
               
                // Método alternativo
                mostrarMensaje('Generando PDF alternativo...', 'warning');
                await generarPDFSimple();
            }
        }

        // MÉTODO ALTERNATIVO SIMPLE
        async function generarPDFSimple() {
            try {
                const pdf = new jspdf.jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: 'a4'
                });
               
                // Título
                pdf.setFontSize(18);
                pdf.text('FORMULARIO DE TRÁNSITO', 148.5, 20, { align: 'center' });
               
                // Sección de datos
                pdf.setFontSize(14);
                pdf.text('DATOS DEL PROPIETARIO', 20, 40);
                pdf.setFontSize(12);
               
                let y = 50;
                const campos = [
                    ['Primer Apellido:', datosActuales.primerApellido],
                    ['Segundo Apellido:', datosActuales.segundoApellido],
                    ['Nombres:', datosActuales.nombres],
                    ['Tipo Documento:', datosActuales.tipoDoc],
                    ['Número Documento:', datosActuales.numeroDoc],
                    ['Dirección:', datosActuales.direccion],
                    ['Ciudad:', datosActuales.ciudad],
                    ['Teléfono:', datosActuales.telefono]
                ];
               
                campos.forEach(([label, value]) => {
                    pdf.text(`${label} ${value || ''}`, 20, y);
                    y += 8;
                });
               
                // Fecha
                const fecha = new Date();
                pdf.text(`Generado: ${fecha.toLocaleDateString()} ${fecha.toLocaleTimeString()}`, 20, 180);
               
                pdf.save(`formulario_${Date.now()}.pdf`);
                mostrarMensaje('PDF simple generado');
               
            } catch (error) {
                mostrarMensaje('Error crítico al generar PDF', 'error');
            }
        }

        // HERRAMIENTAS DE AJUSTE
        function mostrarHerramienta() {
            document.getElementById('herramientaAjuste').style.display = 'block';
        }

        function ocultarHerramienta() {
            document.getElementById('herramientaAjuste').style.display = 'none';
        }

        function aplicarAjuste() {
            const campo = document.getElementById('campoSeleccionado').value;
            const top = document.getElementById('ajustarTop').value;
            const left = document.getElementById('ajustarLeft').value;
           
            const elemento = document.querySelector('.' + campo);
            if (elemento && top && left) {
                elemento.style.top = top + 'mm';
                elemento.style.left = left + 'mm';
                console.log(`Campo ${campo} ajustado a: top=${top}mm, left=${left}mm`);
                mostrarMensaje(`Posición de ${campo} ajustada`);
            }
        }

        // INICIALIZAR
        document.addEventListener('DOMContentLoaded', async function() {
            console.log('=== INICIALIZANDO FORMULARIO ===');
           
            // Cargar datos iniciales
            Object.keys(datosActuales).forEach(key => {
                const input = document.getElementById('input' + key.charAt(0).toUpperCase() + key.slice(1));
                if (input) {
                    input.value = datosActuales[key];
                }
            });
           
            // Cargar imagen automáticamente desde transito.png
            await cargarImagenPredefinida();
           
            // Cargar datos iniciales
            cargarDatosDesdeObjeto(datosActuales);
           
            console.log('=== SISTEMA LISTO ===');
            console.log('Instrucciones:');
            console.log('1. La imagen transito.png se cargó automáticamente');
            console.log('2. Ajusta posiciones con el botón ⚙️ si es necesario');
            console.log('3. Modifica los datos y haz clic en ✅ Cargar Datos');
            console.log('4. Genera PDF con 📄 Generar PDF');
        });
    </script>
</body>
</html>