<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renault - Portal de Administración</title>
    <meta name="description" content="Portal de administración Renault">
    <meta name="author" content="Renault">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <link rel="shortcut icon" href="/adminsite/img/renault-favicon.png">
    <link rel="apple-touch-icon" href="/adminsite/img/renault-icon57.png" sizes="57x57">
    <link rel="apple-touch-icon" href="/adminsite/img/renault-icon72.png" sizes="72x72">
    <link rel="apple-touch-icon" href="/adminsite/img/renault-icon76.png" sizes="76x76">
    <link rel="apple-touch-icon" href="/adminsite/img/renault-icon114.png" sizes="114x114">
    <link rel="apple-touch-icon" href="/adminsite/img/renault-icon120.png" sizes="120x120">
    <link rel="apple-touch-icon" href="/adminsite/img/renault-icon144.png" sizes="144x144">
    <link rel="apple-touch-icon" href="/adminsite/img/renault-icon152.png" sizes="152x152">
    <link rel="apple-touch-icon" href="/adminsite/img/renault-icon180.png" sizes="180x180">

    <!-- Font Awesome -->
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
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        /* CONTENEDOR PRINCIPAL - Totalmente centrado */
        .container {
            width: 100%;
            max-width: 500px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 10;
            margin: 0 auto;
        }

        /* LOGO Y TÍTULO */
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
            color: #000000;
            animation: fadeInUp 0.8s ease-out;
            width: 100%;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
            color: #000000;
            font-weight: 500;
            letter-spacing: -0.5px;
        }

        .logo-container p {
            font-size: 1rem;
            color: #666666;
            font-weight: 300;
        }

        .renault-logo {
            width: 200px;
            height: auto;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .renault-logo:hover {
            transform: scale(1.02);
        }

        .sistema {
            color: #d4af37;
            font-weight: 500;
            font-size: 1.2rem;
            margin-top: 10px;
            letter-spacing: 1px;
        }

        /* PANEL DE LOGIN */
        .auth-panel {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            border-radius: 12px;
            border: 2px solid #000000;
            overflow: hidden;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .auth-panel:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .auth-header {
            background: #000000;
            color: #ffffff;
            padding: 25px;
            text-align: center;
            border-bottom: 3px solid #d4af37;
        }

        .auth-header h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            font-weight: 400;
            color: #ffffff;
        }

        .auth-header h2 i {
            color: #d4af37;
            margin-right: 10px;
        }

        .auth-header p {
            font-size: 0.95rem;
            color: #cccccc;
        }

        .auth-body {
            padding: 35px 30px;
        }

        /* FORMULARIO */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #000000;
            font-size: 0.95rem;
        }

        .form-group label i {
            color: #d4af37;
            margin-right: 5px;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #d4af37;
            font-size: 1.1rem;
            z-index: 1;
        }

        .input-with-icon input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            background: #f8f8f8;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            outline: none;
            color: #000000;
        }

        .input-with-icon input::placeholder {
            color: #999999;
            font-size: 0.95rem;
        }

        .input-with-icon input:focus {
            border-color: #000000;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
        }

        .input-with-icon input.error {
            border-color: #dc3545;
            background: #fff8f8;
        }

        .input-with-icon input.success {
            border-color: #28a745;
            background: #f8fff8;
        }

        /* CHECKBOX PERSONALIZADO */
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666666;
            font-size: 0.95rem;
            margin-top: 5px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #d4af37;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            accent-color: #000000;
        }

        .form-check-input:checked {
            background: #000000;
            border-color: #000000;
        }

        .form-check-label {
            cursor: pointer;
            user-select: none;
        }

        /* BOTONES */
        .form-actions {
            margin-top: 30px;
        }

        .btn {
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: #000000;
            color: #ffffff;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #333333;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-primary i {
            transition: transform 0.3s ease;
        }

        .btn-primary:hover i {
            transform: translateX(5px);
        }

        .btn-primary.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-primary.loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ENLACES */
        .link-container {
            margin-top: 20px;
            text-align: center;
        }

        #link-reminder-login {
            color: #666666;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            position: relative;
        }

        #link-reminder-login::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: #000000;
            transition: width 0.3s ease;
        }

        #link-reminder-login:hover {
            color: #000000;
        }

        #link-reminder-login:hover::after {
            width: 100%;
        }

        /* SEPARADOR */
        .login-divider {
            text-align: center;
            margin: 25px 0 15px;
            position: relative;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #e0e0e0;
        }

        .login-divider::before {
            left: 0;
        }

        .login-divider::after {
            right: 0;
        }

        .login-divider span {
            background: #ffffff;
            padding: 0 15px;
            color: #999999;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        /* MENSAJES */
        .message {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
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
            background: #fff8f8;
            border-color: #dc3545;
            color: #dc3545;
        }

        .message.success {
            background: #f8fff8;
            border-color: #28a745;
            color: #28a745;
        }

        .message.info {
            background: #f8f9ff;
            border-color: #0d6efd;
            color: #0d6efd;
        }

        .message i {
            font-size: 1.5rem;
        }

        .message strong {
            display: block;
            margin-bottom: 5px;
        }

        .message p {
            margin: 0;
            font-size: 0.9rem;
        }

        .message ul {
            margin: 5px 0 0 20px;
            padding: 0;
        }

        /* HELP BLOCK */
        .help-block {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        /* LOADER */
        .loader {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            color: #000000;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid #f0f0f0;
            border-top: 3px solid #000000;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        .loader p {
            font-size: 1rem;
            color: #666666;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 20px;
            color: #999999;
            font-size: 0.85rem;
            margin-top: 20px;
            border-top: 1px solid #e0e0e0;
            width: 100%;
        }

        .footer p {
            margin: 3px 0;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .auth-panel {
                max-width: 100%;
            }
            
            .logo-container h1 {
                font-size: 1.8rem;
            }
            
            .auth-header h2 {
                font-size: 1.5rem;
            }

            .renault-logo {
                width: 160px;
            }

            .auth-body {
                padding: 25px 20px;
            }
        }

        /* Centrado perfecto en cualquier resolución */
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            margin: auto;
        }
    </style>
</head>
<body>
    <!-- CONTENEDOR PRINCIPAL - Perfectamente centrado -->
    <div class="container">
        <!-- LOGO Y TÍTULO -->
        <div class="logo-container">
            <img src="/images/logo-renault-plan-rombo.png" width="60%">
        </div>

        <!-- PANEL DE LOGIN -->
        <div class="auth-panel" id="authPanel">
            <div class="auth-header">
                <h2><i class="fas fa-user-shield"></i> Validación de Acceso</h2>
                <p>Ingrese sus credenciales para acceder al sistema</p>
            </div>
            <div class="auth-body">
                <!-- MENSAJES DE ERROR -->
                @if(session('error'))
                <div class="message error" id="errorMessage">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Error de Autenticación</strong>
                        <p id="errorText">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @if(isset($errors) && $errors->any())
                <div class="message error" id="validationMessage">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Error de Validación</strong>
                        <ul style="margin: 5px 0 0 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                @if(session('success'))
                <div class="message success" id="successMessage">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Operación Exitosa</strong>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <!-- FORMULARIO DE LOGIN -->
                <form id="loginForm" action="{{ url('/login') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Correo Electrónico:
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope"></i>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="correo@renault.com" 
                                required
                                autocomplete="email"
                                autofocus
                                class="{{ $errors->has('email') ? 'error' : '' }}"
                            >
                        </div>
                        @if($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Contraseña:
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="••••••••" 
                                required
                                autocomplete="current-password"
                                class="{{ $errors->has('password') ? 'error' : '' }}"
                            >
                        </div>
                        @if($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" 
                                   id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Recordar mi sesión en este dispositivo
                            </label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="loginBtn">
                            <i class="fas fa-sign-in-alt"></i> Ingresar al Sistema
                        </button>
                    </div>

                    <div class="login-divider">
                        <span>RENAULT SEGURIDAD</span>
                    </div>

                    <div class="link-container">
                        <a href="{{ url('/renault/password/reset') }}" id="link-reminder-login">
                            <i class="fas fa-question-circle"></i> ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </form>

                <!-- LOADER -->
                <div class="loader" id="loader">
                    <div class="spinner"></div>
                    <p>Validando credenciales...</p>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p>Sistema de Administración Renault Plan Rombo &copy; {{ date('Y') }}</p>
            <p>Contacto: soporte@renault.com | Tel: 01-800-123-4567</p>
            <p style="font-size: 0.8rem; margin-top: 8px;">
                <i class="fas fa-shield-alt"></i> Acceso restringido - Personal autorizado
            </p>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const loader = document.getElementById('loader');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            // Validar formato de email en tiempo real
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    validateEmail();
                });
            }

            // Enviar formulario
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Validar campos antes de enviar
                    if (!validateForm()) {
                        e.preventDefault();
                        return;
                    }
                    
                    // Mostrar loader y deshabilitar botón
                    loginBtn.disabled = true;
                    loginBtn.classList.add('loading');
                    loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
                    loader.style.display = 'flex';
                    
                    // Ocultar mensajes de error anteriores
                    const errorMessages = document.querySelectorAll('.message.error');
                    errorMessages.forEach(msg => {
                        msg.style.opacity = '0';
                        setTimeout(() => {
                            msg.style.display = 'none';
                        }, 300);
                    });
                });
            }

            // Función para validar formulario
            function validateForm() {
                let isValid = true;
                
                // Validar email
                if (!validateEmail()) {
                    isValid = false;
                }
                
                // Validar password
                if (!passwordInput || passwordInput.value.trim() === '') {
                    passwordInput.classList.add('error');
                    showNotification('La contraseña es obligatoria', 'error');
                    isValid = false;
                } else {
                    passwordInput.classList.remove('error');
                }
                
                return isValid;
            }

            // Función para validar email
            function validateEmail() {
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (!emailRegex.test(email) && email !== '') {
                    emailInput.classList.add('error');
                    return false;
                } else {
                    emailInput.classList.remove('error');
                    emailInput.classList.add('success');
                    return true;
                }
            }

            // Función para mostrar notificación
            function showNotification(message, type = 'info') {
                let messageDiv = document.getElementById('notificationMessage');
                
                if (!messageDiv) {
                    messageDiv = document.createElement('div');
                    messageDiv.id = 'notificationMessage';
                    messageDiv.className = `message ${type}`;
                    messageDiv.innerHTML = `
                        <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                        <div>
                            <strong>${type === 'error' ? 'Error' : 'Información'}</strong>
                            <p>${message}</p>
                        </div>
                    `;
                    
                    const authBody = document.querySelector('.auth-body');
                    const form = document.getElementById('loginForm');
                    authBody.insertBefore(messageDiv, form);
                } else {
                    messageDiv.className = `message ${type}`;
                    messageDiv.querySelector('p').textContent = message;
                    messageDiv.style.display = 'flex';
                }
                
                setTimeout(() => {
                    if (messageDiv) {
                        messageDiv.style.opacity = '0';
                        setTimeout(() => {
                            messageDiv.style.display = 'none';
                        }, 300);
                    }
                }, 5000);
            }

            // Auto-ocultar mensajes de error después de 5 segundos
            setTimeout(() => {
                const errorMessages = document.querySelectorAll('.message.error');
                errorMessages.forEach(msg => {
                    msg.style.transition = 'opacity 0.3s';
                    msg.style.opacity = '0';
                    setTimeout(() => {
                        msg.style.display = 'none';
                    }, 300);
                });
            }, 5000);
        });
    </script>
</body>
</html>