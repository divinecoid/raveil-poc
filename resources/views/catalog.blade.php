<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Raveil Industries - Bespoke Automotive Design</title>
    <!-- Premium Fonts: Cormorant Garamond for Serifs, Inter for clean Sans-Serif -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Inter:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #080808;
            --bg-surface: #111111;
            --text-primary: #f0f0f0;
            --text-secondary: #8a8a8a;
            --accent: #ffffff;
            --border: rgba(255, 255, 255, 0.06);
            --transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-base);
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 400;
        }

        .container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 4vw;
        }

        /* --- Animations --- */
        .reveal-up {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1), transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-down {
            opacity: 0;
            transform: translateY(-30px);
            transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1), transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-fade {
            opacity: 0;
            transition: opacity 1.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-scale {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1), transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .active.reveal-up, .active.reveal-down, .active.reveal-scale {
            opacity: 1;
            transform: translate(0) scale(1);
        }
        .active.reveal-fade {
            opacity: 1;
        }

        .delay-1 { transition-delay: 0.1s; }
        .delay-2 { transition-delay: 0.2s; }
        .delay-3 { transition-delay: 0.3s; }
        .delay-4 { transition-delay: 0.4s; }
        .delay-5 { transition-delay: 0.5s; }

        /* --- Header --- */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 2rem 4vw;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            background: linear-gradient(to bottom, rgba(8,8,8,0.9) 0%, rgba(8,8,8,0) 100%);
            backdrop-filter: blur(4px);
        }
        .logo {
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 400;
            letter-spacing: 0.4em;
            text-transform: uppercase;
            color: var(--text-primary);
            text-decoration: none;
        }
        nav {
            display: flex;
            gap: 3rem;
        }
        nav a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            transition: var(--transition);
        }
        nav a:hover {
            color: var(--accent);
        }

        /* --- Scroll Progress Bar --- */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(to right, #8a8a8a, #ffffff);
            width: 0%;
            z-index: 1000;
            transition: width 0.1s ease-out;
        }

        /* --- Hero Section --- */
        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            background-color: #000;
        }
        
        .hero-video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
            pointer-events: none;
        }
        
        .hero-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.55;
            transform: scale(1.15);
            transition: transform 0.1s ease-out;
        }
        
        .hero-video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(8, 8, 8, 0.4) 0%, rgba(8, 8, 8, 0.85) 100%),
                        radial-gradient(circle at center, transparent 30%, rgba(8, 8, 8, 0.95) 100%);
            z-index: 2;
        }
        
        .hero-content {
            position: relative;
            z-index: 3;
            max-width: 900px;
            padding: 0 2rem;
            padding-top: 5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .hero h1 {
            font-size: clamp(3rem, 6vw, 6rem);
            line-height: 1.1;
            margin: 0 auto 1.5rem;
            max-width: 900px;
            letter-spacing: -0.02em;
        }
        
        .hero p {
            font-size: clamp(1rem, 1.5vw, 1.25rem);
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto 4rem;
            font-weight: 300;
            letter-spacing: 0.02em;
        }
        
        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 2rem;
        }
        
        .btn {
            padding: 1rem 2.5rem;
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            text-decoration: none;
            transition: var(--transition);
            border: 1px solid var(--border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary {
            background: var(--accent);
            color: var(--bg-base);
            border-color: var(--accent);
        }
        
        .btn-primary:hover {
            background: transparent;
            color: var(--accent);
        }

        /* Mouse Scroll Indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            opacity: 0.7;
            transition: var(--transition);
        }
        
        .scroll-indicator:hover {
            opacity: 1;
        }
        
        .scroll-text {
            font-size: 0.55rem;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            color: var(--text-secondary);
        }
        
        .mouse {
            width: 20px;
            height: 35px;
            border: 2px solid var(--text-secondary);
            border-radius: 20px;
            display: flex;
            justify-content: center;
            padding-top: 6px;
        }
        
        .wheel {
            width: 3px;
            height: 6px;
            background: #ffffff;
            border-radius: 50%;
            animation: scroll-wheel 1.6s infinite ease-in-out;
        }

        @keyframes scroll-wheel {
            0% { transform: translateY(0); opacity: 0; }
            30% { opacity: 1; }
            100% { transform: translateY(10px); opacity: 0; }
        }

        /* --- Collection Section --- */
        .collection {
            padding: 8rem 0;
            background-color: var(--bg-base);
        }
        .section-header {
            margin-bottom: 4rem;
            text-align: center;
        }
        .section-subtitle {
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 1rem;
            display: block;
        }
        .section-title {
            font-size: 3rem;
            letter-spacing: -0.02em;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px; /* Dempet seperti Instagram */
        }
        
        .product-card {
            position: relative;
            overflow: hidden;
            aspect-ratio: 1 / 1; /* Square like Instagram */
            background: var(--bg-surface);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
        }
        
        .product-image-container {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            opacity: 0.95;
        }
        
        .product-card:hover .product-image {
            transform: scale(1.05);
            opacity: 1;
        }

        /* Hover overlay */
        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(8, 8, 8, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            opacity: 0;
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 10;
            text-align: center;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .product-overlay-content {
            transform: translateY(15px);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
        }

        .product-card:hover .product-overlay-content {
            transform: translateY(0);
        }
        
        .product-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.1rem, 1.8vw, 1.6rem);
            font-weight: 400;
            letter-spacing: 0.02em;
            color: #ffffff;
            line-height: 1.25;
            margin-top: auto;
            margin-bottom: 0.5rem;
        }
        
        .product-price {
            font-family: 'Inter', sans-serif;
            font-size: clamp(0.75rem, 1vw, 0.9rem);
            color: var(--text-secondary);
            font-weight: 300;
            margin-bottom: auto;
            letter-spacing: 0.05em;
        }
        
        .inquire-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #ffffff;
            text-decoration: none;
            padding: 0.6rem 1.2rem;
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: var(--transition);
            background: transparent;
            margin-top: auto;
        }

        .inquire-btn:hover {
            background: #ffffff;
            color: #080808;
            border-color: #ffffff;
        }

        /* --- Craftsmanship Section --- */
        .craftsmanship {
            padding: 8rem 0 4rem;
            background: var(--bg-surface);
            text-align: center;
        }
        .craft-content {
            max-width: 800px;
            margin: 0 auto;
        }
        .craft-content h2 {
            font-size: 3.5rem;
            margin-bottom: 2rem;
        }
        .craft-content p {
            font-size: 0.9rem;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            margin-bottom: 4rem;
            font-weight: 300;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.8;
        }
        .craft-list {
            list-style: none;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }
        .craft-list li {
            padding: 3.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            display: flex;
            justify-content: center;
        }
        .craft-list li:last-child {
            border-bottom: none;
        }
        .craft-item-inner {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 3rem;
            width: 100%;
            max-width: 425px;
            font-size: 1.1rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--text-primary);
            text-align: left;
        }
        .craft-item-inner span {
            color: var(--text-secondary);
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            font-style: italic;
            text-align: left;
            width: 40px;
            text-transform: none;
            letter-spacing: 0.05em;
        }

        /* --- Footer/Contact Section --- */
        footer {
            padding: 4rem 4vw 6rem;
            background: var(--bg-surface);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .footer-logo {
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 300;
            letter-spacing: 0.4em;
            text-transform: uppercase;
            margin-bottom: 3rem;
            color: var(--text-primary);
        }
        .footer-links {
            display: flex;
            flex-direction: row;
            justify-content: center;
            gap: 3rem;
            margin-bottom: 4rem;
        }
        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            transition: var(--transition);
        }
        .footer-links a:hover {
            color: var(--accent);
        }
        .copyright {
            font-size: 0.75rem;
            color: var(--text-secondary);
            letter-spacing: 0.05em;
        }

        @media (max-width: 992px) {
            .craftsmanship {
                padding: 6rem 4vw;
            }
        }
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 1.5rem;
                padding: 1.5rem 4vw;
            }
            nav {
                gap: 1.5rem;
            }
            .hero-actions {
                flex-direction: column;
                gap: 1rem;
            }
            .grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 4px !important;
            }
            .product-overlay {
                padding: 0.75rem;
            }
            .product-name {
                font-size: 0.95rem;
                margin-bottom: 0.25rem;
            }
            .product-price {
                font-size: 0.65rem;
            }
            .inquire-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.55rem;
                letter-spacing: 0.1em;
            }
        }

        /* Hero Tagline */
        .hero-tagline {
            font-size: 0.85rem;
            letter-spacing: 0.5em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 1.5rem;
            display: block;
            font-weight: 400;
        }

        /* Hero Title Stacked */
        .hero-title {
            font-family: 'Inter', sans-serif !important;
            font-size: clamp(6rem, 12vw, 10rem) !important;
            font-weight: 900 !important;
            line-height: 0.82 !important;
            letter-spacing: -0.05em !important;
            text-transform: none !important;
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            margin-bottom: 2.5rem;
            color: #ffffff;
        }

        .hero-title span {
            display: block;
        }

        /* Tagline Marquee */
        .marquee-container {
            width: 100%;
            overflow: hidden;
            background: #000;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 1.5rem 0;
            position: relative;
            z-index: 10;
        }
        
        .marquee-content {
            display: flex;
            white-space: nowrap;
            animation: marquee 25s linear infinite;
            gap: 6rem;
        }
        
        .marquee-content span {
            font-family: 'Inter', sans-serif;
            font-size: 3.5rem;
            font-weight: 500;
            letter-spacing: 0.25em;
            color: transparent;
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.12);
            text-transform: uppercase;
            transition: all 0.4s ease;
        }
        
        .marquee-container:hover .marquee-content span {
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.7);
            color: rgba(255, 255, 255, 0.02);
            text-shadow: 0 0 20px rgba(255,255,255,0.1);
        }
        
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        @media (max-width: 768px) {
            .craft-content h2 {
                font-size: 2.5rem;
            }
            .craft-list li {
                padding: 2.5rem 0;
            }
            .craft-item-inner {
                font-size: 0.85rem;
                letter-spacing: 0.1em;
                gap: 1rem;
                width: 280px;
                max-width: 100%;
            }
            .craft-item-inner span {
                width: 25px;
                font-size: 1.1rem;
            }
            .hero-tagline {
                font-size: 0.75rem;
                letter-spacing: 0.4em;
                margin-bottom: 1rem;
            }
            .hero-title {
                font-size: clamp(4.5rem, 18vw, 7.5rem) !important;
                line-height: 0.82 !important;
                margin-bottom: 1.5rem !important;
            }
            .marquee-container {
                padding: 1rem 0;
            }
            .marquee-content span {
                font-size: 2rem;
                letter-spacing: 0.2em;
            }
        }

        /* WhatsApp Floating Button */
        .whatsapp-btn-float {
            position: fixed;
            bottom: 2.5rem;
            right: 2.5rem;
            background-color: #25d366;
            color: #ffffff;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.85rem 1.5rem;
            box-shadow: 0 8px 30px rgba(37, 211, 102, 0.35);
            z-index: 999;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .whatsapp-btn-float:hover {
            transform: translateY(-5px);
            background-color: #20ba5a;
            box-shadow: 0 12px 35px rgba(37, 211, 102, 0.5);
            color: #ffffff;
        }

        .whatsapp-btn-float svg {
            fill: currentColor;
            display: block;
        }

        .whatsapp-text {
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .whatsapp-btn-float {
                bottom: 2rem;
                right: 2rem;
                width: 3.5rem;
                height: 3.5rem;
                padding: 0;
                border-radius: 50%;
                box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
            }
            .whatsapp-text {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="scroll-progress"></div>

    <header class="reveal-down active">
        <a href="/" class="logo">Raveil</a>
        <nav>
            <a href="#collection">Collection</a>
            <a href="#craftsmanship">Studio</a>
            <a href="#contact">Contact</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-video-container">
                <video autoplay loop muted playsinline referrerpolicy="no-referrer" class="hero-video">
                    <source src="{{ $heroVideoUrl }}" type="video/mp4">
                    <source src="https://assets.mixkit.co/videos/preview/mixkit-sports-car-drifting-on-a-wet-track-40348-large.mp4" type="video/mp4">
                    <source src="https://raw.githubusercontent.com/intel-iot-devkit/sample-videos/master/car-detection.mp4" type="video/mp4">
                </video>
                <div class="hero-video-overlay"></div>
            </div>
            <div class="container hero-content">
                <h1 class="hero-title reveal-up delay-1">
                    <span>Break</span>
                    <span>the</span>
                    <span>Rules.</span>
                </h1>
                <p class="reveal-up delay-2">Precision-engineered carbon fiber components for luxury and performance vehicles</p>
                <div class="hero-actions reveal-up delay-3">
                    <a href="#collection" class="btn btn-primary">Explore Collection</a>
                </div>
            </div>
            <div class="scroll-indicator reveal-fade">
                <span class="mouse">
                    <span class="wheel"></span>
                </span>
                <span class="scroll-text">Scroll to explore</span>
            </div>
        </section>

        <div class="marquee-container">
            <div class="marquee-content">
                <span>Break The Rules</span>
                <span>Break The Rules</span>
                <span>Break The Rules</span>
                <span>Break The Rules</span>
                <span>Break The Rules</span>
                <span>Break The Rules</span>
            </div>
        </div>

        <section id="collection" class="collection container">
            <div class="section-header reveal-up">
                <span class="section-subtitle">Exclusivity</span>
                <h2 class="section-title">The Collection</h2>
            </div>
            
            <div class="grid">
                @forelse($products as $index => $product)
                    <div class="product-card reveal-scale" style="transition-delay: {{ $index * 0.1 }}s">
                        <div class="product-image-container">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 0.75rem; letter-spacing: 0.2em; color: var(--text-secondary);">NO IMAGE</span>
                                </div>
                            @endif
                        </div>
                        <div class="product-overlay">
                            <div class="product-overlay-content">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <span class="product-price">
                                    @if($product->price)
                                        From Rp {{ number_format($product->price, 0, ',', '.') }}
                                    @else
                                        Price on Request
                                    @endif
                                </span>
                                <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hello Raveil Industries, I am interested in the ' . $product->name) }}" target="_blank" class="inquire-btn" data-product-id="{{ $product->id }}">
                                    Inquire
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: var(--text-secondary);" class="reveal-fade">
                        <p style="font-weight: 300; letter-spacing: 0.1em;">The collection is currently empty</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section id="craftsmanship" class="craftsmanship">
            <div class="container">
                <div class="craft-content">
                    <span class="section-subtitle reveal-up">The Process</span>
                    <h2 class="reveal-up delay-1">Uncompromising Precision</h2>
                    <p class="reveal-up delay-2">
                        every component is a testament to our dedication to the art of carbon fiber<br>
                        engineered not just for aesthetics, but for measurable aerodynamic performance
                    </p>
                    <ul class="craft-list">
                        <li class="reveal-up delay-1">
                            <div class="craft-item-inner">
                                <span>01</span> Carbon Fiber Expertise
                            </div>
                        </li>
                        <li class="reveal-up delay-2">
                            <div class="craft-item-inner">
                                <span>02</span> Precision Manufacturing
                            </div>
                        </li>
                        <li class="reveal-up delay-3">
                            <div class="craft-item-inner">
                                <span>03</span> Bespoke Design
                            </div>
                        </li>
                        <li class="reveal-up delay-4">
                            <div class="craft-item-inner">
                                <span>04</span> Aerodynamic Performance
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hello Raveil Industries, I am interested in your bespoke carbon fiber designs.') }}" 
           target="_blank" 
           id="whatsapp-floating-btn" 
           class="whatsapp-btn-float" 
           aria-label="Chat with us on WhatsApp">
            <svg viewBox="0 0 24 24" width="20" height="20">
                <path d="M12.012 2c-5.506 0-9.988 4.482-9.988 9.988 0 1.76.46 3.48 1.333 5l-1.34 4.887 5-1.314c1.474.805 3.128 1.227 4.815 1.227h.005c5.505 0 9.987-4.482 9.987-9.988 0-2.67-1.04-5.18-2.93-7.07C17.2 3.04 14.69 2 12.01 2zm5.72 13.784c-.244.69-1.2 1.265-1.65 1.32-.41.05-1.01.2-2.8-.52-2.29-.93-3.77-3.26-3.89-3.41-.1-.15-.9-1.2-1.01-2.4-.1-1.2.51-1.8.74-2.03.18-.18.49-.28.77-.28.1 0 .19 0 .27.01.27.01.41.03.58.45.2.47.69 1.69.75 1.82.06.13.1.28.01.45-.1.17-.15.28-.3.45-.15.17-.31.39-.45.52-.15.15-.31.31-.13.62.18.3.79 1.3 1.7 2.11.78.7 1.43.91 1.63 1.01.2.1.32.09.43-.04.12-.14.53-.62.67-.83.14-.2.28-.17.47-.1.19.07 1.2.57 1.4.67.2.1.34.15.39.24.05.09.05.53-.19 1.22z"/>
            </svg>
            <span class="whatsapp-text">Chat with us</span>
        </a>

    </main>

    <footer id="contact" class="reveal-fade">
        <span class="footer-logo">Raveil Industries</span>
        <div class="footer-links">
            <a href="https://instagram.com/{{ $instagram }}" target="_blank">Instagram</a>
            <a href="https://wa.me/{{ $whatsapp }}" target="_blank">WhatsApp</a>
        </div>
        <p class="copyright">&copy; {{ date('Y') }} Raveil Industries</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Trigger initial hero animations instantly
            setTimeout(() => {
                document.querySelectorAll('.hero .reveal-up, .scroll-indicator').forEach(el => el.classList.add('active'));
            }, 50);

            // Scroll animations for the rest
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            const revealElements = document.querySelectorAll('.reveal-up:not(.hero .reveal-up), .reveal-down:not(.active), .reveal-scale, .reveal-fade');
            revealElements.forEach(el => observer.observe(el));

            // Scroll interactions
            window.addEventListener('scroll', () => {
                const scroll = window.scrollY;
                
                // Parallax effect on hero video & content
                const heroVideo = document.querySelector('.hero-video');
                const heroContent = document.querySelector('.hero-content');
                const scrollIndicator = document.querySelector('.scroll-indicator');
                
                if (heroVideo) {
                    heroVideo.style.transform = `scale(${1.15 - (scroll * 0.0002)}) translateY(${scroll * 0.06}px)`;
                }
                
                if (heroContent) {
                    heroContent.style.transform = `translateY(${scroll * 0.15}px)`;
                    heroContent.style.opacity = `${1 - (scroll * 0.002)}`;
                }

                if (scrollIndicator) {
                    scrollIndicator.style.opacity = `${0.7 - (scroll * 0.005)}`;
                }

                // Scroll progress bar
                const windowHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = (scroll / windowHeight) * 100;
                const progressBar = document.querySelector('.scroll-progress');
                if (progressBar) {
                    progressBar.style.width = `${scrolled}%`;
                }
            });

            // Track WhatsApp floating button click
            const waFloatingBtn = document.getElementById('whatsapp-floating-btn');
            if (waFloatingBtn) {
                waFloatingBtn.addEventListener('click', () => {
                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
                    
                    fetch('/track-whatsapp-click', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        }
                    });
                });
            }

            // Track Catalogue Item clicks
            document.querySelectorAll('.inquire-btn[data-product-id]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const productId = btn.getAttribute('data-product-id');
                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
                    
                    fetch('/track-product-click', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({ product_id: productId })
                    });
                });
            });
        });
    </script>
</body>
</html>
