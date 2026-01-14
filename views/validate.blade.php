<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación de Cédula - Renault</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ESTILOS GENERALES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* CONTENEDOR PRINCIPAL */
        .container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* LOGO Y TÍTULO */
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
            color: #333333;
        }

        .logo-container h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #000000;
            font-weight: 400;
            letter-spacing: -0.5px;
        }

        .logo-container p {
            font-size: 1.1rem;
            color: #666666;
            font-weight: 300;
        }

        .logo {
            font-size: 4rem;
            margin-bottom: 15px;
            color: #000000;
        }

        /* PANEL DE VALIDACIÓN */
        .auth-panel {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            border-radius: 8px;
            border: 2px solid #000000;
            overflow: hidden;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .auth-panel:hover {
            border-color: #333333;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .auth-header {
            background: #000000;
            color: #ffffff;
            padding: 25px;
            text-align: center;
            border-bottom: 2px solid #000000;
        }

        .auth-header h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            font-weight: 400;
            color: #ffffff;
        }

        .auth-header p {
            font-size: 0.95rem;
            color: #cccccc;
        }

        .auth-body {
            padding: 30px;
        }

        /* FORMULARIO DE VALIDACIÓN */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #000000;
            font-size: 1rem;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666666;
            font-size: 1.1rem;
        }

        .input-with-icon input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background: #ffffff;
            border: 2px solid #cccccc;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s;
            outline: none;
            color: #000000;
        }

        .input-with-icon input::placeholder {
            color: #999999;
        }

        .input-with-icon input:focus {
            border-color: #000000;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }

        .input-with-icon input.error {
            border-color: #cc0000;
            box-shadow: 0 0 0 2px rgba(204, 0, 0, 0.1);
        }

        .input-with-icon input.success {
            border-color: #00cc00;
            box-shadow: 0 0 0 2px rgba(0, 204, 0, 0.1);
        }

        .form-actions {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 25px;
            border: 2px solid #000000;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: #000000;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #333333;
            border-color: #333333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: #ffffff;
            color: #000000;
            border: 2px solid #cccccc;
        }

        .btn-secondary:hover {
            background: #f5f5f5;
            border-color: #000000;
            transform: translateY(-2px);
        }

        /* MENSAJES */
        .message {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideDown 0.3s ease;
            border: 2px solid;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.error {
            background: #ffe6e6;
            color: #cc0000;
            border-color: #ffcccc;
        }

        .message.success {
            background: #e6ffe6;
            color: #008000;
            border-color: #ccffcc;
        }

        .message.info {
            background: #e6f2ff;
            color: #0066cc;
            border-color: #cce0ff;
        }

        .message i {
            font-size: 1.5rem;
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

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 20px;
            color: #666666;
            font-size: 0.9rem;
            margin-top: 30px;
            border-top: 2px solid #cccccc;
            width: 100%;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .auth-panel {
                max-width: 100%;
            }
            
            .logo-container h1 {
                font-size: 2rem;
            }
            
            .auth-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- CONTENEDOR PRINCIPAL -->
    <div class="container">
        <!-- LOGO Y TÍTULO -->
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-file-contract"></i>
            </div>
            <h1>Sistema de Trámites Renault</h1>
            <p>Acceso mediante validación por cédula</p>
        </div>

        <!-- PANEL DE VALIDACIÓN -->
        <div class="auth-panel" id="authPanel">
            <div class="auth-header">
                <h2><i class="fas fa-user-shield"></i> Validación de Acceso</h2>
                <p>Ingrese su número de cédula para acceder a los trámites</p>
            </div>
            <div class="auth-body">
                <!-- MENSAJES -->
                @if(session('error'))
                <div class="message error" id="errorMessage">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Acceso Denegado</strong>
                        <p id="errorText">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @if(session('success'))
                <div class="message success" id="successMessage">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Validación Exitosa</strong>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <!-- FORMULARIO DE VALIDACIÓN -->
                <form id="validationForm" action="{{ route('validate.cedula') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="cedula">Número de Cédula:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-id-card"></i>
                            <input 
                                type="text" 
                                id="cedula" 
                                name="cedula" 
                                value="{{ old('cedula') }}"
                                placeholder="Ej: 1234567890" 
                                required
                                pattern="[0-9]{6,12}"
                                maxlength="12"
                                autocomplete="off"
                                autofocus
                            >
                        </div>
                        @error('cedula')
                            <small class="form-text" style="color: #cc0000; font-size: 0.85rem; margin-top: 5px;">
                                {{ $message }}
                            </small>
                        @else
                            <small class="form-text" style="color: #666666; font-size: 0.85rem; margin-top: 5px;">
                                Ingrese solo números (mínimo 6, máximo 12 dígitos)
                            </small>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="validateBtn">
                            <i class="fas fa-sign-in-alt"></i> Validar y Acceder
                        </button>
                        <button type="button" class="btn btn-secondary" id="helpBtn">
                            <i class="fas fa-question-circle"></i> ¿Necesita Ayuda?
                        </button>
                    </div>
                </form>

                <!-- LOADER -->
                <div class="loader" id="loader">
                    <div class="spinner"></div>
                    <p>Validando cédula...</p>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p>Sistema de Trámites Renault &copy; {{ date('Y') }} | Acceso Seguro por Validación de Cédula</p>
            <p>Contacto: soporte@renault.com | Tel: 01-800-123-4567</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const validationForm = document.getElementById('validationForm');
            const cedulaInput = document.getElementById('cedula');
            const validateBtn = document.getElementById('validateBtn');
            const loader = document.getElementById('loader');
            const helpBtn = document.getElementById('helpBtn');

            // Validar formato en tiempo real
            if (cedulaInput) {
                cedulaInput.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, ''); // Solo números
                    validateCedulaFormat();
                });

                // Si hay error de validación, agregar clase error
                @if(session('error_type') == 'validation' && $errors->has('cedula'))
                    cedulaInput.classList.add('error');
                @endif
            }

            // Enviar formulario
            if (validationForm) {
                validationForm.addEventListener('submit', function(e) {
                    // Validar formato antes de enviar
                    if (!validateCedulaFormat()) {
                        e.preventDefault();
                        showError('Por favor, ingrese una cédula válida (6-12 dígitos numéricos)');
                        return;
                    }
                    
                    // Mostrar loader y deshabilitar botón
                    validateBtn.disabled = true;
                    loader.style.display = 'flex';
                });
            }

            // Botón de ayuda
            if (helpBtn) {
                helpBtn.addEventListener('click', function() {
                    showHelp();
                });
            }

            // Función para validar formato de cédula
            function validateCedulaFormat() {
                if (!cedulaInput) return false;
                
                const cedula = cedulaInput.value.trim();
                
                if (cedula.length >= 6 && cedula.length <= 12 && /^\d+$/.test(cedula)) {
                    cedulaInput.classList.remove('error');
                    cedulaInput.classList.add('success');
                    return true;
                } else {
                    cedulaInput.classList.remove('success');
                    cedulaInput.classList.add('error');
                    return false;
                }
            }

            // Función para mostrar error
            function showError(message) {
                let errorDiv = document.getElementById('errorMessage');
                let errorText = document.getElementById('errorText');
                
                if (!errorDiv || !errorText) {
                    // Crear elemento si no existe
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'message error';
                    errorDiv.id = 'errorMessage';
                    errorDiv.innerHTML = `
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>Acceso Denegado</strong>
                            <p id="errorText">${message}</p>
                        </div>
                    `;
                    
                    const authBody = document.querySelector('.auth-body');
                    const form = document.getElementById('validationForm');
                    authBody.insertBefore(errorDiv, form);
                } else {
                    errorText.textContent = message;
                    errorDiv.style.display = 'flex';
                }
                
                // Agitar el input
                cedulaInput.classList.add('error');
                cedulaInput.style.animation = 'none';
                setTimeout(() => {
                    cedulaInput.style.animation = 'shake 0.5s';
                }, 10);
                
                setTimeout(() => {
                    cedulaInput.style.animation = '';
                }, 500);
                
                // Ocultar loader y habilitar botón
                loader.style.display = 'none';
                validateBtn.disabled = false;
            }

            // Función para mostrar ayuda
            function showHelp() {
                alert("AYUDA:\n\n" +
                      "1. Ingrese su número de cédula completo (sin puntos ni espacios).\n" +
                      "2. La cédula debe tener entre 6 y 12 dígitos.\n" +
                      "3. Solo se permiten números.\n" +
                      "4. Si su cédula no está habilitada, contacte al administrador.\n" +
                      "5. Contacto: soporte@renault.com");
            }
        });

        // Añadir estilos de animación
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>