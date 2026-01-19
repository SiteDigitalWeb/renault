<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Trámites - Renault</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* RESET Y ESTILOS GENERALES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            color: #333333;
        }

        /* CONTENEDOR PRINCIPAL */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* HEADER SUPERIOR */
        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 30px;
            border-bottom: 2px solid #000000;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-placeholder {
            width: 60px;
            height: 60px;
            background: #000000;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 24px;
            border: 2px solid #000000;
        }

        .logo-text h1 {
            font-size: 1.8rem;
            color: #000000;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .logo-text p {
            color: #666666;
            font-size: 0.9rem;
        }

        /* INFO DEL USUARIO */
        .user-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: #000000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.2rem;
            border: 2px solid #000000;
        }

        .user-details {
            text-align: right;
        }

        .user-details h3 {
            color: #000000;
            font-size: 1.1rem;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .user-details p {
            color: #666666;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .logout-form {
            display: inline;
        }

        .logout-btn {
            background: #000000;
            color: #ffffff;
            border: 2px solid #000000;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #333333;
            border-color: #333333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* MENSAJES */
        .alert-container {
            margin-bottom: 30px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 15px;
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

        .alert-success {
            background: #f0f9f0;
            color: #008000;
            border-color: #c6e9c6;
        }

        .alert-error {
            background: #fdf2f2;
            color: #cc0000;
            border-color: #f8d7da;
        }

        .alert i {
            font-size: 1.3rem;
        }

        /* TÍTULO DE SECCIÓN */
        .section-title {
            font-size: 1.8rem;
            color: #000000;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 600;
        }

        /* GRID DE DOCUMENTOS */
        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        /* TARJETAS DE DOCUMENTOS */
        .document-card {
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .document-card:hover {
            transform: translateY(-5px);
            border-color: #000000;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            padding: 25px 25px 15px;
            position: relative;
        }

        .document-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 30px;
            color: #ffffff;
            background: #000000;
        }

        .icon-1 { background: linear-gradient(135deg, #000000 0%, #333333 100%); }
        .icon-2 { background: linear-gradient(135deg, #2c3e50 0%, #000000 100%); }
        .icon-3 { background: linear-gradient(135deg, #000000 0%, #666666 100%); }
        .icon-4 { background: linear-gradient(135deg, #333333 0%, #000000 100%); }
        .icon-5 { background: linear-gradient(135deg, #000000 0%, #4a4a4a 100%); }

        .document-card h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #000000;
            font-weight: 600;
        }

        .document-card p {
            color: #666666;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .card-body {
            padding: 0 25px 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .document-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }

        .tag {
            padding: 4px 12px;
            background: #f5f5f5;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #000000;
            border: 1px solid #e0e0e0;
        }

        .tag.urgent { 
            background: #fff5f5; 
            color: #cc0000; 
            border-color: #ffcccc;
        }
        .tag.new { 
            background: #f0fff4; 
            color: #008000; 
            border-color: #c6f6d5;
        }

        .document-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #888888;
            margin-bottom: 20px;
            margin-top: auto;
        }

        .document-info span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .open-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: #000000;
            color: #ffffff;
            text-align: center;
            border: 2px solid #000000;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            margin-top: auto;
        }

        .open-btn:hover {
            background: #333333;
            border-color: #333333;
            transform: scale(1.02);
        }

        .open-btn i {
            margin-right: 8px;
        }

        /* FILTROS Y BÚSQUEDA */
        .filters-section {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            margin-bottom: 30px;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-box input {
            flex-grow: 1;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .search-box input:focus {
            border-color: #000000;
            outline: none;
        }

        .search-btn {
            background: #000000;
            color: #ffffff;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .search-btn:hover {
            background: #333333;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 10px 20px;
            background: #ffffff;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            color: #666666;
            transition: all 0.3s;
        }

        .filter-tab:hover,
        .filter-tab.active {
            background: #000000;
            color: #ffffff;
            border-color: #000000;
        }

        /* ESTADÍSTICAS */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            color: #000000;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666666;
            font-size: 0.9rem;
        }

        /* FOOTER */
        .main-footer {
            text-align: center;
            padding: 30px 0;
            margin-top: 50px;
            border-top: 2px solid #e0e0e0;
            color: #666666;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 15px;
        }

        .footer-links a {
            color: #666666;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #000000;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .main-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .user-section {
                flex-direction: column;
                text-align: center;
            }
            
            .user-details {
                text-align: center;
            }
            
            .documents-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
            
            .logo-text h1 {
                font-size: 1.5rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }

        /* ANIMACIONES */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .document-card {
            animation: fadeIn 0.5s ease;
        }

        /* MODAL DE CONFIRMACIÓN */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
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
        }

        @keyframes modalFade {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .modal-btn {
            padding: 10px 25px;
            border: 2px solid #000000;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .modal-btn.confirm {
            background: #000000;
            color: #ffffff;
        }

        .modal-btn.confirm:hover {
            background: #333333;
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
    <!-- CONTENEDOR PRINCIPAL -->
    <div class="container">
        <!-- HEADER SUPERIOR -->
        <header class="main-header">
            <div class="logo-section">
                <!-- LOGO DE TU EMPRESA - Reemplaza con tu logo -->
                <div class="">
                     <img src="/images/logo-renault-plan-rombo.png" width="50%">
                </div>
                <div class="logo-text">
                    <h1>Sistema de Gestión Renault Plan Rombo</h1>
                    <p>Panel de Documentos y Trámites</p>
                </div>
            </div>
            
            <div class="user-section">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <h3>{{ $user['nombre_completo'] }}</h3>
                    <p>Cédula: {{ $user['cedula'] }}</p>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- MENSAJES -->
        <div class="alert-container">
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ session('error') }}</div>
            </div>
            @endif
        </div>

        <!-- ESTADÍSTICAS -->
        <section class="stats-section">
            <div class="stat-card">
                <div class="stat-number">5</div>
                <div class="stat-label">Documentos Disponibles</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ date('d/m/Y') }}</div>
                <div class="stat-label">Fecha Actual</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Acceso Disponible</div>
            </div>
        </section>

        <!-- FILTROS Y BÚSQUEDA -->
        <section class="filters-section">
            <div class="search-box">
                <input type="text" placeholder="Buscar documentos..." id="searchInput">
                <button class="search-btn" id="searchBtn">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">Todos</button>
                <button class="filter-tab" data-filter="transito">Tránsito</button>
                <button class="filter-tab" data-filter="legal">Legales</button>
                <button class="filter-tab" data-filter="fiscal">Fiscales</button>
                <button class="filter-tab" data-filter="otros">Otros</button>
            </div>
        </section>

        <!-- SECCIÓN DE DOCUMENTOS -->
        <h2 class="section-title">Documentos Disponibles</h2>
        <div class="documents-grid" id="documentsGrid">
            
            <!-- DOCUMENTO 1 -->
            <div class="document-card" data-category="transito">
                <div class="card-header">
                    <div class="document-icon icon-1">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3>Formulario de Tránsito</h3>
                    <p>Formulario oficial para trámites de tránsito vehicular, licencias de conducción y renovación de SOAT.</p>
                </div>
                <div class="card-body">
                   
                    <div class="document-info">
                        <span><i class="far fa-calendar"></i> Actualizado: 15/11/2023</span>
                        <span><i class="far fa-file"></i> PDF</span>
                    </div>
                    <a href="/renault/registro-transito/{{ $user['cedula'] }}" target="_blank" class="open-btn" data-document="Formulario de Tránsito">
                        <i class="fas fa-external-link-alt"></i> Abrir Formulario
                    </a>
                </div>
            </div>

            <!-- DOCUMENTO 2 -->
            <div class="document-card" data-category="fiscal">
                <div class="card-header">
                    <div class="document-icon icon-2">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3>Contrato mandato</h3>
                    <p>Formulario para presentar la declaración de renta anual ante la DIAN. Incluye guía paso a paso.</p>
                </div>
                <div class="card-body">
                  
                    <div class="document-info">
                        <span><i class="far fa-calendar"></i> Vence: 30/04/2024</span>
                        <span><i class="far fa-clock"></i> 15 min</span>
                    </div>
                    <a href="/renault/contrato-mandato/{{ $user['cedula'] }}" target="_blank" class="open-btn" data-document="Declaración de Renta">
                        <i class="fas fa-external-link-alt"></i> Abrir Formulario
                    </a>
                </div>
            </div>

            <!-- DOCUMENTO 3 -->
            <div class="document-card" data-category="legal">
                <div class="card-header">
                    <div class="document-icon icon-3">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h3>Solicitud de Póliza</h3>
                    <p>Documento para otorgar poderes especiales a un representante legal. Formato notarial aprobado.</p>
                </div>
                <div class="card-body">
                  
                    <div class="document-info">
                        <span><i class="far fa-calendar"></i> Actualizado: 05/11/2023</span>
                        <span><i class="far fa-file"></i> DOCX</span>
                    </div>
                    <a href="https://www.ramajudicial.gov.co/poder-especial" target="_blank" class="open-btn" data-document="Poder Especial">
                        <i class="fas fa-external-link-alt"></i> Abrir Formulario
                    </a>
                </div>
            </div>

            <!-- DOCUMENTO 4 -->
            <div class="document-card" data-category="legal">
                <div class="card-header">
                    <div class="document-icon icon-4">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3>Prenda</h3>
                    <p>Solicitud de certificado de tradición y libertad de bienes inmuebles. Validación online disponible.</p>
                </div>
                <div class="card-body">
                    
                    <div class="document-info">
                        <span><i class="far fa-calendar"></i> Válido por: 30 días</span>
                        <span><i class="fas fa-bolt"></i> Rápido</span>
                    </div>
                    <a href="/renault/prenda/{{ $user['cedula'] }}" target="_blank" class="open-btn" data-document="Certificado de Tradición">
                        <i class="fas fa-external-link-alt"></i> Abrir Formulario
                    </a>
                </div>
            </div>

            <!-- DOCUMENTO 5 -->
            <div class="document-card" data-category="transito">
                <div class="card-header">
                    <div class="document-icon icon-5">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h3>Pagare</h3>
                    <p>Renovación y obtención de licencia de conducción. Requisitos y formularios actualizados 2023.</p>
                </div>
                <div class="card-body">
                    
                    <div class="document-info">
                        <span><i class="far fa-calendar"></i> Actualizado: 02/11/2023</span>
                        <span><i class="far fa-file-pdf"></i> PDF</span>
                    </div>
                    <a href="#" class="open-btn" data-document="Licencia de Conducción">
                        <i class="fas fa-external-link-alt"></i> Abrir Formulario
                    </a>
                </div>
            </div>

        </div>

        <!-- FOOTER -->
        <footer class="main-footer">
            <div class="footer-links">
                <a href="#"><i class="fas fa-question-circle"></i> Centro de Ayuda</a>
                <a href="#"><i class="fas fa-file-contract"></i> Términos y Condiciones</a>
                <a href="#"><i class="fas fa-shield-alt"></i> Política de Privacidad</a>
                <a href="#"><i class="fas fa-phone"></i> Contacto</a>
            </div>
            <p>&copy; {{ date('Y') }} Sistema de Gestión Renault. Todos los derechos reservados.</p>
            <p style="font-size: 0.9rem; color: #888888; margin-top: 10px;">
                Versión 1.0.0 | Última actualización: {{ date('d/m/Y') }}
            </p>
        </footer>

        <!-- MODAL DE CONFIRMACIÓN -->
        <div class="modal" id="redirectModal">
            <div class="modal-content">
                <h3><i class="fas fa-external-link-alt"></i> Redireccionando</h3>
                <p id="modalMessage">Será redirigido al formulario seleccionado. ¿Desea continuar?</p>
                <div class="modal-buttons">
                    <button class="modal-btn confirm" id="confirmRedirect">Sí, continuar</button>
                    <button class="modal-btn cancel" id="cancelRedirect">Cancelar</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables globales
            let currentFilter = 'all';
            let redirectUrl = '';
            let redirectTitle = '';

            // Configurar filtros
            const filterTabs = document.querySelectorAll('.filter-tab');
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remover clase active de todos
                    filterTabs.forEach(t => t.classList.remove('active'));
                    // Agregar clase active al seleccionado
                    this.classList.add('active');
                    currentFilter = this.getAttribute('data-filter');
                    
                    // Filtrar documentos
                    filterDocuments();
                });
            });

            // Configurar búsqueda
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            
            searchBtn.addEventListener('click', filterDocuments);
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    filterDocuments();
                }
            });

            // Configurar eventos de apertura de documentos
            document.querySelectorAll('.open-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    const title = this.getAttribute('data-document');
                    
                    redirectUrl = url;
                    redirectTitle = title;
                    
                    document.getElementById('modalMessage').textContent = 
                        `Será redirigido al formulario: "${title}". ¿Desea continuar?`;
                    
                    document.getElementById('redirectModal').style.display = 'flex';
                });
            });

            // Modal de confirmación
            document.getElementById('confirmRedirect').addEventListener('click', function() {
                if (redirectUrl) {
                    window.open(redirectUrl, '_blank');
                    document.getElementById('redirectModal').style.display = 'none';
                }
            });

            document.getElementById('cancelRedirect').addEventListener('click', function() {
                document.getElementById('redirectModal').style.display = 'none';
                redirectUrl = '';
                redirectTitle = '';
            });

            // Cerrar modal al hacer clic fuera
            document.getElementById('redirectModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                    redirectUrl = '';
                    redirectTitle = '';
                }
            });

            // Función para filtrar documentos
            function filterDocuments() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const documents = document.querySelectorAll('.document-card');
                let visibleCount = 0;

                documents.forEach(doc => {
                    const category = doc.getAttribute('data-category');
                    const title = doc.querySelector('h3').textContent.toLowerCase();
                    const description = doc.querySelector('p').textContent.toLowerCase();
                    const tags = Array.from(doc.querySelectorAll('.tag')).map(tag => tag.textContent.toLowerCase());
                    
                    // Verificar filtro de categoría
                    const categoryMatch = currentFilter === 'all' || category === currentFilter;
                    
                    // Verificar búsqueda
                    const searchMatch = !searchTerm || 
                        title.includes(searchTerm) || 
                        description.includes(searchTerm) ||
                        tags.some(tag => tag.includes(searchTerm));
                    
                    // Mostrar/ocultar documento
                    if (categoryMatch && searchMatch) {
                        doc.style.display = 'block';
                        visibleCount++;
                        // Animación de aparición
                        doc.style.animation = 'fadeIn 0.5s ease';
                    } else {
                        doc.style.display = 'none';
                    }
                });

                // Mostrar mensaje si no hay resultados
                showNoResultsMessage(visibleCount === 0);
            }

            // Función para mostrar mensaje de no resultados
            function showNoResultsMessage(show) {
                let message = document.getElementById('noResultsMessage');
                
                if (show) {
                    if (!message) {
                        message = document.createElement('div');
                        message.id = 'noResultsMessage';
                        message.className = 'alert';
                        message.innerHTML = `
                            <i class="fas fa-search"></i>
                            <div>
                                <strong>No se encontraron documentos</strong>
                                <p>Intente con otros términos de búsqueda o cambie el filtro.</p>
                            </div>
                        `;
                        
                        const grid = document.getElementById('documentsGrid');
                        grid.parentNode.insertBefore(message, grid.nextSibling);
                    }
                    message.style.display = 'flex';
                } else if (message) {
                    message.style.display = 'none';
                }
            }

            // Efecto hover mejorado para tarjetas
            document.querySelectorAll('.document-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                    this.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.15)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
                });
            });

            // Inicializar con todos los documentos visibles
            filterDocuments();
        });

        // Animación para nuevos documentos
        const style = document.createElement('style');
        style.textContent = `
            .new-pulse {
                position: relative;
                overflow: hidden;
            }
            
            .new-pulse::before {
                content: "NUEVO";
                position: absolute;
                top: 15px;
                right: -30px;
                background: #000000;
                color: #ffffff;
                padding: 5px 35px;
                font-size: 0.7rem;
                font-weight: bold;
                transform: rotate(45deg);
                z-index: 10;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>