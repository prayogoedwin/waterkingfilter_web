<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waterking Filter - Solusi Filter Air Terbaik</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --water-blue: #00b4d8;
            --water-light: #90e0ef;
            --water-dark: #0077b6;
            --water-deeper: #023e8a;
            --royal-red: #c41e3a;
            --gold: #f0c808;
            --white: #ffffff;
            --dark: #0a1628;
            --dark-blue: #0d2137;
            --whatsapp-green: #25D366;
            --gray-light: #f8f9fa;
            --gray: #6c757d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--white);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            background: transparent;
        }

        .navbar.scrolled {
            background: rgba(10, 22, 40, 0.95);
            backdrop-filter: blur(10px);
            padding: 10px 5%;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.2);
        }

        .nav-logo img {
            height: 50px;
            transition: all 0.3s ease;
        }

        .navbar.scrolled .nav-logo img {
            height: 40px;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 40px;
        }

        .nav-menu a {
            text-decoration: none;
            color: var(--white);
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
            position: relative;
            padding: 5px 0;
            transition: color 0.3s ease;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--water-blue);
            transition: width 0.3s ease;
        }

        .nav-menu a:hover::after,
        .nav-menu a.active::after {
            width: 100%;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            color: var(--water-blue);
        }

        .nav-cta {
            background: linear-gradient(135deg, var(--water-blue), var(--water-dark));
            color: var(--white);
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 180, 216, 0.4);
        }

        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }

        .nav-toggle span {
            width: 25px;
            height: 3px;
            background: var(--white);
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        /* ===== HERO SECTION ===== */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-blue) 50%, var(--water-deeper) 100%);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .hero-bubble {
            position: absolute;
            background: radial-gradient(circle at 30% 30%, rgba(144, 224, 239, 0.3), rgba(0, 180, 216, 0.05));
            border-radius: 50%;
            animation: floatBubble linear infinite;
        }

        @keyframes floatBubble {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }

            10% {
                opacity: 0.6;
            }

            90% {
                opacity: 0.2;
            }

            100% {
                transform: translateY(-100px) scale(1);
                opacity: 0;
            }
        }

        .hero-waves {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px;
            z-index: 1;
        }

        .hero-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 100%;
            animation: waveMove 10s linear infinite;
        }

        .hero-wave:nth-child(1) {
            background: linear-gradient(180deg, transparent 60%, rgba(0, 180, 216, 0.2) 100%);
            animation-duration: 8s;
        }

        .hero-wave:nth-child(2) {
            background: linear-gradient(180deg, transparent 50%, rgba(0, 119, 182, 0.15) 100%);
            animation-duration: 12s;
            animation-delay: -3s;
        }

        @keyframes waveMove {
            0% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(-25%);
            }

            100% {
                transform: translateX(0);
            }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 120px 5% 80px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero-text h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(3rem, 6vw, 5rem);
            color: var(--white);
            letter-spacing: 0.05em;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .hero-text h1 span {
            background: linear-gradient(135deg, var(--water-light), var(--water-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-text p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 35px;
            max-width: 500px;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--water-blue), var(--water-dark));
            color: var(--white);
            padding: 16px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 180, 216, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--white);
            padding: 16px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-secondary:hover {
            border-color: var(--water-blue);
            background: rgba(0, 180, 216, 0.1);
            transform: translateY(-3px);
        }

        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-image-wrapper {
            position: relative;
            animation: heroFloat 5s ease-in-out infinite;
        }

        @keyframes heroFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .hero-droplet {
            width: 350px;
            height: 420px;
            background: linear-gradient(180deg, rgba(144, 224, 239, 0.3) 0%, rgba(0, 180, 216, 0.5) 50%, rgba(0, 119, 182, 0.7) 100%);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow:
                0 30px 60px rgba(0, 180, 216, 0.3),
                inset 0 -30px 60px rgba(0, 119, 182, 0.3),
                inset 0 30px 60px rgba(144, 224, 239, 0.2);
        }

        .hero-droplet::before {
            content: '';
            position: absolute;
            top: 15%;
            left: 20%;
            width: 30%;
            height: 20%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), transparent);
            border-radius: 50%;
            filter: blur(10px);
        }

        .hero-droplet img {
            width: 200px;
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.3));
        }

        .hero-stats {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 30px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px 40px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--water-blue);
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* ===== SERVICES SECTION ===== */
        .services {
            padding: 100px 5%;
            background: var(--gray-light);
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 60px;
        }

        .section-subtitle {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1rem;
            color: var(--water-blue);
            letter-spacing: 0.2em;
            margin-bottom: 10px;
        }

        .section-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            color: var(--dark);
            letter-spacing: 0.05em;
            margin-bottom: 15px;
        }

        .section-desc {
            color: var(--gray);
            line-height: 1.8;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .service-card {
            background: var(--white);
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--water-dark), var(--water-blue), var(--water-light));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 180, 216, 0.15);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(0, 180, 216, 0.1), rgba(0, 119, 182, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.5rem;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            background: linear-gradient(135deg, var(--water-blue), var(--water-dark));
            transform: scale(1.1);
        }

        .service-card h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.4rem;
            color: var(--dark);
            letter-spacing: 0.05em;
            margin-bottom: 15px;
        }

        .service-card p {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.7;
        }

        /* ===== ABOUT / PROFILE SECTION ===== */
        .about {
            padding: 100px 5%;
            background: var(--white);
        }

        .about-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .about-image {
            position: relative;
        }

        .about-img-main {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
        }

        .about-badge {
            position: absolute;
            bottom: -30px;
            right: -30px;
            background: linear-gradient(135deg, var(--water-blue), var(--water-dark));
            color: var(--white);
            padding: 25px 35px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0, 180, 216, 0.3);
        }

        .about-badge-number {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            line-height: 1;
        }

        .about-badge-text {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .about-text h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2rem, 4vw, 2.8rem);
            color: var(--dark);
            letter-spacing: 0.05em;
            margin-bottom: 25px;
        }

        .about-text h2 span {
            color: var(--water-blue);
        }

        .about-text p {
            color: var(--gray);
            line-height: 1.9;
            margin-bottom: 20px;
        }

        .about-features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .about-feature {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .about-feature-icon {
            width: 45px;
            height: 45px;
            background: rgba(0, 180, 216, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--water-blue);
            font-size: 1.2rem;
        }

        .about-feature span {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--dark);
        }

        /* ===== WHY CHOOSE US ===== */
        .why-us {
            padding: 100px 5%;
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-blue) 100%);
            position: relative;
            overflow: hidden;
        }

        .why-us::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2300b4d8' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .why-us .section-header {
            position: relative;
            z-index: 1;
        }

        .why-us .section-title {
            color: var(--white);
        }

        .why-us .section-desc {
            color: rgba(255, 255, 255, 0.7);
        }

        .why-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .why-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 180, 216, 0.2);
            padding: 35px 30px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .why-card:hover {
            background: rgba(0, 180, 216, 0.1);
            border-color: var(--water-blue);
            transform: translateY(-10px);
        }

        .why-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .why-card h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.3rem;
            color: var(--white);
            letter-spacing: 0.05em;
            margin-bottom: 12px;
        }

        .why-card p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            line-height: 1.7;
        }

        /* ===== LOCATIONS SECTION ===== */
        .locations {
            padding: 100px 5%;
            background: var(--gray-light);
        }

        .locations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .location-card {
            background: var(--white);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .location-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--water-dark), var(--water-blue), var(--water-light));
        }

        .location-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 180, 216, 0.15);
        }

        .location-card.coming-soon::before {
            background: linear-gradient(90deg, var(--gold), #ffd700);
        }

        .location-status {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 20px;
        }

        .status-open {
            background: rgba(37, 211, 102, 0.15);
            color: #1a9850;
        }

        .status-soon {
            background: rgba(240, 200, 8, 0.15);
            color: #b8860b;
        }

        .location-card h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--dark);
            letter-spacing: 0.05em;
            margin-bottom: 15px;
        }

        .location-card address {
            font-style: normal;
            color: var(--gray);
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .location-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--water-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .location-link:hover {
            gap: 15px;
            color: var(--water-dark);
        }

        .coming-cities {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .city-tag {
            background: rgba(240, 200, 8, 0.1);
            border: 1px solid rgba(240, 200, 8, 0.3);
            padding: 10px 18px;
            border-radius: 10px;
            color: #b8860b;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
        }

        /* ===== CONTACT SECTION ===== */
        .contact {
            padding: 100px 5%;
            background: var(--white);
        }

        .contact-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
        }

        .contact-info h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2rem, 4vw, 2.5rem);
            color: var(--dark);
            letter-spacing: 0.05em;
            margin-bottom: 20px;
        }

        .contact-info>p {
            color: var(--gray);
            line-height: 1.8;
            margin-bottom: 40px;
        }

        .contact-item {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(0, 180, 216, 0.1), rgba(0, 119, 182, 0.1));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .contact-detail h4 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .contact-detail p,
        .contact-detail a {
            color: var(--gray);
            text-decoration: none;
            line-height: 1.6;
        }

        .contact-detail a:hover {
            color: var(--water-blue);
        }

        .contact-form-wrapper {
            background: var(--gray-light);
            padding: 40px;
            border-radius: 20px;
        }

        .contact-form h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.5rem;
            color: var(--dark);
            letter-spacing: 0.05em;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--water-blue);
            box-shadow: 0 0 0 4px rgba(0, 180, 216, 0.1);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--water-blue), var(--water-dark));
            color: var(--white);
            padding: 18px;
            border: none;
            border-radius: 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 180, 216, 0.3);
        }

        /* ===== CTA SECTION ===== */
        .cta-section {
            padding: 80px 5%;
            background: linear-gradient(135deg, var(--water-blue), var(--water-dark));
            text-align: center;
        }

        .cta-section h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            color: var(--white);
            letter-spacing: 0.05em;
            margin-bottom: 15px;
        }

        .cta-section p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-whatsapp {
            background: var(--whatsapp-green);
            color: var(--white);
            padding: 18px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 30px rgba(37, 211, 102, 0.3);
        }

        .btn-whatsapp:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 50px rgba(37, 211, 102, 0.4);
        }

        .btn-whatsapp svg {
            width: 24px;
            height: 24px;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--dark);
            padding: 60px 5% 30px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 50px;
        }

        .footer-brand img {
            height: 60px;
            margin-bottom: 20px;
        }

        .footer-brand p {
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.8;
            font-size: 0.9rem;
        }

        .footer-column h4 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.2rem;
            color: var(--white);
            letter-spacing: 0.1em;
            margin-bottom: 25px;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 12px;
        }

        .footer-column ul a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .footer-column ul a:hover {
            color: var(--water-blue);
            padding-left: 5px;
        }

        .footer-social {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .footer-social a {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            transition: all 0.3s ease;
        }

        .footer-social a:hover {
            background: var(--water-blue);
            transform: translateY(-5px);
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 50px auto 0;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.85rem;
        }

        /* ===== FLOATING WHATSAPP ===== */
        .floating-wa {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 999;
            animation: pulse 2s ease-in-out infinite;
        }

        .floating-wa a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 65px;
            height: 65px;
            background: var(--whatsapp-green);
            border-radius: 50%;
            box-shadow: 0 5px 25px rgba(37, 211, 102, 0.5);
            transition: all 0.3s ease;
        }

        .floating-wa a:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 40px rgba(37, 211, 102, 0.6);
        }

        .floating-wa svg {
            width: 35px;
            height: 35px;
            fill: white;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-text p {
                max-width: 100%;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-droplet {
                width: 280px;
                height: 340px;
            }

            .about-content {
                grid-template-columns: 1fr;
                gap: 50px;
            }

            .about-badge {
                right: 20px;
            }

            .contact-content {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .nav-menu {
                position: fixed;
                top: 0;
                right: -100%;
                width: 70%;
                height: 100vh;
                background: var(--dark);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 30px;
                transition: right 0.3s ease;
            }

            .nav-menu.active {
                right: 0;
            }

            .nav-toggle {
                display: flex;
                z-index: 1001;
            }

            .nav-toggle.active span:nth-child(1) {
                transform: rotate(45deg) translate(5px, 5px);
            }

            .nav-toggle.active span:nth-child(2) {
                opacity: 0;
            }

            .nav-toggle.active span:nth-child(3) {
                transform: rotate(-45deg) translate(7px, -6px);
            }

            .nav-cta {
                display: none;
            }

            .hero-stats {
                flex-direction: column;
                gap: 15px;
                padding: 20px 30px;
            }

            .about-features {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }

            .footer-social {
                justify-content: center;
            }

            .floating-wa {
                bottom: 20px;
                right: 20px;
            }

            .floating-wa a {
                width: 55px;
                height: 55px;
            }
        }

        @media (max-width: 480px) {
            .hero-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            .hero-droplet {
                width: 220px;
                height: 270px;
            }

            .hero-droplet img {
                width: 140px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <a href="#" class="nav-logo">
            <img src="{{ asset('img') }}/waterking-filter.png" alt="Waterking Filter">
        </a>
        <ul class="nav-menu" id="navMenu">
            <li><a href="#home" class="active">Home</a></li>
            <li><a href="#layanan">Layanan</a></li>
            <li><a href="#profile">Profile</a></li>
            <li><a href="#lokasi">Lokasi</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <a href="https://wa.me/6281362033888?text=Halo%20Waterking%20Filter,%20saya%20ingin%20bertanya%20tentang%20produk%20filter%20air"
            class="nav-cta" target="_blank">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
            </svg>
            Hubungi Kami
        </a>
        <div class="nav-toggle" id="navToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-bg" id="heroBg"></div>
        <div class="hero-waves">
            <div class="hero-wave"></div>
            <div class="hero-wave"></div>
        </div>
        <div class="hero-content">
            <div class="hero-text">
                <h1>SOLUSI <span>FILTER AIR</span> TERBAIK UNTUK INDONESIA</h1>
                <p>Nikmati air bersih dan sehat untuk keluarga Anda. Waterking Filter menyediakan sistem filtrasi air
                    berkualitas tinggi untuk rumah tangga, bisnis, hingga industri.</p>
                <div class="hero-buttons">
                    <a href="https://wa.me/6281362033888?text=Halo%20Waterking%20Filter,%20saya%20ingin%20konsultasi%20tentang%20filter%20air"
                        class="btn-primary" target="_blank">
                        Konsultasi Gratis
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                    <a href="#layanan" class="btn-secondary">
                        Lihat Layanan
                    </a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-image-wrapper">
                    <div class="hero-droplet">
                        <img src="{{ asset('img') }}/waterking-filter.png" alt="Waterking Filter">
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Pelanggan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">99%</div>
                            <div class="stat-label">Kepuasan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="layanan">
        <div class="section-header">
            <div class="section-subtitle">LAYANAN KAMI</div>
            <h2 class="section-title">MELAYANI BERBAGAI KEBUTUHAN FILTER AIR</h2>
            <p class="section-desc">Kami menyediakan solusi filter air untuk berbagai kebutuhan, dari skala rumah tangga
                hingga industri besar.</p>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">🏠</div>
                <h3>RUMAH TANGGA</h3>
                <p>Filter air berkualitas untuk kebutuhan sehari-hari keluarga Anda. Air bersih untuk minum, masak, dan
                    mandi.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">☕</div>
                <h3>RESTO & CAFE</h3>
                <p>Solusi filter air untuk bisnis kuliner Anda. Pastikan kualitas air terbaik untuk hidangan dan minuman
                    pelanggan.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">🏨</div>
                <h3>HOTEL & PENGINAPAN</h3>
                <p>Sistem filtrasi air skala besar untuk hotel dan penginapan. Kepuasan tamu dimulai dari kualitas air.
                </p>
            </div>
            <div class="service-card">
                <div class="service-icon">🏭</div>
                <h3>INDUSTRI & PABRIK</h3>
                <p>Filter air industrial dengan kapasitas besar. Mendukung proses produksi dengan air berkualitas
                    tinggi.</p>
            </div>
        </div>
    </section>

    <!-- About / Profile Section -->
    <section class="about" id="profile">
        <div class="about-content">
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1581244277943-fe4a9c777189?w=600&h=500&fit=crop"
                    alt="Water Filter System" class="about-img-main">
                <div class="about-badge">
                    <div class="about-badge-number">10+</div>
                    <div class="about-badge-text">Tahun Pengalaman</div>
                </div>
            </div>
            <div class="about-text">
                <h2>TENTANG <span>WATERKING FILTER</span></h2>
                <p>Waterking Filter adalah perusahaan yang bergerak di bidang penyediaan sistem filtrasi air berkualitas
                    tinggi. Kami hadir untuk memberikan solusi air bersih dan sehat bagi masyarakat Indonesia.</p>
                <p>Dengan pengalaman lebih dari 10 tahun di industri ini, kami telah melayani ratusan pelanggan dari
                    berbagai sektor, mulai dari rumah tangga hingga industri besar. Komitmen kami adalah memberikan
                    produk berkualitas dengan pelayanan terbaik.</p>
                <div class="about-features">
                    <div class="about-feature">
                        <div class="about-feature-icon">✓</div>
                        <span>Produk Berkualitas</span>
                    </div>
                    <div class="about-feature">
                        <div class="about-feature-icon">✓</div>
                        <span>Garansi Resmi</span>
                    </div>
                    <div class="about-feature">
                        <div class="about-feature-icon">✓</div>
                        <span>Instalasi Profesional</span>
                    </div>
                    <div class="about-feature">
                        <div class="about-feature-icon">✓</div>
                        <span>Layanan Purna Jual</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-us">
        <div class="section-header">
            <div class="section-subtitle">MENGAPA MEMILIH KAMI</div>
            <h2 class="section-title">KEUNGGULAN WATERKING FILTER</h2>
            <p class="section-desc">Kami berkomitmen memberikan yang terbaik untuk kebutuhan filter air Anda.</p>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon">💎</div>
                <h3>KUALITAS TERJAMIN</h3>
                <p>Produk filter air dengan standar kualitas tinggi dan material terbaik.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">💰</div>
                <h3>HARGA KOMPETITIF</h3>
                <p>Harga terjangkau dengan kualitas premium untuk semua kalangan.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">🔧</div>
                <h3>INSTALASI MUDAH</h3>
                <p>Tim teknisi profesional siap membantu proses instalasi di lokasi Anda.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">🛡️</div>
                <h3>GARANSI PRODUK</h3>
                <p>Garansi resmi untuk setiap produk yang kami jual.</p>
            </div>
        </div>
    </section>

    <!-- Locations Section -->
    <section class="locations" id="lokasi">
        <div class="section-header">
            <div class="section-subtitle">LOKASI</div>
            <h2 class="section-title">TEMUKAN KAMI</h2>
            <p class="section-desc">Kunjungi outlet kami atau hubungi untuk konsultasi.</p>
        </div>
        <div class="locations-grid">
            <div class="location-card">
                <span class="location-status status-open">✓ Buka Sekarang</span>
                <h3>MEDAN</h3>
                <address>
                    Jl. Letda Sujono 142<br>
                    (Simpang Jl. Pancing)<br>
                    Medan, Sumatera Utara
                </address>
                <a href="https://maps.app.goo.gl/iToxhzswvuFrG5C78" target="_blank" class="location-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                    </svg>
                    Lihat di Google Maps →
                </a>
            </div>
            <div class="location-card coming-soon">
                <span class="location-status status-soon">Segera Hadir</span>
                <h3>EKSPANSI</h3>
                <p style="color: var(--gray); margin-bottom: 20px;">Kami akan segera hadir di:</p>
                <div class="coming-cities">
                    <span class="city-tag">JAKARTA</span>
                    <span class="city-tag">BALIKPAPAN</span>
                    <span class="city-tag">BANDA ACEH</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="contact-content">
            <div class="contact-info">
                <h2>HUBUNGI KAMI</h2>
                <p>Punya pertanyaan tentang produk atau layanan kami? Jangan ragu untuk menghubungi kami. Tim kami siap
                    membantu Anda.</p>

                <div class="contact-item">
                    <div class="contact-icon">📞</div>
                    <div class="contact-detail">
                        <h4>Telepon / WhatsApp</h4>
                        <a href="tel:+6281362033888">0813 6203 3888</a>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">📍</div>
                    <div class="contact-detail">
                        <h4>Alamat</h4>
                        <p>Jl. Letda Sujono 142<br>(Simpang Jl. Pancing), Medan</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">🕐</div>
                    <div class="contact-detail">
                        <h4>Jam Operasional</h4>
                        <p>Senin - Sabtu: 08.00 - 17.00 WIB<br>Minggu: Tutup</p>
                    </div>
                </div>
            </div>

            <div class="contact-form-wrapper">
                <form class="contact-form" id="contactForm">
                    <h3>KIRIM PESAN</h3>
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama Anda"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="phone">No. WhatsApp</label>
                        <input type="tel" id="phone" name="phone" placeholder="Contoh: 081234567890"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="service">Kebutuhan</label>
                        <select id="service" name="service" required>
                            <option value="">Pilih kebutuhan Anda</option>
                            <option value="Rumah Tangga">Rumah Tangga</option>
                            <option value="Resto / Cafe">Resto / Cafe</option>
                            <option value="Hotel">Hotel</option>
                            <option value="Industri / Pabrik">Industri / Pabrik</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">Pesan</label>
                        <textarea id="message" name="message" placeholder="Tulis pesan atau pertanyaan Anda..." required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Kirim Pesan via WhatsApp</button>
                </form>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>SIAP MENDAPATKAN AIR BERSIH?</h2>
        <p>Konsultasikan kebutuhan filter air Anda sekarang. Gratis!</p>
        <div class="cta-buttons">
            <a href="https://wa.me/6281362033888?text=Halo%20Waterking%20Filter,%20saya%20ingin%20konsultasi%20tentang%20filter%20air"
                class="btn-whatsapp" target="_blank">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
                Chat via WhatsApp
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-brand">
                <img src="{{ asset('img') }}/waterking-filter.png" alt="Waterking Filter">
                <p>Solusi filter air terbaik untuk keluarga Indonesia. Menyediakan produk berkualitas dengan pelayanan
                    profesional.</p>
                <div class="footer-social">
                    <a href="#" title="Facebook">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="#" title="Instagram">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="footer-column">
                <h4>MENU</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#layanan">Layanan</a></li>
                    <li><a href="#profile">Profile</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>LAYANAN</h4>
                <ul>
                    <li><a href="#layanan">Rumah Tangga</a></li>
                    <li><a href="#layanan">Resto & Cafe</a></li>
                    <li><a href="#layanan">Hotel</a></li>
                    <li><a href="#layanan">Industri</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>KONTAK</h4>
                <ul>
                    <li><a href="tel:+6281362033888">0813 6203 3888</a></li>
                    <li><a href="https://maps.app.goo.gl/iToxhzswvuFrG5C78" target="_blank">Jl. Letda Sujono 142,
                            Medan</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 Waterking Filter. All Rights Reserved. By GMT
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <div class="floating-wa">
        <a href="https://wa.me/6281362033888?text=Halo%20Waterking%20Filter,%20saya%20ingin%20bertanya%20tentang%20produk%20filter%20air"
            target="_blank" title="Chat via WhatsApp">
            <svg viewBox="0 0 24 24">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
            </svg>
        </a>
    </div>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');

        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close menu when clicking a link
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });

        // Active nav link on scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-menu a');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                if (scrollY >= sectionTop) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });

        // Create hero bubbles
        function createHeroBubbles() {
            const heroBg = document.getElementById('heroBg');
            for (let i = 0; i < 15; i++) {
                const bubble = document.createElement('div');
                bubble.className = 'hero-bubble';
                const size = Math.random() * 80 + 30;
                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
                bubble.style.left = `${Math.random() * 100}%`;
                bubble.style.animationDuration = `${Math.random() * 10 + 10}s`;
                bubble.style.animationDelay = `${Math.random() * 10}s`;
                heroBg.appendChild(bubble);
            }
        }
        createHeroBubbles();

        // Contact form - redirect to WhatsApp
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const service = document.getElementById('service').value;
            const message = document.getElementById('message').value;

            const waMessage =
                `Halo Waterking Filter!%0A%0ANama: ${name}%0ANo. HP: ${phone}%0AKebutuhan: ${service}%0A%0APesan:%0A${message}`;

            window.open(`https://wa.me/6281362033888?text=${waMessage}`, '_blank');
        });
    </script>
</body>

</html>
