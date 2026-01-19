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
            max-width: 900px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* HEADER DEL FORMULARIO */
        .form-header {
            width: 100%;
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 10px 10px 0 0;
            border: 2px solid #000000;
            border-bottom: none;
            margin-bottom: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
            font-size: 1.6rem;
            color: #000000;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header-text p {
            color: #666666;
            font-size: 0.95rem;
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
            font-size: 0.95rem;
        }

        .btn-volver:hover {
            background: #000000;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* FORMULARIO DE DATOS */
        .formulario-datos {
            background: #ffffff;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 2px solid #000000;
            border-top: none;
            width: 100%;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .formulario-datos h2 {
            font-size: 1.4rem;
            color: #000000;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000000;
            font-weight: 600;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 600;
            color: #000000;
            font-size: 0.95rem;
        }

        .form-group input {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #ffffff;
            color: #000000;
        }

        .form-group input:focus {
            border-color: #000000;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        /* BOTÓN GENERAR PDF */
        .btn-generar {
            background: #000000;
            color: #ffffff;
            border: 2px solid #000000;
            padding: 16px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .btn-generar:hover {
            background: #333333;
            border-color: #333333;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-generar:disabled {
            background: #666666;
            border-color: #666666;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* PREVISUALIZACIÓN (oculta) */
        .form-container {
            width: 210mm;
            height: 297mm;
            position: fixed;
            top: -10000px;
            left: -10000px;
            background: white;
        }

        .imagen-fondo {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .datos-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

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
            top: 41mm;
            left: 80mm;
            width: 60mm;
        }

        .segundo-apellido {
            top: 41mm;
            left: 90mm;
            width: 60mm;
            display: none;
        }

        .nombres {
            top: 41mm;
            left: 100mm;
            width: 80mm;
            display: none;
        }

        .numero-doc {
            top: 48mm;
            left: 150mm;
            width: 80mm;
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
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease;
            max-width: 400px;
            border-left: 5px solid #000000;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .mensaje.success {
            border-left-color: #008000;
        }

        .mensaje.error {
            border-left-color: #cc0000;
        }

        .mensaje.warning {
            border-left-color: #ffc107;
        }

        .mensaje.info {
            border-left-color: #0066cc;
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

        .mensaje.info i {
            color: #0066cc;
        }

        /* LOADER */
        .loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            z-index: 999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .spinner {
            width: 60px;
            height: 60px;
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
            font-size: 1.2rem;
            color: #000000;
            font-weight: 600;
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
            
            .btn-volver {
                width: 100%;
                justify-content: center;
            }
            
            .formulario-datos {
                padding: 20px;
            }
            
            .mensaje {
                left: 20px;
                right: 20px;
                max-width: none;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .form-header {
                padding: 20px;
            }
            
            .header-text h1 {
                font-size: 1.3rem;
            }
            
            .form-group input {
                padding: 10px 12px;
                font-size: 0.95rem;
            }
            
            .btn-generar {
                padding: 14px 20px;
                font-size: 1rem;
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
            background: rgba(0, 0, 0, 0.9);
            z-index: 1001;
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
            font-size: 1rem;
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
    </style>
</head>
<body>
    <!-- LOADER -->
    <div class="loader" id="loader">
        <div class="spinner"></div>
        <p>Generando documento PDF...</p>
    </div>

    <!-- MENSAJE -->
    <div class="mensaje" id="mensaje">
        <div class="mensaje-content">
            <i class="fas fa-info-circle"></i>
            <div id="mensajeTexto"></div>
        </div>
    </div>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="main-container">
        <!-- HEADER DEL FORMULARIO -->
        <div class="form-header">
            <div class="header-left">
                <div class="form-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="header-text">
                    <h1>Formulario Contrato Mandato</h1>
                    <p>Sistema Renault - Complete los datos y genere el documento oficial</p>
                </div>
            </div>
            <a href="#" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver a Trámites
            </a>
        </div>

        <!-- FORMULARIO DE DATOS -->
        <div class="formulario-datos">
            <h2>Datos del Propietario</h2>
            
            <div class="form-grid">
                @foreach($users as $user)
                <div class="form-group">
                    <label for="inputPrimerApellido">Nombres y Apellidos:</label>
                    <input type="text" value="{{ $user->name }} {{ $user->last_name }} {{ $user->last_name_second }}" id="inputPrimerApellido" placeholder="Ej: PÉREZ" required>
                </div>

                <div class="form-group" style="display: none;">
                    <label for="inputSegundoApellido">Segundo Apellido:</label>
                    <input type="text" value="{{ $user->last_name }}" id="inputSegundoApellido" placeholder="Ej: GÓMEZ" required>
                </div>

                <div class="form-group" style="display: none;">
                    <label for="inputNombres">Nombres:</label>
                    <input type="text" value="dario" id="inputNombres" placeholder="Ej: JUAN CARLOS" required>
                </div>

                <div class="form-group">
                    <label for="inputNumeroDoc">Número de Documento:</label>
                    <input type="text" value="{{ $user->cedula }}" id="inputNumeroDoc" placeholder="Ej: 1234567890" required>
                </div>
                @endforeach
            </div>
        </div>

        <!-- BOTÓN PARA GENERAR PDF -->
        <button class="btn-generar" onclick="mostrarConfirmacion()">
            <i class="fas fa-file-pdf"></i> Generar Documento PDF
        </button>

        <!-- FOOTER -->
        <div class="form-footer">
            <p>Sistema de Gestión Renault &copy; {{ date('Y') }} | Formularios Oficiales de Tránsito</p>
            <p>Este documento es válido para trámites oficiales según la normativa vigente</p>
        </div>
    </div>

    <!-- FORMULARIO INVISIBLE PARA LA CONVERSIÓN A PDF -->
    <div class="form-container" id="formContainer">
        <div class="datos-container">
            <!-- DATOS DEL PROPIETARIO -->
            <div class="dato primer-apellido" id="primerApellido"></div>
            <div class="dato segundo-apellido" id="segundoApellido"></div>
            <div class="dato nombres" id="nombres"></div>
            <div class="dato numero-doc" id="numeroDoc"></div>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN -->
    <div class="modal" id="confirmModal">
        <div class="modal-content">
            <h3><i class="fas fa-file-pdf"></i> Confirmar Generación de PDF</h3>
            <p id="modalMessage">Se generará un documento PDF con los datos ingresados. ¿Desea continuar?</p>
            <div class="modal-buttons">
                <button class="modal-btn confirm" onclick="generarPDF()">Sí, Generar PDF</button>
                <button class="modal-btn cancel" onclick="cerrarModal()">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        // VARIABLES GLOBALES
        let imagenCargada = false;

        // FUNCIONES DEL MODAL
        function mostrarConfirmacion() {
            if (!validarFormulario()) {
                return;
            }
            document.getElementById('confirmModal').style.display = 'flex';
        }

        function cerrarModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });

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

        // FUNCIÓN PARA MOSTRAR/OCULTAR LOADER
        function mostrarLoader(mostrar) {
            document.getElementById('loader').style.display = mostrar ? 'flex' : 'none';
        }

        // FUNCIÓN PARA CARGAR IMAGEN DE FONDO
        async function cargarImagenFondo() {
            if (imagenCargada) return true;
            
            // Intentar diferentes rutas posibles
            const rutasPosibles = [
                '/storage/formularios/mandato.png',
                '/images/mandato.png',
                '/assets/mandato.png',
                './mandato.png',
                'mandato.png',
                'public/mandato.png'
            ];
            
            const formContainer = document.getElementById('formContainer');
            
            for (const ruta of rutasPosibles) {
                try {
                    // Limpiar imágenes existentes
                    const imagenesExistentes = formContainer.querySelectorAll('.imagen-fondo');
                    imagenesExistentes.forEach(img => img.remove());
                    
                    // Crear nueva imagen
                    const imgElement = document.createElement('img');
                    imgElement.className = 'imagen-fondo';
                    imgElement.alt = 'Fondo del formulario';
                    
                    // Intentar cargar la imagen
                    const cargada = await new Promise((resolve) => {
                        imgElement.onload = () => resolve(true);
                        imgElement.onerror = () => resolve(false);
                        imgElement.src = ruta + '?t=' + new Date().getTime(); // Evitar cache
                        
                        // Timeout para no esperar demasiado
                        setTimeout(() => resolve(false), 2000);
                    });
                    
                    if (cargada) {
                        formContainer.insertBefore(imgElement, formContainer.firstChild);
                        imagenCargada = true;
                        console.log('✓ Imagen cargada desde:', ruta);
                        return true;
                    }
                    
                } catch (error) {
                    console.warn(`Error con ruta ${ruta}:`, error);
                    continue;
                }
            }
            
            // Si ninguna ruta funcionó, crear un fondo de respaldo
            console.warn('No se pudo cargar ninguna imagen de fondo');
            crearFondoRespaldo();
            mostrarMensaje('Usando fondo predeterminado', 'warning');
            return false;
        }

        // FUNCIÓN PARA CREAR FONDO DE RESPALDO
        function crearFondoRespaldo() {
            const formContainer = document.getElementById('formContainer');
            
            const fondoDiv = document.createElement('div');
            fondoDiv.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: #ffffff;
                border: 2px solid #000000;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: #000000;
                padding: 40px;
                box-sizing: border-box;
            `;
            
            fondoDiv.innerHTML = `
                <div style="font-size: 28px; font-weight: bold; margin-bottom: 20px; text-align: center;">
                    FORMULARIO DE TRÁNSITO
                </div>
                <div style="font-size: 16px; text-align: center; color: #666666;">
                    Sistema Renault - Documento Oficial
                </div>
            `;
            
            formContainer.insertBefore(fondoDiv, formContainer.firstChild);
        }

        // FUNCIÓN PARA ACTUALIZAR LOS DATOS EN EL FORMULARIO INVISIBLE
        function actualizarDatosPDF() {
            const datos = {
                primerApellido: document.getElementById('inputPrimerApellido').value.trim().toUpperCase(),
                segundoApellido: document.getElementById('inputSegundoApellido').value.trim().toUpperCase(),
                nombres: document.getElementById('inputNombres').value.trim().toUpperCase(),
                numeroDoc: document.getElementById('inputNumeroDoc').value.trim()
            };

            // Actualizar cada campo en el formulario invisible
            Object.keys(datos).forEach(campo => {
                const elemento = document.getElementById(campo);
                if (elemento) {
                    elemento.textContent = datos[campo] || '';
                }
            });

            return datos;
        }

        // FUNCIÓN PARA VALIDAR FORMULARIO
        function validarFormulario() {
            const campos = [
                'inputPrimerApellido',
                'inputSegundoApellido', 
                'inputNombres',
                'inputNumeroDoc'
            ];

            for (const campoId of campos) {
                const elemento = document.getElementById(campoId);
                const valor = elemento.value.trim();
                
                if (!valor) {
                    elemento.focus();
                    elemento.style.borderColor = '#cc0000';
                    
                    setTimeout(() => {
                        elemento.style.borderColor = '#e0e0e0';
                    }, 2000);
                    
                    mostrarMensaje('Por favor, complete todos los campos', 'error');
                    return false;
                }
            }

            return true;
        }

        // FUNCIÓN PRINCIPAL PARA GENERAR PDF
        async function generarPDF() {
            cerrarModal();
            
            // Validar formulario
            if (!validarFormulario()) {
                return;
            }

            // Mostrar loader
            mostrarLoader(true);

            try {
                // 1. Actualizar datos en el formulario invisible
                actualizarDatosPDF();
                
                // 2. Cargar imagen de fondo
                await cargarImagenFondo();
                
                // 3. Esperar un momento para que todo se renderice
                await new Promise(resolve => setTimeout(resolve, 500));
                
                // 4. Capturar el formulario como imagen
                const formulario = document.getElementById('formContainer');
                
                const canvas = await html2canvas(formulario, {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    width: formulario.offsetWidth,
                    height: formulario.offsetHeight
                });
                
                // 5. Crear PDF
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });
                
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();
                
                // Calcular dimensiones para que quepa perfectamente
                const imgWidth = pageWidth;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                
                let y = 0;
                if (imgHeight < pageHeight) {
                    y = (pageHeight - imgHeight) / 2;
                }
                
                // Agregar imagen al PDF
                const imgData = canvas.toDataURL('image/jpeg', 0.95);
                pdf.addImage(imgData, 'JPEG', 0, y, imgWidth, imgHeight);
                
                // 6. Generar nombre del archivo
                const fecha = new Date();
                const primerApellido = document.getElementById('inputPrimerApellido').value.trim().toUpperCase();
                const numeroDoc = document.getElementById('inputNumeroDoc').value.trim();
                
                const nombreArchivo = `Formulario_Transito_${primerApellido}_${numeroDoc}_${fecha.getFullYear()}${String(fecha.getMonth()+1).padStart(2,'0')}${String(fecha.getDate()).padStart(2,'0')}.pdf`;
                
                // 7. Guardar PDF
                pdf.save(nombreArchivo);
                
                // 8. Mostrar mensaje de éxito
                mostrarMensaje(`✅ Documento PDF generado: ${nombreArchivo}`, 'success');
                
            } catch (error) {
                console.error('Error al generar PDF:', error);
                mostrarMensaje('❌ Error al generar el documento PDF', 'error');
                
                // Método alternativo simple
                try {
                    await generarPDFSimple();
                } catch (error2) {
                    mostrarMensaje('❌ Error crítico al generar el documento', 'error');
                }
                
            } finally {
                // Ocultar loader
                mostrarLoader(false);
            }
        }

        // MÉTODO ALTERNATIVO SIMPLE
        async function generarPDFSimple() {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({
                orientation: 'landscape',
                unit: 'mm',
                format: 'a4'
            });
            
            // Obtener datos
            const datos = actualizarDatosPDF();
            
            // Título
            pdf.setFontSize(20);
            pdf.text('FORMULARIO DE TRÁNSITO', 105, 30, { align: 'center' });
            
            // Línea separadora
            pdf.setDrawColor(0, 0, 0);
            pdf.setLineWidth(1);
            pdf.line(20, 40, 190, 40);
            
            // Datos
            pdf.setFontSize(16);
            pdf.text('DATOS DEL PROPIETARIO', 20, 55);
            
            pdf.setFontSize(12);
            let y = 70;
            const lineHeight = 8;
            
            const campos = [
                ['Primer Apellido:', datos.primerApellido],
                ['Segundo Apellido:', datos.segundoApellido],
                ['Nombres:', datos.nombres],
                ['Número Documento:', datos.numeroDoc]
            ];
            
            campos.forEach(([label, value]) => {
                pdf.text(`${label}`, 20, y);
                pdf.text(`${value}`, 80, y);
                y += lineHeight;
            });
            
            // Firma y fecha
            pdf.setFontSize(10);
            y = 180;
            pdf.text('Firma del Propietario: _________________________', 20, y);
            pdf.text('Fecha: ' + new Date().toLocaleDateString(), 150, y);
            pdf.text('Sistema de Gestión Renault', 105, 195, { align: 'center' });
            
            // Nombre del archivo
            const nombreArchivo = `formulario_transito_${Date.now()}.pdf`;
            pdf.save(nombreArchivo);
            
            mostrarMensaje('PDF alternativo generado', 'info');
        }

        // INICIALIZAR AL CARGAR LA PÁGINA
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Formulario de Tránsito - Sistema Renault');
            
            // Poner foco en el primer campo
            document.getElementById('inputPrimerApellido').focus();
            
            // Permitir enviar con Enter en el último campo
            document.getElementById('inputNumeroDoc').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    mostrarConfirmacion();
                }
            });
            
            // Cargar imagen de fondo al inicio
            cargarImagenFondo();
            
            // Efecto de enfoque en inputs
            document.querySelectorAll('input').forEach(element => {
                element.addEventListener('focus', function() {
                    this.style.borderColor = '#000000';
                });
                
                element.addEventListener('blur', function() {
                    this.style.borderColor = '#e0e0e0';
                });
            });
        });
    </script>
</body>
</html>