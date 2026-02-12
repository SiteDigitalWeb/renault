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

        .badge-suscriptores {
            background: #000000;
            color: #ffffff;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-left: 10px;
        }

        /* CONTENEDOR DE FORMULARIOS EN VERTICAL */
        .formularios-wrapper {
            display: flex;
            flex-direction: column;
            gap: 30px;
            width: 100%;
            margin-bottom: 30px;
        }

        .formulario-section {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            border: 2px solid #000000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .formulario-section h3 {
            text-align: center;
            margin-bottom: 25px;
            color: #000000;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000000;
        }

        .numero-suscriptor {
            background: #000000;
            color: #ffffff;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
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

        /* FORM GRID */
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

        /* PREVISUALIZACIÓN (oculta) */
        .form-container {
            width: 297mm;
            height: 210mm;
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
            top: 106mm;
            left: 13mm;
            width: 60mm;
        }

        .segundo-apellido {
            top: 106mm;
            left: 52mm;
            width: 60mm;
        }

        .nombres {
            top: 106mm;
            left: 100mm;
            width: 80mm;
        }

        .tipo-doc {
            top: 105mm;
            left: 40mm;
            width: 30mm;
            display: none;
        }

        .numero-doc {
            top: 115.5mm;
            left: 117mm;
            width: 80mm;
        }

        .direccion {
            top: 124.5mm;
            left: 13mm;
            width: 100mm;
        }

        .ciudad {
            top: 124.5mm;
            left: 75mm;
            width: 60mm;
        }

        .telefono {
            top: 124.5mm;
            left: 117mm;
            width: 60mm;
        }

        /* POSICIONES PARA LAS X DE LOS TIPOS DE DOCUMENTO */
        .marca-x {
            position: absolute;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14pt;
            font-weight: bold;
            color: #000000;
            z-index: 10;
        }

        .marca-cc {
            top: 114mm;
            left: 13mm;
        }

        .marca-nit {
            top: 114mm;
            left: 23mm;
        }

        .marca-nn {
            top: 114mm;
            left: 33mm;
        }

        .marca-pasaporte {
            top: 114mm;
            left: 44mm;
        }

        .marca-extranjeria {
            top: 114mm;
            left: 56mm;
        }

        .marca-tidentidad {
            top: 114mm;
            left: 71mm;
        }

        .marca-nuip {
            top: 114mm;
            left: 86mm;
        }

        .marca-diplomatico {
            top: 114mm;
            left: 103mm;
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
            margin: 20px auto 0;
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
            
            .formulario-section {
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
                    <h1>Formulario de Tránsito
                        <span class="badge-suscriptores">{{ $cantidadSuscriptores }} SUSCRIPTORES</span>
                    </h1>
                    <p>Sistema Renault - Complete los datos y genere el documento oficial</p>
                </div>
            </div>
            <a href="{{ route('renault.tramites') }}" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver a Trámites
            </a>
        </div>

        <!-- FORMULARIOS EN VERTICAL -->
        <div class="formularios-wrapper">
            <!-- SUSCRIPTOR 1 -->
            <div class="formulario-section">
                <h3>
                    <span class="numero-suscriptor">1</span>
                    Suscriptor 1
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="inputPrimerApellido">Primer Apellido:</label>
                        <input type="text" value="{{ $user->last_name ?? '' }}" id="inputPrimerApellido" name="primer_apellido" placeholder="Ej: PÉREZ" required>
                    </div>

                    <div class="form-group">
                        <label for="inputSegundoApellido">Segundo Apellido:</label>
                        <input type="text" value="{{ $user->last_name_second ?? '' }}" id="inputSegundoApellido" name="segundo_apellido" placeholder="Ej: GÓMEZ" required>
                    </div>

                    <div class="form-group">
                        <label for="inputNombres">Nombres:</label>
                        <input type="text" value="{{ $user->name ?? '' }}" id="inputNombres" name="nombres" placeholder="Ej: JUAN CARLOS" required>
                    </div>

                    <div class="form-group">
                        <label for="inputTipoDoc">Tipo de Documento:</label>
                        <select id="inputTipoDoc" name="tipo_documento" required onchange="actualizarMarcasDocumento(1)">
                            <option value="">-- Seleccione un tipo --</option>
                            <option value="C.C." {{ (isset($user->tipo_documento) && $user->tipo_documento == 'C.C.') ? 'selected' : '' }}>Cédula de Ciudadanía (C.C.)</option>
                            <option value="NIT" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'NIT') ? 'selected' : '' }}>Número de Identificación Tributaria (NIT)</option>
                            <option value="N.N" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'N.N') ? 'selected' : '' }}>N.N (No Nombre)</option>
                            <option value="PASAPORTE" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'PASAPORTE') ? 'selected' : '' }}>Pasaporte</option>
                            <option value="C.EXTRANJERIA" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'C.EXTRANJERIA') ? 'selected' : '' }}>Cédula de Extranjería</option>
                            <option value="T.IDENTIDAD" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'T.IDENTIDAD') ? 'selected' : '' }}>Tarjeta de Identidad</option>
                            <option value="NUIP" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'NUIP') ? 'selected' : '' }}>Número Único de Identificación Personal (NUIP)</option>
                            <option value="C.DIPLOMATICO" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'C.DIPLOMATICO') ? 'selected' : '' }}>Carné Diplomático</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputNumeroDoc">Número de Documento:</label>
                        <input type="text" value="{{ $user->cedula ?? '' }}" id="inputNumeroDoc" name="numero_documento" placeholder="Ej: 1234567890" required>
                    </div>

                    <div class="form-group">
                        <label for="inputDireccion">Dirección:</label>
                        <input type="text" value="{{ $user->address ?? '' }}" id="inputDireccion" name="direccion" placeholder="Ej: CRA 10 # 20-30" required>
                    </div>

                    <div class="form-group">
                        <label for="inputCiudad">Ciudad:</label>
                        <input type="text" id="inputCiudad" name="ciudad" placeholder="Ej: BOGOTÁ" required>
                    </div>

                    <div class="form-group">
                        <label for="inputTelefono">Teléfono:</label>
                        <input type="text" value="{{ $user->phone ?? '' }}" id="inputTelefono" name="telefono" placeholder="Ej: 3001234567" required>
                    </div>
                </div>
            </div>

            <!-- SUSCRIPTOR 2 (con datos cargados y nombres de campo con _2) -->
            <div class="formulario-section">
                <h3>
                    <span class="numero-suscriptor">2</span>
                    Suscriptor 2
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="inputPrimerApellido_2">Primer Apellido:</label>
                        <input type="text" value="{{ $user->last_name ?? '' }}" id="inputPrimerApellido_2" name="primer_apellido_2" placeholder="Ej: PÉREZ" required>
                    </div>

                    <div class="form-group">
                        <label for="inputSegundoApellido_2">Segundo Apellido:</label>
                        <input type="text" value="{{ $user->last_name_second ?? '' }}" id="inputSegundoApellido_2" name="segundo_apellido_2" placeholder="Ej: GÓMEZ" required>
                    </div>

                    <div class="form-group">
                        <label for="inputNombres_2">Nombres:</label>
                        <input type="text" value="{{ $user->name ?? '' }}" id="inputNombres_2" name="nombres_2" placeholder="Ej: JUAN CARLOS" required>
                    </div>

                    <div class="form-group">
                        <label for="inputTipoDoc_2">Tipo de Documento:</label>
                        <select id="inputTipoDoc_2" name="tipo_documento_2" required onchange="actualizarMarcasDocumento(2)">
                            <option value="">-- Seleccione un tipo --</option>
                            <option value="C.C." {{ (isset($user->tipo_documento) && $user->tipo_documento == 'C.C.') ? 'selected' : '' }}>Cédula de Ciudadanía (C.C.)</option>
                            <option value="NIT" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'NIT') ? 'selected' : '' }}>Número de Identificación Tributaria (NIT)</option>
                            <option value="N.N" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'N.N') ? 'selected' : '' }}>N.N (No Nombre)</option>
                            <option value="PASAPORTE" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'PASAPORTE') ? 'selected' : '' }}>Pasaporte</option>
                            <option value="C.EXTRANJERIA" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'C.EXTRANJERIA') ? 'selected' : '' }}>Cédula de Extranjería</option>
                            <option value="T.IDENTIDAD" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'T.IDENTIDAD') ? 'selected' : '' }}>Tarjeta de Identidad</option>
                            <option value="NUIP" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'NUIP') ? 'selected' : '' }}>Número Único de Identificación Personal (NUIP)</option>
                            <option value="C.DIPLOMATICO" {{ (isset($user->tipo_documento) && $user->tipo_documento == 'C.DIPLOMATICO') ? 'selected' : '' }}>Carné Diplomático</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputNumeroDoc_2">Número de Documento:</label>
                        <input type="text" value="{{ $user->cedula ?? '' }}" id="inputNumeroDoc_2" name="numero_documento_2" placeholder="Ej: 1234567890" required>
                    </div>

                    <div class="form-group">
                        <label for="inputDireccion_2">Dirección:</label>
                        <input type="text" value="{{ $user->address ?? '' }}" id="inputDireccion_2" name="direccion_2" placeholder="Ej: CRA 10 # 20-30" required>
                    </div>

                    <div class="form-group">
                        <label for="inputCiudad_2">Ciudad:</label>
                        <input type="text" id="inputCiudad_2" name="ciudad_2" placeholder="Ej: BOGOTÁ" required>
                    </div>

                    <div class="form-group">
                        <label for="inputTelefono_2">Teléfono:</label>
                        <input type="text" value="{{ $user->phone ?? '' }}" id="inputTelefono_2" name="telefono_2" placeholder="Ej: 3001234567" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTÓN PARA GENERAR PDF -->
        <button class="btn-generar" onclick="mostrarConfirmacion()">
            <i class="fas fa-file-pdf"></i> 
            Generar PDF con {{ $cantidadSuscriptores }} formularios
        </button>

        <!-- FOOTER -->
        <div class="form-footer">
            <p>Sistema de Gestión Renault &copy; {{ date('Y') }} | Formularios Oficiales de Tránsito</p>
            <p>Este documento es válido para trámites oficiales según la normativa vigente</p>
        </div>
    </div>

    <!-- FORMULARIOS INVISIBLES PARA LA CONVERSIÓN A PDF -->
    <!-- Solo el primer suscriptor se replica en PDF -->
    <div class="form-container" id="formContainer1">
        <div class="datos-container">
            <!-- DATOS DEL SUSCRIPTOR 1 -->
            <div class="dato primer-apellido" id="primerApellido1"></div>
            <div class="dato segundo-apellido" id="segundoApellido1"></div>
            <div class="dato nombres" id="nombres1"></div>
            <div class="dato tipo-doc" id="tipoDoc1"></div>
            <div class="dato numero-doc" id="numeroDoc1"></div>
            <div class="dato direccion" id="direccion1"></div>
            <div class="dato ciudad" id="ciudad1"></div>
            <div class="dato telefono" id="telefono1"></div>
            
            <!-- MARCAS DE X PARA LOS TIPOS DE DOCUMENTO -->
            <div class="marca-x marca-cc" id="marcaCC1" style="display: none;">X</div>
            <div class="marca-x marca-nit" id="marcaNIT1" style="display: none;">X</div>
            <div class="marca-x marca-nn" id="marcaNN1" style="display: none;">X</div>
            <div class="marca-x marca-pasaporte" id="marcaPasaporte1" style="display: none;">X</div>
            <div class="marca-x marca-extranjeria" id="marcaExtranjeria1" style="display: none;">X</div>
            <div class="marca-x marca-tidentidad" id="marcaTIdentidad1" style="display: none;">X</div>
            <div class="marca-x marca-nuip" id="marcaNUIP1" style="display: none;">X</div>
            <div class="marca-x marca-diplomatico" id="marcaDiplomatico1" style="display: none;">X</div>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN -->
    <div class="modal" id="confirmModal">
        <div class="modal-content">
            <h3><i class="fas fa-file-pdf"></i> Confirmar Generación de PDF</h3>
            <p id="modalMessage">
                Se generará un PDF con {{ $cantidadSuscriptores }} formularios de suscriptores. ¿Desea continuar?
            </p>
            <div class="modal-buttons">
                <button class="modal-btn confirm" onclick="generarPDF()">Sí, Generar PDF</button>
                <button class="modal-btn cancel" onclick="cerrarModal()">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        // VARIABLES GLOBALES
        let cantidadSuscriptores = {{ $cantidadSuscriptores }};
        let imagenesCargadas = {};

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

        // FUNCIÓN PARA ACTUALIZAR LAS MARCAS DE DOCUMENTO
        function actualizarMarcasDocumento(suscriptorNumero) {
            let tipoDocSeleccionado = '';
            
            if (suscriptorNumero === 1) {
                tipoDocSeleccionado = document.getElementById('inputTipoDoc').value;
            } else if (suscriptorNumero === 2) {
                tipoDocSeleccionado = document.getElementById('inputTipoDoc_2').value;
            }
            
            // Ocultar todas las marcas para este suscriptor
            const todasLasMarcas = [
                'marcaCC',
                'marcaNIT',
                'marcaNN',
                'marcaPasaporte',
                'marcaExtranjeria',
                'marcaTIdentidad',
                'marcaNUIP',
                'marcaDiplomatico'
            ];
            
            todasLasMarcas.forEach(tipo => {
                const elemento = document.getElementById(`${tipo}${suscriptorNumero}`);
                if (elemento) {
                    elemento.style.display = 'none';
                }
            });
            
            // Mostrar la marca correspondiente según el tipo de documento
            let marcaAMostrar = '';
            
            switch(tipoDocSeleccionado) {
                case 'C.C.':
                    marcaAMostrar = 'marcaCC';
                    break;
                case 'NIT':
                    marcaAMostrar = 'marcaNIT';
                    break;
                case 'N.N':
                    marcaAMostrar = 'marcaNN';
                    break;
                case 'PASAPORTE':
                    marcaAMostrar = 'marcaPasaporte';
                    break;
                case 'C.EXTRANJERIA':
                    marcaAMostrar = 'marcaExtranjeria';
                    break;
                case 'T.IDENTIDAD':
                    marcaAMostrar = 'marcaTIdentidad';
                    break;
                case 'NUIP':
                    marcaAMostrar = 'marcaNUIP';
                    break;
                case 'C.DIPLOMATICO':
                    marcaAMostrar = 'marcaDiplomatico';
                    break;
            }
            
            if (marcaAMostrar && suscriptorNumero === 1) {
                const elemento = document.getElementById(`${marcaAMostrar}${suscriptorNumero}`);
                if (elemento) {
                    elemento.style.display = 'block';
                    elemento.textContent = 'X';
                    elemento.style.color = '#000000';
                    elemento.style.fontWeight = 'bold';
                    elemento.style.fontSize = '14pt';
                }
            }
            
            // También actualizar el texto del tipo de documento (solo para suscriptor 1 en PDF)
            if (suscriptorNumero === 1) {
                const tipoDocElement = document.getElementById(`tipoDoc${suscriptorNumero}`);
                if (tipoDocElement) {
                    tipoDocElement.textContent = tipoDocSeleccionado;
                }
            }
        }

        // FUNCIÓN PARA CARGAR IMAGEN DE FONDO
        async function cargarImagenFondo(formContainerId) {
            if (imagenesCargadas[formContainerId]) return true;
            
            // Intentar diferentes rutas posibles
            const rutasPosibles = [
                '/storage/formularios/transito.png',
                '/images/transito.png',
                '/assets/transito.png',
                './transito.png',
                'transito.png',
                'public/transito.png'
            ];
            
            const formContainer = document.getElementById(formContainerId);
            
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
                        imagenesCargadas[formContainerId] = true;
                        console.log('✓ Imagen cargada para:', formContainerId);
                        return true;
                    }
                    
                } catch (error) {
                    console.warn(`Error con ruta ${ruta}:`, error);
                    continue;
                }
            }
            
            // Si ninguna ruta funcionó, crear un fondo de respaldo
            console.warn('No se pudo cargar imagen para:', formContainerId);
            crearFondoRespaldo(formContainerId);
            return false;
        }

        // FUNCIÓN PARA CREAR FONDO DE RESPALDO
        function crearFondoRespaldo(formContainerId) {
            const formContainer = document.getElementById(formContainerId);
            
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
                    Sistema Renault
                </div>
            `;
            
            formContainer.insertBefore(fondoDiv, formContainer.firstChild);
        }

        // FUNCIÓN PARA ACTUALIZAR LOS DATOS EN LOS FORMULARIOS INVISIBLES
        // Solo actualiza el suscriptor 1 para el PDF
        function actualizarDatosPDF() {
            const datosSuscriptores = [];
            
            // Solo actualizamos el suscriptor 1 para el PDF
            for (let i = 1; i <= 1; i++) {
                let prefijo = '';
                if (i === 1) {
                    prefijo = '';
                }
                
                const datos = {
                    primerApellido: document.getElementById(`inputPrimerApellido${prefijo}`).value.trim().toUpperCase(),
                    segundoApellido: document.getElementById(`inputSegundoApellido${prefijo}`).value.trim().toUpperCase(),
                    nombres: document.getElementById(`inputNombres${prefijo}`).value.trim().toUpperCase(),
                    tipoDoc: document.getElementById(`inputTipoDoc${prefijo}`).value.trim().toUpperCase(),
                    numeroDoc: document.getElementById(`inputNumeroDoc${prefijo}`).value.trim(),
                    direccion: document.getElementById(`inputDireccion${prefijo}`).value.trim().toUpperCase(),
                    ciudad: document.getElementById(`inputCiudad${prefijo}`).value.trim().toUpperCase(),
                    telefono: document.getElementById(`inputTelefono${prefijo}`).value.trim()
                };

                // Actualizar cada campo en el formulario invisible
                Object.keys(datos).forEach(campo => {
                    const elemento = document.getElementById(`${campo}${i}`);
                    if (elemento) {
                        elemento.textContent = datos[campo] || '';
                    }
                });

                // Actualizar las marcas de documento para este suscriptor
                actualizarMarcasDocumento(i);
                
                datosSuscriptores.push(datos);
            }

            return datosSuscriptores;
        }

        // FUNCIÓN PARA VALIDAR FORMULARIO (ambos suscriptores)
        function validarFormulario() {
            let todosValidos = true;
            
            // Validar suscriptor 1
            const camposSuscriptor1 = [
                'inputPrimerApellido',
                'inputSegundoApellido', 
                'inputNombres',
                'inputTipoDoc',
                'inputNumeroDoc',
                'inputDireccion',
                'inputCiudad',
                'inputTelefono'
            ];

            for (const campoId of camposSuscriptor1) {
                const elemento = document.getElementById(campoId);
                if (!elemento) continue;
                
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
                        mostrarMensaje(`Suscriptor 1: Por favor, seleccione el tipo de documento`, 'error');
                    } else {
                        mostrarMensaje(`Suscriptor 1: Por favor, complete todos los campos`, 'error');
                    }
                    todosValidos = false;
                    break;
                }
            }
            
            if (!todosValidos) return false;
            
            // Validar suscriptor 2
            const camposSuscriptor2 = [
                'inputPrimerApellido_2',
                'inputSegundoApellido_2', 
                'inputNombres_2',
                'inputTipoDoc_2',
                'inputNumeroDoc_2',
                'inputDireccion_2',
                'inputCiudad_2',
                'inputTelefono_2'
            ];

            for (const campoId of camposSuscriptor2) {
                const elemento = document.getElementById(campoId);
                if (!elemento) continue;
                
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
                        mostrarMensaje(`Suscriptor 2: Por favor, seleccione el tipo de documento`, 'error');
                    } else {
                        mostrarMensaje(`Suscriptor 2: Por favor, complete todos los campos`, 'error');
                    }
                    todosValidos = false;
                    break;
                }
            }

            return todosValidos;
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
                // 1. Actualizar datos en el formulario invisible (solo suscriptor 1)
                const datos = actualizarDatosPDF();
                
                // 2. Cargar imagen de fondo para el formulario
                await cargarImagenFondo('formContainer1');
                
                // 3. Esperar un momento para que todo se renderice
                await new Promise(resolve => setTimeout(resolve, 500));
                
                // 4. Crear PDF
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: 'a4'
                });
                
                // 5. Capturar y agregar el formulario al PDF (solo suscriptor 1)
                const formulario = document.getElementById('formContainer1');
                
                const canvas = await html2canvas(formulario, {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    width: formulario.offsetWidth,
                    height: formulario.offsetHeight
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
                const tipoDoc = document.getElementById('inputTipoDoc').value;
                const numeroDoc = document.getElementById('inputNumeroDoc').value.trim();
                
                let nombreArchivo = `Formulario_Transito_${primerApellido}_${tipoDoc}_${numeroDoc}_${fecha.getFullYear()}${String(fecha.getMonth()+1).padStart(2,'0')}${String(fecha.getDate()).padStart(2,'0')}.pdf`;
                
                // 7. Guardar PDF
                pdf.save(nombreArchivo);
                
                // 8. Mostrar mensaje de éxito
                mostrarMensaje(`✅ Documento PDF generado: ${nombreArchivo}`, 'success');
                
                // 9. Preparar datos para enviar al servidor
                prepararEnvioDatos();
                
            } catch (error) {
                console.error('Error al generar PDF:', error);
                mostrarMensaje('❌ Error al generar el documento PDF', 'error');
                
            } finally {
                // Ocultar loader
                mostrarLoader(false);
            }
        }

        // FUNCIÓN PARA PREPARAR DATOS PARA ENVIAR AL SERVIDOR
        function prepararEnvioDatos() {
            // Recopilar datos de ambos suscriptores
            const datosFormulario = {
                suscriptor1: {
                    primer_apellido: document.getElementById('inputPrimerApellido').value.trim(),
                    segundo_apellido: document.getElementById('inputSegundoApellido').value.trim(),
                    nombres: document.getElementById('inputNombres').value.trim(),
                    tipo_documento: document.getElementById('inputTipoDoc').value,
                    numero_documento: document.getElementById('inputNumeroDoc').value.trim(),
                    direccion: document.getElementById('inputDireccion').value.trim(),
                    ciudad: document.getElementById('inputCiudad').value.trim(),
                    telefono: document.getElementById('inputTelefono').value.trim()
                },
                suscriptor2: {
                    primer_apellido: document.getElementById('inputPrimerApellido_2').value.trim(),
                    segundo_apellido: document.getElementById('inputSegundoApellido_2').value.trim(),
                    nombres: document.getElementById('inputNombres_2').value.trim(),
                    tipo_documento: document.getElementById('inputTipoDoc_2').value,
                    numero_documento: document.getElementById('inputNumeroDoc_2').value.trim(),
                    direccion: document.getElementById('inputDireccion_2').value.trim(),
                    ciudad: document.getElementById('inputCiudad_2').value.trim(),
                    telefono: document.getElementById('inputTelefono_2').value.trim()
                },
                cantidad_suscriptores: cantidadSuscriptores,
                fecha_generacion: new Date().toISOString()
            };

            // Aquí podrías enviar los datos al servidor si es necesario
            console.log('Datos del formulario listos para enviar:', datosFormulario);
            
            // Ejemplo de cómo enviar los datos (descomentar si necesitas)
            // fetch('/api/guardar-formulario', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //     },
            //     body: JSON.stringify(datosFormulario)
            // });
        }

        // INICIALIZAR AL CARGAR LA PÁGINA
        document.addEventListener('DOMContentLoaded', async function() {
            console.log('Formulario de Tránsito - Sistema Renault');
            console.log(`Cantidad de suscriptores: ${cantidadSuscriptores}`);
            
            // Poner foco en el primer campo
            document.getElementById('inputPrimerApellido').focus();
            
            // Configurar eventos para el suscriptor 1
            const tipoDocElement1 = document.getElementById('inputTipoDoc');
            if (tipoDocElement1) {
                tipoDocElement1.addEventListener('change', function() {
                    if (this.value) {
                        this.style.borderColor = '#000000';
                        actualizarMarcasDocumento(1);
                    }
                });
            }
            
            // Configurar eventos para el suscriptor 2
            const tipoDocElement2 = document.getElementById('inputTipoDoc_2');
            if (tipoDocElement2) {
                tipoDocElement2.addEventListener('change', function() {
                    if (this.value) {
                        this.style.borderColor = '#000000';
                        actualizarMarcasDocumento(2);
                    }
                });
            }
            
            // Permitir enviar con Enter en el último campo del suscriptor 2
            const telefonoElement2 = document.getElementById('inputTelefono_2');
            if (telefonoElement2) {
                telefonoElement2.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        mostrarConfirmacion();
                    }
                });
            }
            
            // Efecto de enfoque en inputs de ambos formularios
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(element => {
                element.addEventListener('focus', function() {
                    this.style.borderColor = '#000000';
                });
                
                element.addEventListener('blur', function() {
                    this.style.borderColor = '#e0e0e0';
                });
            });
            
            // Cargar imagen de fondo al inicio
            await cargarImagenFondo('formContainer1');
            
            // Inicializar marcas de documento para el suscriptor 1
            if (tipoDocElement1 && tipoDocElement1.value) {
                actualizarMarcasDocumento(1);
            }
        });
    </script>
</body>
</html>