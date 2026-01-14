<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Tránsito - Sistema Renault</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ESTILOS BASE - CONCORDANCIA CON EL SISTEMA */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #333333;
        }

        /* CONTENEDOR PRINCIPAL */
        .main-container {
            width: 100%;
            max-width: 1400px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* HEADER DEL FORMULARIO */
        .form-header {
            width: 100%;
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 10px 10px 0 0;
            border: 2px solid #000000;
            border-bottom: none;
            margin-bottom: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .form-icon {
            width: 50px;
            height: 50px;
            background: #000000;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 24px;
        }

        .header-text h1 {
            font-size: 1.5rem;
            color: #000000;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header-text p {
            color: #666666;
            font-size: 0.9rem;
        }

        /* BOTÓN VOLVER */
        .btn-volver {
            background: #ffffff;
            color: #000000;
            border: 2px solid #000000;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-volver:hover {
            background: #000000;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* CONTENEDOR DEL FORMULARIO - HORIZONTAL */
        .form-container {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: white;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
            border: 2px solid #000000;
            border-radius: 0 0 10px 10px;
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
                linear-gradient(90deg, rgba(0,0,0,0.08) 1px, transparent 1px) 0 0 / 20mm 20mm,
                linear-gradient(rgba(0,0,0,0.08) 1px, transparent 1px) 0 0 / 20mm 20mm;
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 9pt;
            color: #000000;
            text-transform: uppercase;
            line-height: 1.2;
            overflow: hidden;
            white-space: nowrap;
            font-weight: 600;
            background: transparent;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
        }

        /* POSICIONES DE LOS DATOS */
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

        /* CONTROLES DEL FORMULARIO */
        .controls-container {
            width: 100%;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .control-group label {
            font-weight: 600;
            color: #000000;
            font-size: 0.9rem;
        }

        .control-group input,
        .control-group select {
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .control-group input:focus,
        .control-group select:focus {
            border-color: #000000;
            outline: none;
        }

        /* BOTÓN GENERAR PDF */
        .btn-generar-pdf {
            background: #000000;
            color: #ffffff;
            border: 2px solid #000000;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .btn-generar-pdf:hover {
            background: #333333;
            border-color: #333333;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        /* MENSAJES */
        .mensaje {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #000000;
            color: #ffffff;
            padding: 15px 25px;
            border-radius: 8px;
            display: none;
            z-index: 1001;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease;
            max-width: 400px;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .mensaje.success {
            background: #000000;
            border-left: 5px solid #008000;
        }

        .mensaje.error {
            background: #000000;
            border-left: 5px solid #cc0000;
        }

        .mensaje.warning {
            background: #000000;
            border-left: 5px solid #ffc107;
        }

        .mensaje-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .mensaje i {
            font-size: 1.3rem;
        }

        .mensaje.success i {
            color: #008000;
        }

        .mensaje.error i {
            color: #cc0000;
        }

        .mensaje.warning i {
            color: #ffc107;
        }

        /* FOOTER */
        .form-footer {
            text-align: center;
            padding: 20px;
            color: #666666;
            font-size: 0.9rem;
            margin-top: 30px;
            width: 100%;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .form-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .controls-container {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                width: 100%;
                height: auto;
                aspect-ratio: 297 / 210;
                max-width: 297mm;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .form-header {
                padding: 15px;
            }
            
            .header-text h1 {
                font-size: 1.3rem;
            }
        }

        /* MODAL DE CONFIRMACIÓN */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            border: 2px solid #000000;
            animation: modalFade 0.3s;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        @keyframes modalFade {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal h3 {
            color: #000000;
            margin-bottom: 15px;
            font-size: 1.4rem;
            font-weight: 600;
        }

        .modal p {
            color: #666666;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .modal-btn {
            padding: 12px 25px;
            border: 2px solid #000000;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            min-width: 120px;
        }

        .modal-btn.confirm {
            background: #000000;
            color: #ffffff;
        }

        .modal-btn.confirm:hover {
            background: #333333;
            border-color: #333333;
        }

        .modal-btn.cancel {
            background: #ffffff;
            color: #000000;
        }

        .modal-btn.cancel:hover {
            background: #f5f5f5;
        }

        /* LOADER */
        .loader {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            color: #666666;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f0f0f0;
            border-top: 5px solid #000000;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loader p {
            font-size: 1.1rem;
            color: #666666;
        }
    </style>
</head>
<body>
    <!-- CONTENEDOR PRINCIPAL -->
    <div class="main-container">
        <!-- HEADER DEL FORMULARIO -->
        <div class="form-header">
            <div class="header-left">
                <div class="form-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="header-text">
                    <h1>Formulario de Tránsito</h1>
                    <p>Complete el formulario y genere el PDF oficial</p>
                </div>
            </div>
            <a href="{{ route('renault.tramites') }}" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver a Trámites
            </a>
        </div>

        <!-- MENSAJE -->
        <div class="mensaje" id="mensaje">
            <div class="mensaje-content">
                <i class="fas fa-info-circle"></i>
                <div id="mensajeTexto"></div>
            </div>
        </div>

        <!-- FORMULARIO PRINCIPAL -->
        <div class="form-container" id="formContainer">
            <!-- La imagen se cargará automáticamente aquí -->
            <div class="datos-container">
                <div class="dato primer-apellido" id="primerApellido">PÉREZ</div>
                <div class="dato segundo-apellido" id="segundoApellido">GÓMEZ</div>
                <div class="dato nombres" id="nombres">JUAN CARLOS</div>
                <div class="dato tipo-doc" id="tipoDoc">C.C.</div>
                <div class="dato numero-doc" id="numeroDoc">1234567890</div>
                <div class="dato direccion" id="direccion">CRA 10 # 20-30</div>
                <div class="dato ciudad" id="ciudad">BOGOTÁ</div>
                <div class="dato telefono" id="telefono">3001234567</div>
            </div>
        </div>

        <!-- CONTROLES DEL FORMULARIO -->
        <div class="controls-container">
            <div class="control-group">
                <label for="inputPrimerApellido">Primer Apellido:</label>
                <input type="text" id="inputPrimerApellido" placeholder="Ingrese primer apellido">
            </div>
            
            <div class="control-group">
                <label for="inputSegundoApellido">Segundo Apellido:</label>
                <input type="text" id="inputSegundoApellido" placeholder="Ingrese segundo apellido">
            </div>
            
            <div class="control-group">
                <label for="inputNombres">Nombres:</label>
                <input type="text" id="inputNombres" placeholder="Ingrese nombres completos">
            </div>
            
            <div class="control-group">
                <label for="selectTipoDoc">Tipo de Documento:</label>
                <select id="selectTipoDoc">
                    <option value="C.C.">Cédula de Ciudadanía</option>
                    <option value="T.I.">Tarjeta de Identidad</option>
                    <option value="C.E.">Cédula de Extranjería</option>
                    <option value="PAS">Pasaporte</option>
                </select>
            </div>
            
            <div class="control-group">
                <label for="inputNumeroDoc">Número de Documento:</label>
                <input type="text" id="inputNumeroDoc" placeholder="Ingrese número de documento">
            </div>
            
            <div class="control-group">
                <label for="inputDireccion">Dirección:</label>
                <input type="text" id="inputDireccion" placeholder="Ingrese dirección completa">
            </div>
            
            <div class="control-group">
                <label for="inputCiudad">Ciudad:</label>
                <input type="text" id="inputCiudad" placeholder="Ingrese ciudad">
            </div>
            
            <div class="control-group">
                <label for="inputTelefono">Teléfono:</label>
                <input type="tel" id="inputTelefono" placeholder="Ingrese teléfono">
            </div>
        </div>

        <!-- BOTÓN GENERAR PDF -->
        <button class="btn-generar-pdf" onclick="generarPDF()">
            <i class="fas fa-file-pdf"></i> Generar PDF del Formulario
        </button>

        <!-- LOADER -->
        <div class="loader" id="loader">
            <div class="spinner"></div>
            <p>Generando documento PDF...</p>
        </div>

        <!-- FOOTER -->
        <div class="form-footer">
            <p>Sistema de Gestión Renault &copy; {{ date('Y') }} | Formularios Oficiales</p>
            <p>Este formulario genera documentos oficiales válidos para trámites de tránsito</p>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN -->
    <div class="modal" id="redirectModal">
        <div class="modal-content">
            <h3><i class="fas fa-external-link-alt"></i> Descarga de PDF</h3>
            <p id="modalMessage">El documento PDF se generará con los datos actuales. ¿Desea continuar?</p>
            <div class="modal-buttons">
                <button class="modal-btn confirm" id="confirmRedirect">Sí, Generar PDF</button>
                <button class="modal-btn cancel" id="cancelRedirect">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        // VARIABLES GLOBALES
        let datosActuales = {
            primerApellido: "PÉREZ",
            segundoApellido: "GÓMEZ",
            nombres: "JUAN CARLOS",
            tipoDoc: "C.C.",
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
            const mensajeTexto = document.getElementById('mensajeTexto');
            const icono = mensaje.querySelector('i');
            
            mensajeTexto.textContent = texto;
            mensaje.className = 'mensaje ' + tipo;
            
            // Cambiar icono según tipo
            if (tipo === 'success') {
                icono.className = 'fas fa-check-circle';
            } else if (tipo === 'error') {
                icono.className = 'fas fa-exclamation-circle';
            } else if (tipo === 'warning') {
                icono.className = 'fas fa-exclamation-triangle';
            } else {
                icono.className = 'fas fa-info-circle';
            }
            
            mensaje.style.display = 'block';
            setTimeout(() => {
                mensaje.style.display = 'none';
            }, 5000);
        }

        // CARGAR IMAGEN AUTOMÁTICAMENTE
        async function cargarImagenPredefinida() {
            console.log('Cargando imagen del formulario...');
           
            const imagenesPredefinidas = [
                '/storage/formularios/transito.png',
                '/images/transito.png',
                '/assets/transito.png',
                './transito.png',
                'transito.png'
            ];
           
            for (const rutaImagen of imagenesPredefinidas) {
                console.log(`Intentando cargar: ${rutaImagen}`);
               
                try {
                    const cargada = await cargarImagenDesdeRuta(rutaImagen);
                    if (cargada) {
                        console.log(`✓ Imagen cargada desde: ${rutaImagen}`);
                        mostrarMensaje('Formulario cargado correctamente', 'success');
                        return true;
                    }
                } catch (error) {
                    console.log(`✗ No se pudo cargar: ${rutaImagen}`);
                }
            }
           
            // Si no se encontró la imagen
            console.log('✗ No se encontró la imagen del formulario');
            mostrarMensaje('Usando formulario base. Puede cargar una imagen personalizada.', 'warning');
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

        // AGREGAR CUADRICULA DE AYUDA
        function agregarCuadricula() {
            const cuadricula = document.createElement('div');
            cuadricula.className = 'cuadricula-fondo';
            const container = document.getElementById('formContainer');
            container.insertBefore(cuadricula, container.firstChild);
        }

        // ACTUALIZAR DATOS DEL FORMULARIO
        function actualizarDatosFormulario() {
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
                    elemento.textContent = datosActuales[campo] || '';
                }
            });
        }

        // ACTUALIZAR DATOS DESDE LOS INPUTS
        function actualizarDesdeInputs() {
            datosActuales = {
                primerApellido: document.getElementById('inputPrimerApellido').value.toUpperCase() || datosActuales.primerApellido,
                segundoApellido: document.getElementById('inputSegundoApellido').value.toUpperCase() || datosActuales.segundoApellido,
                nombres: document.getElementById('inputNombres').value.toUpperCase() || datosActuales.nombres,
                tipoDoc: document.getElementById('selectTipoDoc').value,
                numeroDoc: document.getElementById('inputNumeroDoc').value || datosActuales.numeroDoc,
                direccion: document.getElementById('inputDireccion').value.toUpperCase() || datosActuales.direccion,
                ciudad: document.getElementById('inputCiudad').value.toUpperCase() || datosActuales.ciudad,
                telefono: document.getElementById('inputTelefono').value || datosActuales.telefono
            };
            
            actualizarDatosFormulario();
            mostrarMensaje('Datos actualizados en el formulario', 'success');
        }

        // CONFIGURAR EVENTOS DE INPUTS
        function configurarEventosInputs() {
            const inputs = [
                'inputPrimerApellido',
                'inputSegundoApellido',
                'inputNombres',
                'selectTipoDoc',
                'inputNumeroDoc',
                'inputDireccion',
                'inputCiudad',
                'inputTelefono'
            ];
            
            inputs.forEach(inputId => {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('change', actualizarDesdeInputs);
                    input.addEventListener('blur', actualizarDesdeInputs);
                }
            });
        }

        // GENERAR PDF
        async function generarPDF() {
            // Actualizar datos desde inputs antes de generar
            actualizarDesdeInputs();
            
            // Mostrar modal de confirmación
            document.getElementById('modalMessage').textContent = 
                `Se generará un PDF con los datos del formulario. ¿Desea continuar?`;
            document.getElementById('redirectModal').style.display = 'flex';
        }

        // FUNCIÓN REAL PARA GENERAR PDF
        async function generarPDFReal() {
            if (!imagenCargada) {
                mostrarMensaje('Generando PDF con formulario base...', 'warning');
            }
           
            mostrarMensaje("Generando documento PDF...", 'success');
           
            try {
                // Mostrar loader
                const loader = document.getElementById('loader');
                const btnGenerar = document.querySelector('.btn-generar-pdf');
                loader.style.display = 'flex';
                btnGenerar.disabled = true;
               
                await new Promise(resolve => setTimeout(resolve, 500));
               
                const formulario = document.getElementById('formContainer');
               
                // Capturar el formulario
                const canvas = await html2canvas(formulario, {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    allowTaint: true,
                    width: formulario.offsetWidth,
                    height: formulario.offsetHeight
                });
               
                // Crear PDF horizontal
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF({
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
                const nombreArchivo = `Formulario_Transito_${datosActuales.primerApellido}_${fecha.getFullYear()}-${String(fecha.getMonth()+1).padStart(2, '0')}-${String(fecha.getDate()).padStart(2, '0')}.pdf`;
               
                // Guardar
                pdf.save(nombreArchivo);
               
                // Restaurar controles
                loader.style.display = 'none';
                btnGenerar.disabled = false;
               
                mostrarMensaje(`PDF generado: ${nombreArchivo}`, 'success');
               
            } catch (error) {
                console.error('Error al generar PDF:', error);
               
                // Restaurar controles
                document.getElementById('loader').style.display = 'none';
                document.querySelector('.btn-generar-pdf').disabled = false;
               
                // Método alternativo
                mostrarMensaje('Generando PDF alternativo...', 'warning');
                await generarPDFSimple();
            }
        }

        // MÉTODO ALTERNATIVO SIMPLE
        async function generarPDFSimple() {
            try {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });
               
                // Título
                pdf.setFontSize(18);
                pdf.text('FORMULARIO DE TRÁNSITO', 105, 20, { align: 'center' });
               
                // Línea separadora
                pdf.setDrawColor(0, 0, 0);
                pdf.setLineWidth(0.5);
                pdf.line(20, 25, 190, 25);
               
                // Sección de datos
                pdf.setFontSize(14);
                pdf.text('DATOS DEL PROPIETARIO', 20, 35);
                pdf.setFontSize(12);
               
                let y = 45;
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
               
                // Firma y fecha
                pdf.setFontSize(10);
                pdf.text(`Generado: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}`, 20, 180);
                pdf.text('Sistema de Gestión Renault - Formulario Oficial', 105, 190, { align: 'center' });
               
                pdf.save(`Formulario_Transito_${Date.now()}.pdf`);
                mostrarMensaje('PDF simple generado correctamente', 'success');
               
            } catch (error) {
                mostrarMensaje('Error al generar el documento PDF', 'error');
            }
        }

        // INICIALIZAR
        document.addEventListener('DOMContentLoaded', async function() {
            console.log('=== INICIALIZANDO FORMULARIO DE TRÁNSITO ===');
           
            // Cargar imagen automáticamente
            await cargarImagenPredefinida();
           
            // Cargar datos iniciales
            actualizarDatosFormulario();
            
            // Configurar eventos de inputs
            configurarEventosInputs();
            
            // Configurar botones del modal
            document.getElementById('confirmRedirect').addEventListener('click', function() {
                document.getElementById('redirectModal').style.display = 'none';
                generarPDFReal();
            });
            
            document.getElementById('cancelRedirect').addEventListener('click', function() {
                document.getElementById('redirectModal').style.display = 'none';
            });
            
            // Cerrar modal al hacer clic fuera
            document.getElementById('redirectModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });

            console.log('=== FORMULARIO LISTO ===');
            console.log('Complete los datos y haga clic en "Generar PDF"');
        });
    </script>
</body>
</html>