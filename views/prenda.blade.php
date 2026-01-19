<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Contrato Prenda - Sistema Renault</title>
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

        .form-group input,
        .form-group select {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #ffffff;
            color: #000000;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #000000;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23000000' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
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

        /* PREVISUALIZACIÓN PARA LAS DOS PÁGINAS (oculta) */
        .form-container {
            width: 210mm;
            height: 297mm;
            position: fixed;
            top: -10000px;
            left: -10000px;
            background: white;
        }

        .form-container.pagina2 {
            top: -20000px;
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
            font-size: 7pt;
            color: #000000;
            text-transform: uppercase;
            line-height: 1.2;
            overflow: hidden;
            white-space: nowrap;
            font-weight: 200;
            background: transparent;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
        }

        /* POSICIONES DE LOS DATOS - PÁGINA 1 (SOLO NOMBRES, DIRECCIÓN, CIUDAD, EMAIL) */
        .nombres-p1 {
            top: 51mm;
            left: 110mm;
            width: 80mm;
            font-size: 7pt;
        }

        .direccion-p1 {
            top: 225mm;
            left: 27mm;
            width: 120mm;
            font-size: 8pt;
        }

        .ciudad-p1 {
            top: 220mm;
            left: 85mm;
            width: 80mm;
            font-size: 8pt;
        }

        .email-p1 {
            top: 225mm;
            left: 102mm;
            width: 120mm;
            font-size: 8pt;
        }

        /* CÉDULA P1 OCULTA - ELIMINADA */
        .cedula-p1 {
            display: none !important;
        }

        /* MARCA DE SELECCIÓN DE TIPO DE PERSONA - PÁGINA 1 */
        .marca-seleccion {
            position: absolute;
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            font-weight: 100;
            color: #000000;
            z-index: 10;
        }

        /* Posición para "A NOMBRE PROPIO" - AJUSTA ESTOS VALORES SEGÚN TU FORMULARIO */
        .marca-nombre-propio {
            top: 59mm;  /* Ajusta esta posición vertical */
            left: 33mm; /* Ajusta esta posición horizontal */
        }

        /* Posición para "REPRESENTANTE LEGAL" - AJUSTA ESTOS VALORES SEGÚN TU FORMULARIO */
        .marca-representante-legal {
            top: 59mm;   /* Ajusta esta posición vertical */
            left: 67mm; /* Ajusta esta posición horizontal */
        }

        /* POSICIONES DE LOS DATOS - PÁGINA 2 (SOLO CÉDULA) */
        .cedula-p2 {
            top: 256mm;
            left: 88mm;
            width: 80mm;
            font-size: 9pt;
        }

        /* MARCAS DE SELECCIÓN PARA PÁGINA 2 - OCULTAS */
        .marca-seleccion-p2,
        .marca-nombre-propio-p2,
        .marca-representante-legal-p2 {
            display: none !important;
        }

        /* Ocultar los demás campos en página 2 ya que no se usarán */
        .primer-apellido-p2,
        .segundo-apellido-p2,
        .nombres-p2,
        .direccion-p2,
        .ciudad-p2,
        .email-p2,
        .fecha-p2,
        .tipo-persona-p2 {
            display: none;
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
            
            .form-group input,
            .form-group select {
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
        <p>Generando contrato PDF de 2 páginas...</p>
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
                    <h1>Formulario Contrato Prenda</h1>
                    <p>Sistema Renault - Complete los datos y genere el contrato oficial (2 páginas)</p>
                </div>
            </div>
            <a href="{{ route('renault.tramites') }}" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver a Trámites
            </a>
        </div>

        <!-- FORMULARIO DE DATOS -->
        <div class="formulario-datos">
            <h2>Datos del Deudor Prendario</h2>
            
            <div class="form-grid">
                @foreach($users as $user)
                <!-- Campo Único: Apellidos y Nombres Completos -->
                <div class="form-group" style="grid-column: span 2;">
                    <label for="inputNombresCompletos">Apellidos y Nombres Completos:</label>
                    <input type="text" value=" {{ $user->name }} {{ $user->last_name }} {{ $user->last_name_second }}" id="inputNombresCompletos" placeholder="Ej: PÉREZ GÓMEZ JUAN CARLOS" required>
                </div>

                <!-- Tipo de Persona (Nuevo campo) -->
                <div class="form-group">
    <label for="inputTipoPersona">Tipo de Persona:</label>
    <select id="inputTipoPersona" required onchange="actualizarMarcasSeleccion()"  disabled>
        <option value="">Seleccione una opción...</option>
        <option value="A NOMBRE PROPIO" {{ $user->tipo_persona == 'A NOMBRE PROPIO' ? 'selected' : '' }}>A NOMBRE PROPIO</option>
        <option value="REPRESENTANTE LEGAL" {{ $user->tipo_persona == 'REPRESENTANTE LEGAL' ? 'selected' : '' }}>REPRESENTANTE LEGAL</option>
    </select>
</div>

                <!-- Número de Documento -->
                <div class="form-group">
                    <label for="inputCedula">Número de Documento:</label>
                    <input type="text" value="{{ $user->cedula }}" id="inputCedula" placeholder="Ej: 1234567890" required>
                </div>

                <!-- Dirección -->
                <div class="form-group">
                    <label for="inputDireccion">Dirección:</label>
                    <input type="text" value="{{ $user->address }}" id="inputDireccion" placeholder="Ej: CRA 45 # 26-85" required>
                </div>

                <!-- Ciudad -->
                <div class="form-group">
                    <label for="inputCiudad">Ciudad:</label>
                    <input type="text" id="inputCiudad" value="Bogotá, D.C." required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="inputEmail">Correo Electrónico:</label>
                    <input type="email" value="{{ $user->email }}" id="inputEmail" placeholder="Ej: correo@ejemplo.com">
                </div>

                <!-- Campos ocultos para compatibilidad -->
                <div style="display: none;">
                    <input type="text" id="inputPrimerApellido" value="{{ $user->last_name }}">
                    <input type="text" id="inputSegundoApellido">
                    <input type="text" id="inputNombres" value="{{ $user->name }}">
                    <input type="text" id="inputTipoDoc">
                </div>
                @endforeach
            </div>
        </div>

        <!-- BOTÓN PARA GENERAR PDF -->
        <button class="btn-generar" onclick="mostrarConfirmacion()">
            <i class="fas fa-file-pdf"></i> Generar Contrato Prenda (2 páginas)
        </button>

        <!-- FOOTER -->
        <div class="form-footer">
            <p>Sistema de Gestión Renault &copy; {{ date('Y') }} | Contrato Prenda sin Tenencia</p>
            <p>Este documento es válido para trámites oficiales según la normativa vigente</p>
        </div>
    </div>

    <!-- PÁGINA 1 - PRENDA 1 (INVISIBLE PARA LA CONVERSIÓN A PDF) -->
    <div class="form-container" id="formContainerPagina1">
        <div class="datos-container">
            <!-- DATOS EN PÁGINA 1: Nombres, dirección, ciudad, correo -->
            <div class="dato nombres-p1" id="nombresP1"></div>
            <div class="dato direccion-p1" id="direccionP1"></div>
            <div class="dato ciudad-p1" id="ciudadP1"></div>
            <div class="dato email-p1" id="emailP1"></div>
            
            <!-- MARCAS DE SELECCIÓN PARA TIPO DE PERSONA - PÁGINA 1 (Solo "X") -->
            <div class="marca-seleccion marca-nombre-propio" id="marcaNombrePropioP1" style="display: none;">X</div>
            <div class="marca-seleccion marca-representante-legal" id="marcaRepresentanteLegalP1" style="display: none;">X</div>
            
            <!-- Campos ocultos para compatibilidad (no se usarán) -->
            <div style="display: none;">
                <div class="dato primer-apellido-p1" id="primerApellidoP1"></div>
                <div class="dato segundo-apellido-p1" id="segundoApellidoP1"></div>
                <div class="dato fecha-p1" id="fechaP1"></div>
                <!-- Cédula oculta en página 1 -->
                <div class="dato cedula-p1" id="cedulaP1"></div>
            </div>
        </div>
    </div>

    <!-- PÁGINA 2 - PRENDA 2 (INVISIBLE PARA LA CONVERSIÓN A PDF) -->
    <div class="form-container pagina2" id="formContainerPagina2">
        <div class="datos-container">
            <!-- DATOS EN PÁGINA 2: Solo cédula -->
            <div class="dato cedula-p2" id="cedulaP2"></div>
            
            <!-- Los demás campos están ocultos por CSS -->
            <div class="dato primer-apellido-p2" id="primerApellidoP2"></div>
            <div class="dato segundo-apellido-p2" id="segundoApellidoP2"></div>
            <div class="dato nombres-p2" id="nombresP2"></div>
            <div class="dato direccion-p2" id="direccionP2"></div>
            <div class="dato ciudad-p2" id="ciudadP2"></div>
            <div class="dato email-p2" id="emailP2"></div>
            <div class="dato tipo-persona-p2" id="tipoPersonaP2"></div>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN -->
    <div class="modal" id="confirmModal">
        <div class="modal-content">
            <h3><i class="fas fa-file-pdf"></i> Confirmar Generación de PDF</h3>
            <p id="modalMessage">Se generará un contrato PDF de 2 páginas con los datos ingresados. ¿Desea continuar?</p>
            <div class="modal-buttons">
                <button class="modal-btn confirm" onclick="generarPDF()">Sí, Generar PDF</button>
                <button class="modal-btn cancel" onclick="cerrarModal()">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        // VARIABLES GLOBALES
        let imagenesCargadas = {
            prenda1: false,
            prenda2: false
        };

        // FUNCIÓN PARA ACTUALIZAR LAS MARCAS DE SELECCIÓN (LAS "X") - SOLO PÁGINA 1
        function actualizarMarcasSeleccion() {
            const tipoPersona = document.getElementById('inputTipoPersona').value;
            
            // Obtener referencias a las marcas en página 1
            const marcaNombrePropioP1 = document.getElementById('marcaNombrePropioP1');
            const marcaRepresentanteLegalP1 = document.getElementById('marcaRepresentanteLegalP1');
            
            // Ocultar todas las marcas primero
            marcaNombrePropioP1.style.display = 'none';
            marcaRepresentanteLegalP1.style.display = 'none';
            
            // Mostrar la marca correspondiente según la selección (solo página 1)
            if (tipoPersona === 'A NOMBRE PROPIO') {
                marcaNombrePropioP1.style.display = 'block';
            } else if (tipoPersona === 'REPRESENTANTE LEGAL') {
                marcaRepresentanteLegalP1.style.display = 'block';
            }
            
            // NOTA: No mostramos el texto del tipo de persona, solo las "X" en página 1
            // NOTA: No mostramos "X" en página 2 como solicitaste
        }

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

        // FUNCIÓN PARA CARGAR IMAGEN DE FONDO DE UNA PÁGINA
        async function cargarImagenFondoPagina(paginaId, nombreImagen) {
            const formContainer = document.getElementById(paginaId);
            const clave = paginaId === 'formContainerPagina1' ? 'prenda1' : 'prenda2';
            
            if (imagenesCargadas[clave]) return true;
            
            // Intentar diferentes rutas posibles
            const rutasPosibles = [
                `/storage/formularios/${nombreImagen}`,
                `/images/${nombreImagen}`,
                `/assets/${nombreImagen}`,
                `./${nombreImagen}`,
                `${nombreImagen}`,
                `public/${nombreImagen}`
            ];
            
            for (const ruta of rutasPosibles) {
                try {
                    // Limpiar imágenes existentes
                    const imagenesExistentes = formContainer.querySelectorAll('.imagen-fondo');
                    imagenesExistentes.forEach(img => img.remove());
                    
                    // Crear nueva imagen
                    const imgElement = document.createElement('img');
                    imgElement.className = 'imagen-fondo';
                    imgElement.alt = 'Fondo del contrato';
                    
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
                        imagenesCargadas[clave] = true;
                        console.log(`✓ ${nombreImagen} cargada desde:`, ruta);
                        return true;
                    }
                    
                } catch (error) {
                    console.warn(`Error con ruta ${ruta}:`, error);
                    continue;
                }
            }
            
            // Si ninguna ruta funcionó, crear un fondo de respaldo
            console.warn(`No se pudo cargar la imagen: ${nombreImagen}`);
            crearFondoRespaldo(formContainer, `Página ${paginaId === 'formContainerPagina1' ? '1' : '2'}`);
            return false;
        }

        // FUNCIÓN PARA CARGAR AMBAS IMÁGENES
        async function cargarImagenesFondo() {
            // Cargar imagen para página 1
            await cargarImagenFondoPagina('formContainerPagina1', 'prenda1.png');
            
            // Cargar imagen para página 2
            await cargarImagenFondoPagina('formContainerPagina2', 'prenda2.png');
            
            if (!imagenesCargadas.prenda1 || !imagenesCargadas.prenda2) {
                mostrarMensaje('⚠️ Algunas imágenes no se cargaron, usando fondo predeterminado', 'warning');
            }
        }

        // FUNCIÓN PARA CREAR FONDO DE RESPALDO
        function crearFondoRespaldo(contenedor, titulo) {
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
                    CONTRATO DE PRENDA
                </div>
                <div style="font-size: 16px; text-align: center; color: #666666;">
                    ${titulo}
                </div>
                <div style="margin-top: 20px; font-size: 14px; color: #999999;">
                    (Imagen no disponible - usando fondo predeterminado)
                </div>
            `;
            
            contenedor.insertBefore(fondoDiv, contenedor.firstChild);
        }

        // FUNCIÓN PARA SEPARAR NOMBRES COMPLETOS
        function separarNombresCompletos(nombresCompletos) {
            const partes = nombresCompletos.trim().split(' ');
            if (partes.length >= 2) {
                // Tomar el primer elemento como primer apellido
                const primerApellido = partes[0] || '';
                // Tomar el segundo elemento como segundo apellido (si existe)
                const segundoApellido = partes.length > 1 ? partes[1] : '';
                // El resto son nombres
                const nombres = partes.slice(2).join(' ') || '';
                
                return {
                    primerApellido: primerApellido.toUpperCase(),
                    segundoApellido: segundoApellido.toUpperCase(),
                    nombres: nombres.toUpperCase(),
                    nombresCompletos: nombresCompletos.toUpperCase()
                };
            } else {
                return {
                    primerApellido: nombresCompletos.toUpperCase(),
                    segundoApellido: '',
                    nombres: '',
                    nombresCompletos: nombresCompletos.toUpperCase()
                };
            }
        }

        // FUNCIÓN PARA ACTUALIZAR LOS DATOS EN AMBAS PÁGINAS
        function actualizarDatosPDF() {
            // Obtener datos del formulario
            const datos = {
                nombresCompletos: document.getElementById('inputNombresCompletos').value.trim().toUpperCase(),
                tipoPersona: document.getElementById('inputTipoPersona').value.trim().toUpperCase(),
                cedula: document.getElementById('inputCedula').value.trim(),
                direccion: document.getElementById('inputDireccion').value.trim().toUpperCase(),
                ciudad: document.getElementById('inputCiudad').value.trim().toUpperCase(),
                email: document.getElementById('inputEmail').value.trim().toUpperCase()
            };

            // Separar nombres completos para compatibilidad
            const nombresSeparados = separarNombresCompletos(datos.nombresCompletos);

            // Actualizar PÁGINA 1: Nombres, dirección, ciudad, correo (NO cédula)
            document.getElementById('nombresP1').textContent = datos.nombresCompletos || '';
            document.getElementById('direccionP1').textContent = datos.direccion || '';
            document.getElementById('ciudadP1').textContent = datos.ciudad || '';
            document.getElementById('emailP1').textContent = datos.email || '';
            
            // Cédula OCULTA en página 1
            document.getElementById('cedulaP1').textContent = ''; // Vacío

            // Actualizar las marcas de selección en página 1 (solo "X")
            actualizarMarcasSeleccion();

            // Campos ocultos para compatibilidad
            document.getElementById('primerApellidoP1').textContent = nombresSeparados.primerApellido || '';
            document.getElementById('segundoApellidoP1').textContent = nombresSeparados.segundoApellido || '';

            // Actualizar PÁGINA 2: Solo cédula (NO "X")
            document.getElementById('cedulaP2').textContent = datos.cedula || '';
            
            // Los demás campos en página 2 están ocultos por CSS
            document.getElementById('primerApellidoP2').textContent = nombresSeparados.primerApellido || '';
            document.getElementById('segundoApellidoP2').textContent = nombresSeparados.segundoApellido || '';
            document.getElementById('nombresP2').textContent = nombresSeparados.nombres || '';
            document.getElementById('direccionP2').textContent = datos.direccion || '';
            document.getElementById('ciudadP2').textContent = datos.ciudad || '';
            document.getElementById('emailP2').textContent = datos.email || '';
            document.getElementById('tipoPersonaP2').textContent = datos.tipoPersona || '';

            return {
                ...datos,
                ...nombresSeparados
            };
        }

        // FUNCIÓN PARA VALIDAR FORMULARIO
        function validarFormulario() {
            const campos = [
                'inputNombresCompletos',
                'inputTipoPersona',
                'inputCedula',
                'inputDireccion',
                'inputCiudad'
            ];

            for (const campoId of campos) {
                const elemento = document.getElementById(campoId);
                let valor = '';
                
                if (elemento.type === 'select-one') {
                    valor = elemento.value;
                } else {
                    valor = elemento.value.trim();
                }
                
                if (!valor) {
                    elemento.focus();
                    elemento.style.borderColor = '#cc0000';
                    
                    setTimeout(() => {
                        elemento.style.borderColor = '#e0e0e0';
                    }, 2000);
                    
                    if (elemento.type === 'select-one') {
                        mostrarMensaje('Por favor, seleccione el tipo de persona', 'error');
                    } else if (campoId === 'inputNombresCompletos') {
                        mostrarMensaje('Por favor, ingrese apellidos y nombres completos', 'error');
                    } else {
                        mostrarMensaje('Por favor, complete todos los campos obligatorios', 'error');
                    }
                    return false;
                }
            }

            return true;
        }

        // FUNCIÓN PARA CAPTURAR UNA PÁGINA COMO IMAGEN
        async function capturarPagina(paginaId) {
            const pagina = document.getElementById(paginaId);
            
            const canvas = await html2canvas(pagina, {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff',
                logging: false,
                width: pagina.offsetWidth,
                height: pagina.offsetHeight
            });
            
            return canvas.toDataURL('image/jpeg', 0.95);
        }

        // FUNCIÓN PRINCIPAL PARA GENERAR PDF CON 2 PÁGINAS
        async function generarPDF() {
            cerrarModal();
            
            // Validar formulario
            if (!validarFormulario()) {
                return;
            }

            // Mostrar loader
            mostrarLoader(true);

            try {
                // 1. Actualizar datos en ambas páginas
                const datos = actualizarDatosPDF();
                
                // 2. Cargar imágenes de fondo (si no están cargadas)
                await cargarImagenesFondo();
                
                // 3. Esperar un momento para que todo se renderice
                await new Promise(resolve => setTimeout(resolve, 500));
                
                // 4. Crear PDF
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });
                
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();
                
                // 5. Capturar y agregar PÁGINA 1
                const imgData1 = await capturarPagina('formContainerPagina1');
                
                // Calcular dimensiones para que quepa perfectamente
                let imgWidth1 = pageWidth;
                let imgHeight1 = pageHeight;
                let y1 = 0;
                
                // Agregar imagen de la página 1 al PDF
                pdf.addImage(imgData1, 'JPEG', 0, y1, imgWidth1, imgHeight1);
                
                // 6. Agregar nueva página y capturar PÁGINA 2
                pdf.addPage();
                const imgData2 = await capturarPagina('formContainerPagina2');
                
                let imgWidth2 = pageWidth;
                let imgHeight2 = pageHeight;
                let y2 = 0;
                
                // Agregar imagen de la página 2 al PDF
                pdf.addImage(imgData2, 'JPEG', 0, y2, imgWidth2, imgHeight2);
                
                // 7. Generar nombre del archivo
                const fecha = new Date();
                const nombresLimpios = datos.nombresCompletos.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '').substring(0, 20);
                const cedulaLimpia = datos.cedula.replace(/\s+/g, '_');
                
                const nombreArchivo = `Contrato_Prenda_${nombresLimpios}_${cedulaLimpia}_${fecha.getFullYear()}${String(fecha.getMonth()+1).padStart(2,'0')}${String(fecha.getDate()).padStart(2,'0')}.pdf`;
                
                // 8. Guardar PDF
                pdf.save(nombreArchivo);
                
                // 9. Mostrar mensaje de éxito
                mostrarMensaje(`✅ Contrato PDF de 2 páginas generado: ${nombreArchivo}`, 'success');
                
            } catch (error) {
                console.error('Error al generar PDF:', error);
                mostrarMensaje('❌ Error al generar el contrato PDF', 'error');
                
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
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });
            
            // Obtener datos
            const datos = actualizarDatosPDF();
            
            // PÁGINA 1
            pdf.setFontSize(20);
            pdf.text('CONTRATO DE PRENDA SIN TENENCIA', 105, 30, { align: 'center' });
            pdf.text('PÁGINA 1', 105, 40, { align: 'center' });
            
            // Línea separadora
            pdf.setDrawColor(0, 0, 0);
            pdf.setLineWidth(1);
            pdf.line(20, 50, 190, 50);
            
            // Datos del usuario
            pdf.setFontSize(14);
            pdf.text('DATOS DEL DEUDOR PRENDARIO', 20, 65);
            
            pdf.setFontSize(12);
            let y = 80;
            const lineHeight = 8;
            
            const camposP1 = [
                ['Nombres Completos:', datos.nombresCompletos],
                ['Tipo de Persona:', datos.tipoPersona],
                ['Dirección:', datos.direccion],
                ['Ciudad:', datos.ciudad],
                ['Email:', datos.email]
            ];
            
            camposP1.forEach(([label, value]) => {
                pdf.text(`${label}`, 20, y);
                pdf.text(`${value}`, 70, y);
                y += lineHeight;
            });
            
            // Mostrar marca de selección para tipo de persona (solo "X") en página 1
            pdf.setFontSize(10);
            if (datos.tipoPersona === 'A NOMBRE PROPIO') {
                pdf.text('X', 33, 100); // Marca "X" en posición de "A NOMBRE PROPIO"
            } else if (datos.tipoPersona === 'REPRESENTANTE LEGAL') {
                pdf.text('X', 67, 100); // Marca "X" en posición de "REPRESENTANTE LEGAL"
            }
            
            // Texto de ejemplo del contrato
            y = 140;
            pdf.setFontSize(10);
            pdf.text('DÉCIMA.- El presente contrato de prenda sin tenencia que se constituye tiene por objeto', 20, y);
            pdf.text('garantizar al ACREEDOR PRENDARIO cualquier obligación, que por cualquier concepto', 20, y + 5);
            pdf.text('tuviere o llegare a contraer EL DEUDOR PRENDARIO a su favor...', 20, y + 10);
            
            // Firma página 1
            pdf.setFontSize(10);
            pdf.text('Firma del Deudor: _________________________', 20, 260);
            pdf.text('Fecha: ' + new Date().toLocaleDateString(), 150, 260);
            pdf.text('Página 1 de 2 - Contrato de Prenda sin Tenencia', 105, 280, { align: 'center' });
            
            // PÁGINA 2
            pdf.addPage();
            
            pdf.setFontSize(20);
            pdf.text('CONTRATO DE PRENDA SIN TENENCIA', 105, 30, { align: 'center' });
            pdf.text('PÁGINA 2', 105, 40, { align: 'center' });
            
            pdf.line(20, 50, 190, 50);
            
            // Continuación del contrato
            pdf.setFontSize(10);
            y = 70;
            const textoContrato = [
                'DÉCIMA PRIMERA.- El término de duración del presente contrato de prenda es de',
                '10 años contrados desde la fecha de firma del presente documento; sin embargo',
                'una vez vencido este término, el gravamen (prenda) sobre EL VEHÍCULO',
                'permanecerá vigente durante todo el tiempo en que EL DEUDOR PRENDARIO',
                'tenga obligaciones a favor del ACREEDOR PRENDARIO.',
                '',
                'DÉCIMA SEGUNDA.- Por el hecho de celebrarse el presente contrato EL ACREEDOR',
                'PRENDARIO no adquiere obligación alguna de carácter legal, ni de ninguna otra',
                'clase, de hacer préstamos o desembolsos al DEUDOR PRENDARIO...',
                '',
                'DÉCIMA TERCERA.- EL DEUDOR PRENDARIO se obliga a mantener asegurado',
                'EL VEHÍCULO contra todo riesgo de incendio, hurto y accidentes...'
            ];
            
            textoContrato.forEach(linea => {
                pdf.text(linea, 20, y);
                y += 6;
            });
            
            // Mostrar solo la cédula en página 2 (NO "X")
            pdf.setFontSize(12);
            pdf.text(`Documento: ${datos.cedula}`, 60, 180);
            
            // NOTA: No mostramos "X" en página 2 como solicitaste
            
            // Firma página 2
            pdf.text('Firma del Acreedor: _________________________', 20, 260);
            pdf.text('Fecha: ' + new Date().toLocaleDateString(), 150, 260);
            pdf.text('Página 2 de 2 - Sistema de Gestión Renault', 105, 280, { align: 'center' });
            
            // Nombre del archivo
            const nombreArchivo = `contrato_prenda_simple_${Date.now()}.pdf`;
            pdf.save(nombreArchivo);
            
            mostrarMensaje('Contrato PDF simple generado', 'info');
        }

        // INICIALIZAR AL CARGAR LA PÁGINA
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Formulario Contrato Prenda - Sistema Renault');
            
            // Poner foco en el primer campo
            document.getElementById('inputNombresCompletos').focus();
            
            // Permitir enviar con Enter en cualquier campo
            document.querySelectorAll('input, select').forEach(element => {
                element.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        mostrarConfirmacion();
                    }
                });
            });
            
            // Cargar imágenes de fondo al inicio
            cargarImagenesFondo();
            
            // Efecto de enfoque en inputs
            document.querySelectorAll('input, select').forEach(element => {
                element.addEventListener('focus', function() {
                    this.style.borderColor = '#000000';
                });
                
                element.addEventListener('blur', function() {
                    this.style.borderColor = '#e0e0e0';
                });
            });
            
            // Inicializar las marcas de selección
            actualizarMarcasSeleccion();
        });
    </script>
</body>
</html>