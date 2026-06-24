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
            font-size: 1.1rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 4rem;
            color: var(--text-primary);
            text-align: left;
        }
        .craft-list li:last-child {
            border-bottom: none;
        }
        .craft-list li span {
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
                font-size: 1rem;
                gap: 2rem;
            }
            .craft-list li span {
                width: 30px;
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

        /* --- Multi-Level Catalog Transitions --- */
        .catalog-level {
            display: none;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .catalog-level.active-level {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Breadcrumbs --- */
        .catalog-breadcrumbs {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2.5rem;
            font-size: 0.75rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--text-secondary);
        }

        .catalog-breadcrumbs a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }

        .catalog-breadcrumbs a:hover {
            color: var(--accent);
        }

        .catalog-breadcrumbs span.separator {
            opacity: 0.3;
        }

        .catalog-breadcrumbs span.current {
            color: var(--accent);
            font-weight: 500;
        }

        /* --- Brand Cards --- */
        .brand-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
        }

        .brand-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.01) 0%, rgba(255, 255, 255, 0.03) 100%);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            aspect-ratio: 1.2 / 1;
        }

        .brand-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .brand-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), inset 0 0 15px rgba(255, 255, 255, 0.02);
        }

        .brand-card:hover::before {
            opacity: 1;
        }

        .brand-svg-container {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            transition: var(--transition);
        }

        .brand-card:hover .brand-svg-container {
            color: var(--accent);
            transform: scale(1.08);
        }

        .brand-svg-container svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .brand-name-display {
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            font-weight: 500;
            color: var(--text-secondary);
            transition: var(--transition);
        }

        .brand-card:hover .brand-name-display {
            color: var(--accent);
        }

        /* --- Model Cards --- */
        .model-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .model-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2.5rem 2rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            min-height: 150px;
        }

        .model-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            width: 0;
            background: var(--accent);
            transition: width 0.4s ease;
        }

        .model-card:hover {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-3px);
        }

        .model-card:hover::after {
            width: 100%;
        }

        .model-name-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 400;
            color: #ffffff;
            line-height: 1.2;
        }

        .model-product-count {
            margin-top: 1rem;
            font-size: 0.65rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-secondary);
        }

        /* --- Product Details Modal --- */
        .product-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            padding: 2rem;
        }

        .product-modal.modal-active {
            opacity: 1;
            pointer-events: auto;
        }

        .product-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
        }

        .product-modal-content {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1000px;
            background: #0d0d0d;
            border: 1px solid var(--border);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
            transform: scale(0.95) translateY(20px);
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            max-height: 85vh;
        }

        .product-modal.modal-active .product-modal-content {
            transform: scale(1) translateY(0);
        }

        .modal-image-panel {
            width: 100%;
            height: 100%;
            background: #080808;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            min-height: 400px;
            border-right: 1px solid var(--border);
        }

        .modal-image-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
        }

        .modal-image-placeholder {
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            color: var(--text-secondary);
            text-transform: uppercase;
        }

        .modal-info-panel {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            justify-content: center;
        }

        .modal-category {
            font-size: 0.65rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 0.75rem;
        }

        .modal-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            line-height: 1.15;
            color: #ffffff;
            margin-bottom: 1.5rem;
            font-weight: 400;
        }

        .modal-meta-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .modal-meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .modal-meta-label {
            font-size: 0.55rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--text-secondary);
        }

        .modal-meta-value {
            font-size: 0.8rem;
            font-weight: 500;
            color: #ffffff;
        }

        .modal-description {
            font-size: 0.9rem;
            line-height: 1.8;
            color: var(--text-secondary);
            margin-bottom: 2.5rem;
            font-weight: 300;
        }

        .modal-price-box {
            display: flex;
            align-items: baseline;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .modal-price-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--text-secondary);
        }

        .modal-price-value {
            font-size: 1.4rem;
            font-weight: 400;
            color: #ffffff;
        }

        .modal-actions {
            margin-top: auto;
            display: flex;
            gap: 1rem;
        }

        .modal-close-btn {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            cursor: pointer;
            transition: var(--transition);
            z-index: 15;
        }

        .modal-close-btn:hover {
            background: #ffffff;
            color: #000000;
            transform: rotate(90deg);
        }

        @media (max-width: 992px) {
            .product-modal-content {
                grid-template-columns: 1fr;
                max-height: 90vh;
                max-width: 600px;
            }
            .modal-image-panel {
                min-height: 250px;
                border-right: none;
                border-bottom: 1px solid var(--border);
            }
            .modal-info-panel {
                padding: 2rem;
            }
            .modal-title {
                font-size: 1.8rem;
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

        @php
            $brandLogos = [
                'porsche' => '
                    <svg viewBox="0 0 100 120" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M50 10 L85 20 C85 60 70 95 50 110 C30 95 15 60 15 20 Z" />
                        <path d="M35 45 H65 M35 60 H65 M35 75 H65" stroke-width="1.5" opacity="0.6"/>
                        <text x="50" y="32" font-family="\'Inter\', sans-serif" font-size="8" font-weight="900" letter-spacing="1" fill="currentColor" stroke="none" text-anchor="middle">PORSCHE</text>
                    </svg>
                ',
                'ferrari' => '
                    <svg viewBox="0 0 100 120" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M15 105 C25 105 35 100 35 90 C35 70 15 70 15 50 C15 30 35 25 50 25 C65 25 85 30 85 50 C85 70 65 70 65 90 C65 100 75 105 85 105" />
                        <text x="50" y="65" font-family="\'Cormorant Garamond\', serif" font-size="20" font-style="italic" font-weight="600" fill="currentColor" stroke="none" text-anchor="middle">Ferrari</text>
                    </svg>
                ',
                'lamborghini' => '
                    <svg viewBox="0 0 100 120" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10 10 H90 L85 90 C85 105 50 115 50 115 C50 115 15 105 15 90 Z" />
                        <polygon points="50,25 65,55 35,55" stroke-width="1.5"/>
                        <text x="50" y="80" font-family="\'Inter\', sans-serif" font-size="7" font-weight="900" fill="currentColor" stroke="none" text-anchor="middle" letter-spacing="0.5">LAMBORGHINI</text>
                    </svg>
                ',
                'bmw' => '
                    <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="50" cy="50" r="45" />
                        <circle cx="50" cy="50" r="30" stroke-width="1.5" />
                        <path d="M50 20 V80 M20 50 H80" stroke-width="1.5" />
                        <path d="M50 20 A30 30 0 0 1 80 50 H50 Z M20 50 A30 30 0 0 1 50 20 V50 Z" fill="currentColor" opacity="0.3" stroke="none" />
                        <text x="50" y="14" font-family="\'Inter\', sans-serif" font-size="9" font-weight="800" fill="currentColor" stroke="none" text-anchor="middle">BMW</text>
                    </svg>
                ',
                'bmw-m' => '
                    <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="50" cy="50" r="45" />
                        <circle cx="50" cy="50" r="30" stroke-width="1.5" />
                        <path d="M50 20 V80 M20 50 H80" stroke-width="1.5" />
                        <path d="M50 20 A30 30 0 0 1 80 50 H50 Z M20 50 A30 30 0 0 1 50 20 V50 Z" fill="currentColor" opacity="0.3" stroke="none" />
                        <text x="50" y="14" font-family="\'Inter\', sans-serif" font-size="9" font-weight="800" fill="currentColor" stroke="none" text-anchor="middle">BMW</text>
                    </svg>
                ',
                'audi' => '
                    <svg viewBox="0 0 120 50" fill="none" stroke="currentColor" stroke-width="3">
                        <circle cx="30" cy="25" r="16" />
                        <circle cx="50" cy="25" r="16" />
                        <circle cx="70" cy="25" r="16" />
                        <circle cx="90" cy="25" r="16" />
                    </svg>
                ',
                'audi-sport' => '
                    <svg viewBox="0 0 120 50" fill="none" stroke="currentColor" stroke-width="3">
                        <circle cx="30" cy="25" r="16" />
                        <circle cx="50" cy="25" r="16" />
                        <circle cx="70" cy="25" r="16" />
                        <circle cx="90" cy="25" r="16" />
                    </svg>
                ',
                'mercedes-benz' => '
                    <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="50" cy="50" r="45" />
                        <path d="M50 5 L50 50 L11 72.5 M50 50 L89 72.5" />
                    </svg>
                ',
                'mercedes' => '
                    <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="50" cy="50" r="45" />
                        <path d="M50 5 L50 50 L11 72.5 M50 50 L89 72.5" />
                    </svg>
                ',
                'aston-martin' => '
                    <svg viewBox="0 0 120 50" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M10 25 C30 25 50 20 60 28 C70 20 90 25 110 25 C90 27 70 32 60 28 C50 32 30 27 10 25 Z" />
                        <line x1="60" y1="10" x2="60" y2="40" />
                        <text x="60" y="21" font-family="\'Inter\', sans-serif" font-size="5" font-weight="700" fill="currentColor" stroke="none" text-anchor="middle" letter-spacing="0.5">ASTON MARTIN</text>
                    </svg>
                ',
                'toyota' => '
                    <svg viewBox="0 0 100 70" fill="none" stroke="currentColor" stroke-width="2">
                        <ellipse cx="50" cy="35" rx="45" ry="30" />
                        <ellipse cx="50" cy="35" rx="30" ry="20" />
                        <ellipse cx="50" cy="27" rx="10" ry="18" />
                    </svg>
                ',
                'honda' => '
                    <svg viewBox="0 0 100 80" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M8 5 H92 L87 75 H13 Z" />
                        <path d="M30 15 V65 M70 15 V65 M30 38 H70" stroke-width="3" />
                    </svg>
                ',
                'mitsubishi' => '
                    <svg viewBox="0 0 100 90" fill="none" stroke="currentColor">
                        <path d="M50 5 L65 30 L50 55 L35 30 Z M35 30 L50 55 L20 80 L5 55 Z M65 30 L95 55 L80 80 L50 55 Z" fill="currentColor" stroke="none"/>
                    </svg>
                '
            ];

            // Group active products by brand and model
            $modelsByBrand = [];
            foreach($products as $product) {
                $brandSlug = $product->brand ? $product->brand->slug : 'raveil-custom';
                $brandName = $product->brand ? $product->brand->name : 'Raveil Custom';
                $modelName = $product->car_model ?? 'Universal';
                
                if (!isset($modelsByBrand[$brandSlug])) {
                    $modelsByBrand[$brandSlug] = [
                        'name' => $brandName,
                        'models' => []
                    ];
                }
                
                if (!isset($modelsByBrand[$brandSlug]['models'][$modelName])) {
                    $modelsByBrand[$brandSlug]['models'][$modelName] = 0;
                }
                $modelsByBrand[$brandSlug]['models'][$modelName]++;
            }

            // Prepare products json data to avoid nested blade compilation error
            $productsJsonData = $products->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description ?? 'No description available.',
                    'price' => $p->price,
                    'formatted_price' => $p->price ? 'From Rp ' . number_format($p->price, 0, ',', '.') : 'Price on Request',
                    'image' => $p->image ? \Storage::url($p->image) : null,
                    'brand_id' => $p->brand_id,
                    'brand_name' => $p->brand ? $p->brand->name : 'Raveil Custom',
                    'brand_slug' => $p->brand ? $p->brand->slug : 'raveil-custom',
                    'car_model' => $p->car_model ?? 'Universal',
                    'category_name' => $p->category ? $p->category->name : 'Accessories'
                ];
            });
        @endphp

        <section id="collection" class="collection container">
            <div class="section-header reveal-up">
                <span class="section-subtitle">Exclusivity</span>
                <h2 class="section-title">The Collection</h2>
            </div>

            <!-- Breadcrumbs Navigation -->
            <div id="catalog-breadcrumbs" class="catalog-breadcrumbs reveal-up">
                <span class="current">Collection</span>
            </div>

            <!-- LEVEL 1: Brands Selector -->
            <div id="catalog-brands-level" class="catalog-level active-level reveal-up">
                <div class="brand-grid">
                    @foreach($brands as $index => $brand)
                        <div class="brand-card reveal-scale" data-brand-slug="{{ $brand->slug }}" data-brand-name="{{ $brand->name }}" style="transition-delay: {{ $index * 0.05 }}s">
                            <div class="brand-svg-container">
                                @if($brand->logo)
                                    <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                @elseif(isset($brandLogos[$brand->slug]))
                                    {!! $brandLogos[$brand->slug] !!}
                                @else
                                    <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="50" cy="50" r="45"/>
                                        <text x="50" y="55" font-family="'Cormorant Garamond', serif" font-size="16" fill="currentColor" stroke="none" text-anchor="middle">{{ substr($brand->name, 0, 2) }}</text>
                                    </svg>
                                @endif
                            </div>
                            <span class="brand-name-display">{{ $brand->name }}</span>
                        </div>
                    @endforeach

                    @if($hasUniversal)
                        <div class="brand-card reveal-scale" data-brand-slug="raveil-custom" data-brand-name="Raveil Custom" style="transition-delay: {{ count($brands) * 0.05 }}s">
                            <div class="brand-svg-container">
                                <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="50,5 93,30 93,80 50,105 7,80 7,30" />
                                    <text x="50" y="58" font-family="'Inter', sans-serif" font-size="12" font-weight="300" letter-spacing="4" fill="currentColor" stroke="none" text-anchor="middle">RAVEIL</text>
                                </svg>
                            </div>
                            <span class="brand-name-display">Raveil Custom</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- LEVEL 2: Models Selector -->
            <div id="catalog-models-level" class="catalog-level">
                <div class="model-grid">
                    @foreach($modelsByBrand as $brandSlug => $brandData)
                        @foreach($brandData['models'] as $modelName => $count)
                            <div class="model-card" data-brand-slug="{{ $brandSlug }}" data-model-name="{{ $modelName }}">
                                <span class="model-name-text">{{ $modelName }}</span>
                                <span class="model-product-count">{{ $count }} {{ $count > 1 ? 'Components' : 'Component' }}</span>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <!-- LEVEL 3: Products Grid -->
            <div id="catalog-products-level" class="catalog-level">
                <div class="grid">
                    @forelse($products as $index => $product)
                        @php
                            $brandSlug = $product->brand ? $product->brand->slug : 'raveil-custom';
                            $modelName = $product->car_model ?? 'Universal';
                        @endphp
                        <div class="product-card" 
                             data-brand-slug="{{ $brandSlug }}" 
                             data-car-model="{{ $modelName }}" 
                             data-product-id="{{ $product->id }}">
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
                                    <button class="inquire-btn">
                                        View Details
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: var(--text-secondary);">
                            <p style="font-weight: 300; letter-spacing: 0.1em;">The collection is currently empty</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- LEVEL 4: Product Detail Modal -->
        <div id="product-detail-modal" class="product-modal">
            <div class="product-modal-backdrop"></div>
            <div class="product-modal-content">
                <button class="modal-close-btn" aria-label="Close modal">&times;</button>
                <div class="modal-image-panel">
                    <img id="modal-product-image" src="" alt="">
                    <div id="modal-image-fallback" class="modal-image-placeholder" style="display: none;">No Image</div>
                </div>
                <div class="modal-info-panel">
                    <span id="modal-product-category" class="modal-category">Aerodynamics</span>
                    <h3 id="modal-product-title" class="modal-title">Product Name</h3>
                    
                    <div class="modal-meta-grid">
                        <div class="modal-meta-item">
                            <span class="modal-meta-label">Brand</span>
                            <span id="modal-product-brand" class="modal-meta-value">Porsche</span>
                        </div>
                        <div class="modal-meta-item">
                            <span class="modal-meta-label">Model Fitment</span>
                            <span id="modal-product-model" class="modal-meta-value">911 (992) GT3</span>
                        </div>
                    </div>

                    <div class="modal-description" id="modal-product-description">
                        Description goes here...
                    </div>

                    <div class="modal-price-box">
                        <span class="modal-price-label">Price</span>
                        <span id="modal-product-price" class="modal-price-value">From Rp 85.000.000</span>
                    </div>

                    <div class="modal-actions">
                        <a id="modal-wa-btn" href="" target="_blank" class="btn btn-primary" style="width: 100%;">
                            Inquire via WhatsApp
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-left: 8px; vertical-align: middle;"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

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
                        <li class="reveal-up delay-1"><span>01</span> Carbon Fiber Expertise</li>
                        <li class="reveal-up delay-2"><span>02</span> Precision Manufacturing</li>
                        <li class="reveal-up delay-3"><span>03</span> Bespoke Design</li>
                        <li class="reveal-up delay-4"><span>04</span> Aerodynamic Performance</li>
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

            // Track Catalogue Item clicks - replaced with multi-level catalog logic
            // --- Multi-level Catalog State & Controller ---
            const productsData = @json($productsJsonData);

            const state = {
                brandSlug: null,
                brandName: null,
                modelName: null
            };

            const levels = {
                brands: document.getElementById('catalog-brands-level'),
                models: document.getElementById('catalog-models-level'),
                products: document.getElementById('catalog-products-level')
            };

            const breadcrumbs = document.getElementById('catalog-breadcrumbs');

            function updateBreadcrumbs() {
                if (!breadcrumbs) return;
                
                let html = '<a id="bc-collection">Collection</a>';
                
                if (state.brandSlug) {
                    html += ` <span class="separator">/</span> <a id="bc-brand" data-slug="${state.brandSlug}">${state.brandName}</a>`;
                }
                
                if (state.modelName) {
                    html += ` <span class="separator">/</span> <span class="current">${state.modelName}</span>`;
                }
                
                breadcrumbs.innerHTML = html;
                
                // Wire up breadcrumb links
                const bcCollection = document.getElementById('bc-collection');
                if (bcCollection) {
                    bcCollection.addEventListener('click', () => showBrandsLevel());
                }
                const bcBrand = document.getElementById('bc-brand');
                if (bcBrand) {
                    bcBrand.addEventListener('click', () => showModelsLevel(state.brandSlug, state.brandName));
                }
            }

            function showBrandsLevel() {
                state.brandSlug = null;
                state.brandName = null;
                state.modelName = null;
                
                updateBreadcrumbs();
                
                // Toggle levels
                Object.values(levels).forEach(lvl => {
                    if (lvl) {
                        lvl.classList.remove('active-level');
                    }
                });
                if (levels.brands) levels.brands.classList.add('active-level');
            }

            function showModelsLevel(brandSlug, brandName) {
                state.brandSlug = brandSlug;
                state.brandName = brandName;
                state.modelName = null;
                
                updateBreadcrumbs();
                
                // Filter model cards
                document.querySelectorAll('.model-card').forEach(card => {
                    if (card.getAttribute('data-brand-slug') === brandSlug) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Toggle levels
                Object.values(levels).forEach(lvl => {
                    if (lvl) {
                        lvl.classList.remove('active-level');
                    }
                });
                if (levels.models) levels.models.classList.add('active-level');
            }

            function showProductsLevel(modelName) {
                state.modelName = modelName;
                
                updateBreadcrumbs();
                
                // Filter product cards
                document.querySelectorAll('.product-card[data-brand-slug]').forEach(card => {
                    const matchesBrand = card.getAttribute('data-brand-slug') === state.brandSlug;
                    const matchesModel = card.getAttribute('data-car-model') === modelName;
                    
                    if (matchesBrand && matchesModel) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Toggle levels
                Object.values(levels).forEach(lvl => {
                    if (lvl) {
                        lvl.classList.remove('active-level');
                    }
                });
                if (levels.products) levels.products.classList.add('active-level');
            }

            // --- Wire up brand cards click ---
            document.querySelectorAll('.brand-card').forEach(card => {
                card.addEventListener('click', () => {
                    const slug = card.getAttribute('data-brand-slug');
                    const name = card.getAttribute('data-brand-name');
                    showModelsLevel(slug, name);
                    
                    // Smooth scroll to catalog section header
                    document.getElementById('collection').scrollIntoView({ behavior: 'smooth' });
                });
            });

            // --- Wire up model cards click ---
            document.querySelectorAll('.model-card').forEach(card => {
                card.addEventListener('click', () => {
                    const modelName = card.getAttribute('data-model-name');
                    showProductsLevel(modelName);
                    
                    // Smooth scroll to catalog section header
                    document.getElementById('collection').scrollIntoView({ behavior: 'smooth' });
                });
            });

            // --- Product Modal Operations ---
            const modal = document.getElementById('product-detail-modal');
            
            function openProductModal(productId) {
                const product = productsData.find(p => p.id == productId);
                if (!product || !modal) return;

                // Populate modal
                const modalImage = document.getElementById('modal-product-image');
                const fallbackImage = document.getElementById('modal-image-fallback');
                if (product.image) {
                    modalImage.src = product.image;
                    modalImage.alt = product.name;
                    modalImage.style.display = 'block';
                    if (fallbackImage) fallbackImage.style.display = 'none';
                } else {
                    modalImage.style.display = 'none';
                    if (fallbackImage) fallbackImage.style.display = 'flex';
                }

                document.getElementById('modal-product-category').textContent = product.category_name;
                document.getElementById('modal-product-title').textContent = product.name;
                document.getElementById('modal-product-brand').textContent = product.brand_name;
                document.getElementById('modal-product-model').textContent = product.car_model;
                document.getElementById('modal-product-description').innerHTML = product.description.replace(/\n/g, '<br>');
                document.getElementById('modal-product-price').textContent = product.formatted_price;

                // Track click event via post request
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
                fetch('/track-product-click', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ product_id: product.id })
                });

                // Set up WA link
                const waBase = "https://wa.me/{{ $whatsapp }}";
                const messageText = `Hello Raveil Industries, I am interested in the ${product.name} (${product.category_name}) for my ${product.brand_name} ${product.car_model}.`;
                document.getElementById('modal-wa-btn').href = `${waBase}?text=${encodeURIComponent(messageText)}`;

                modal.classList.add('modal-active');
                document.body.style.overflow = 'hidden'; // Lock body scroll
            }

            function closeProductModal() {
                if (modal) {
                    modal.classList.remove('modal-active');
                }
                document.body.style.overflow = ''; // Restore body scroll
            }

            // Wire up modal elements
            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', (e) => {
                    const productId = card.getAttribute('data-product-id');
                    openProductModal(productId);
                });
            });

            if (modal) {
                const backdrop = modal.querySelector('.product-modal-backdrop');
                const closeBtn = modal.querySelector('.modal-close-btn');
                if (closeBtn) closeBtn.addEventListener('click', closeProductModal);
                if (backdrop) backdrop.addEventListener('click', closeProductModal);
            }
            
            // Close modal on Escape key
            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal && modal.classList.contains('modal-active')) {
                    closeProductModal();
                }
            });

            // Initialize breadcrumbs
            updateBreadcrumbs();
        });
    </script>
</body>
</html>
