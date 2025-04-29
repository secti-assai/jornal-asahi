<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @hasSection('title')
            @yield('title') - {{ config('app.name', 'Jornal Asahi') }}
        @else
            {{ config('app.name', 'Jornal Asahi') }}
        @endif
    </title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=1.0">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}?v=1.0">
    <link rel="mask-icon" href="{{ asset('safari-pinned-tab.svg') }}?v=1.0" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e74c3c;
            --secondary-color: #1e3a8a;
            --light-grey: #f8f9fa;
        }
        .brand-logo {
            max-height: 90px;
            margin-right: 30px;
        }
        .header-title {
            font-family: 'Noto Sans JP', sans-serif;
        }
        .navbar-custom {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .nav-link {
            color: #333 !important;
            font-weight: 500;
        }
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .footer-dark {
            background-color: #1a237e;
            color: white;
            padding: 3rem 0;
        }
        .section-title {
            position: relative;
            padding-left: 1rem;
            margin-bottom: 1.5rem;
            font-weight: bold;
        }
        .section-title:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.25rem;
            height: 70%;
            width: 5px;
            background-color: var(--primary-color);
        }
        .live-indicator {
            color: var(--primary-color);
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        /* Melhorias para responsividade */
        @media (max-width: 768px) {
            .brand-logo {
                max-height: 80px; /* Aumentado de 30px para 40px */
            }
            .header-title {
                font-size: 1rem; /* Aumentado de 0.9rem para 1rem */
            }
        }

        /* Modificação para ajustar o posicionamento do menu */
        .navbar-nav {
            margin-left: auto; /* Empurra os itens para direita */
            margin-right: 20px; /* Espaço entre o menu e o botão de login */
        }
        
        /* Aumentar o tamanho dos caracteres japoneses */
        .japanese-text {
            font-size: 1.5rem; /* Aumentado para destaque */
            font-weight: bold;
            color: #1a237e;
        }

        /* Estilos personalizados */
        .pagination {
            margin-bottom: 0;
        }
        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .page-link {
            color: #0d6efd;
            border-radius: 3px;
            margin: 0 2px;
        }
        .page-link:hover {
            color: #0a58ca;
            background-color: #e9ecef;
        }
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
        }

        /* Estilos para indicadores em desktop */
        .carousel-indicators.desktop-indicators {
            bottom: 20px;
            z-index: 200;
        }
        
        /* Estilos para indicadores em mobile */
        .carousel-indicators.mobile-indicators {
            z-index: 200;
        }
        
        /* Estilos do botão para garantir que ele seja clicável */
        #featuredNewsCarousel .carousel-item a.btn {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            pointer-events: auto;
            position: relative;
            z-index: 150;
        }
        
        /* Efeito hover no botão */
        #featuredNewsCarousel .carousel-item a.btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        /* Ajustes responsivos para dispositivos móveis */
        @media (max-width: 767.98px) {
            /* Redução do espaço no gradiente para dispositivos móveis */
            #featuredNewsCarousel .position-absolute.bottom-0 {
                padding: 2rem 1rem !important;
            }
            
            /* Ajuste os tamanhos de texto para dispositivos móveis */
            #featuredNewsCarousel h2 {
                font-size: 1.5rem;
            }
            
            #featuredNewsCarousel p {
                font-size: 0.875rem;
                margin-bottom: 1rem !important;
            }
        }

        .search-form .input-group {
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .search-form .form-control {
            border-radius: 30px 0 0 30px;
            border: none;
            padding: 10px 15px;
        }

        .search-form .btn {
            border-radius: 0 30px 30px 0;
            padding: 10px 20px;
        }

        .search-form .form-control:focus {
            box-shadow: none;
            border-color: transparent;
        }

        /* Torna as imagens do conteúdo responsivas */
        .news-content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 1rem 0;
        }
        
        /* Opcional: Adiciona uma borda e sombra suave às imagens */
        .news-content img {
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        /* Centraliza imagens pequenas e adiciona espaço em volta */
        @media (min-width: 768px) {
            .news-content img:not([style*="width"]), 
            .news-content img[style*="width:"][style*="px"][style*="max-width: 500px"] {
                margin-left: auto;
                margin-right: auto;
            }
        }
        
        /* Estilos para legendas de imagem (se existirem) */
        .news-content figure {
            max-width: 100%;
            margin: 1.5rem 0;
        }
        
        .news-content figcaption {
            font-size: 0.9rem;
            color: #6c757d;
            text-align: center;
            padding: 0.5rem 0;
        }
    </style>
    <!-- Adicionar fontes para suporte japonês -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom mb-4">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <!-- Logo do site com tamanho maior -->
                <img src="{{ asset('storage/jornal-asahi.png') }}" alt="Logo Jornal Asahi" class="brand-logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Removido mx-auto para posicionar à direita -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('news.index') }}">Notícias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://valedosol.assai.pr.gov.br/" target="_blank" rel="noopener noreferrer"">Vale do Sol</a>
                    </li>
                </ul>
                <div class="d-flex">
                    @guest
                        <a class="btn btn-outline-primary" href="{{ route('login') }}">Entrar</a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Sair</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer-dark mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Jornal Asahi</h5>
                    <p class="text-light">Jornal municipal administrado por estudantes do ensino médio e fundamental da cidade de Assaí, focado em notícias, cultura e eventos para os jovens da comunidade.</p>
                    <div class="mt-3">
                        <a href="https://www.facebook.com/prefeituraassai/" target="_blank" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/jornal.asahi/" target="_blank" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.youtube.com/@valedosolpr" target="_blank" class="text-light"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Sobre</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://valedosol.assai.pr.gov.br/" target="_blank" class="text-light text-decoration-none mb-2 d-block">Nossa História</a></li>
                        <li><a href="https://valedosol.assai.pr.gov.br/" target="_blank" class="text-light text-decoration-none mb-2 d-block">Contato</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Conteúdo</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none mb-2 d-block">Notícias</a></li>
                        <li><a href="#" class="text-light text-decoration-none mb-2 d-block">Galeria</a></li>
                        <li><a href="#" class="text-light text-decoration-none mb-2 d-block">Vídeos</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Suporte</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none mb-2 d-block">FAQ</a></li>
                        <li><a href="#" class="text-light text-decoration-none mb-2 d-block">Privacidade</a></li>
                        <li><a href="#" class="text-light text-decoration-none mb-2 d-block">Termos de Uso</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Parceiros</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://www.assai.pr.gov.br/" target="_blank" class="text-light text-decoration-none mb-2 d-block">Prefeitura</a></li>
                    </ul>
                </div>
            </div>
            <hr class="mt-3 mb-3 bg-light">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">© {{ date('Y') }} Jornal Asahi. Todos os direitos reservados.</p>
                    <p class="small">Desenvolvido pela Secretaria de Ciência, Tecnologia e Inovação.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @stack('scripts')
</body>
</html>