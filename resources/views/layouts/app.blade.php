<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'QRIS Journey Game')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="/qris-template/templatemo-chain-summit.css">

    <style>
        .wallet-badge {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            padding: .15rem .6rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, .3);
            font-size: .8rem;
        }

        /* Biar list materi QRIS lebih rapi */
        .qris-list {
            text-align: left;
            margin: 0.75rem 0 0;
            padding-left: 1.4rem;
            list-style: disc;
        }

        .qris-list li {
            margin-bottom: 0.35rem;
            line-height: 1.5;
        }

        /* Khusus section jenis pembayaran: kartu tetap cantik, teks list rata kiri */
        .qris-section-payments .speaker-card {
            text-align: left;
        }

        .qris-section-payments .speaker-card h3 {
            text-align: center;
            margin-bottom: 0.75rem;
        }
    </style>


    @stack('styles')
</head>

<body>
    <!-- Background Animation dari template -->
    <div class="bg-animation">
        <div class="neural-network" id="neuralNetwork"></div>
        <div class="particles" id="particles"></div>
    </div>

    <!-- Header / Navbar -->
    <header>
        <nav>
            <a href="{{ route('home') }}" class="logo">
                {{-- logo svg sama seperti template --}}
                <svg class="logo-icon" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="50,5 85,25 85,65 50,85 15,65 15,25" fill="none" stroke="url(#gradientStroke)"
                        stroke-width="2" />
                    <circle cx="50" cy="30" r="4" fill="#00ffff" />
                    <circle cx="35" cy="45" r="4" fill="#ff00ff" />
                    <circle cx="65" cy="45" r="4" fill="#ff00ff" />
                    <circle cx="35" cy="65" r="4" fill="#00ffff" />
                    <circle cx="65" cy="65" r="4" fill="#00ffff" />
                    <circle cx="50" cy="55" r="5" fill="#7c3aed" />
                    <line x1="50" y1="30" x2="35" y2="45" stroke="#00ffff" stroke-width="1"
                        opacity="0.5" />
                    <line x1="50" y1="30" x2="65" y2="45" stroke="#00ffff" stroke-width="1"
                        opacity="0.5" />
                    <line x1="35" y1="45" x2="50" y2="55" stroke="#ff00ff" stroke-width="1"
                        opacity="0.5" />
                    <line x1="65" y1="45" x2="50" y2="55" stroke="#ff00ff" stroke-width="1"
                        opacity="0.5" />
                    <line x1="50" y1="55" x2="35" y2="65" stroke="#7c3aed" stroke-width="1"
                        opacity="0.5" />
                    <line x1="50" y1="55" x2="65" y2="65" stroke="#7c3aed" stroke-width="1"
                        opacity="0.5" />
                    <defs>
                        <linearGradient id="gradientStroke" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#00ffff;stop-opacity:1" />
                            <stop offset="50%" style="stop-color:#ff00ff;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                </svg>
                <span class="logo-text">QRIS Journey</span>
            </a>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('game.index') }}">Stage & Level</a></li>
                <li>
                    <span class="wallet-badge">
                        💰 <span id="coin-balance">{{ $coins ?? 0 }}</span> koin
                    </span>
                </li>
            </ul>
            <div class="mobile-menu" onclick="toggleMenu()">
                <span></span><span></span><span></span>
            </div>
        </nav>
        <div class="mobile-nav" id="mobileNav">
            <a href="{{ route('home') }}" onclick="closeMenu()">Beranda</a>
            <a href="{{ route('game.index') }}" onclick="closeMenu()">Stage & Level</a>
        </div>
    </header>

    <main>
        {{-- Hero khusus halaman yang butuh (home) --}}
        @yield('hero')

        {{-- Section umum --}}
        <section class="section" id="main-section">
            @yield('content')
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <h2>QRIS Journey Game</h2>
            <p>Media edukasi pembayaran digital berbasis QRIS.</p>
            <p>
                © {{ date('Y') }} QRIS Journey.
                Template by <a href="https://templatemo.com" rel="nofollow noopener" target="_blank">TemplateMo</a>
            </p>
        </div>
    </footer>

    <script src="qris-template/templatemo-chain-scripts.js"></script>
    @stack('scripts')
</body>

</html>
