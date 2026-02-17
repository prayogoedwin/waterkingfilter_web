<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waterking Filter - Coming Soon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --water-blue: #00b4d8;
            --water-light: #90e0ef;
            --water-dark: #0077b6;
            --royal-red: #c41e3a;
            --gold: #f0c808;
            --white: #ffffff;
            --dark: #0a1628;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--dark) 0%, #0d2137 50%, #0f2847 100%);
            overflow-x: hidden;
            position: relative;
        }

        /* Animated water background */
        .water-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .bubble {
            position: absolute;
            bottom: -100px;
            background: radial-gradient(circle at 30% 30%, rgba(144, 224, 239, 0.4), rgba(0, 180, 216, 0.1));
            border-radius: 50%;
            animation: rise linear infinite;
            opacity: 0;
        }

        @keyframes rise {
            0% {
                opacity: 0;
                transform: translateY(0) scale(0.5);
            }

            10% {
                opacity: 0.6;
            }

            90% {
                opacity: 0.3;
            }

            100% {
                opacity: 0;
                transform: translateY(-100vh) scale(1);
            }
        }

        /* Wave animation at bottom */
        .waves {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            z-index: 1;
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 100%;
            background-repeat: repeat-x;
            transform-origin: center bottom;
        }

        .wave1 {
            background: linear-gradient(180deg, transparent 50%, rgba(0, 180, 216, 0.3) 100%);
            animation: wave 8s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
            z-index: 3;
        }

        .wave2 {
            background: linear-gradient(180deg, transparent 40%, rgba(0, 119, 182, 0.2) 100%);
            animation: wave 10s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
            animation-delay: -2s;
            z-index: 2;
        }

        .wave3 {
            background: linear-gradient(180deg, transparent 30%, rgba(144, 224, 239, 0.15) 100%);
            animation: wave 12s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
            animation-delay: -4s;
            z-index: 1;
        }

        @keyframes wave {
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

        /* Main content */
        .container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            text-align: center;
        }

        /* Logo section */
        .logo-container {
            margin-bottom: 30px;
            animation: fadeInDown 1s ease-out;
        }

        .logo {
            max-width: 280px;
            width: 100%;
            height: auto;
            filter: drop-shadow(0 10px 30px rgba(0, 180, 216, 0.3));
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Coming Soon text */
        .coming-soon {
            margin-bottom: 20px;
            animation: fadeIn 1s ease-out 0.3s both;
        }

        .coming-soon h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(3rem, 12vw, 7rem);
            letter-spacing: 0.15em;
            background: linear-gradient(135deg, var(--water-light) 0%, var(--water-blue) 50%, var(--water-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 60px rgba(0, 180, 216, 0.5);
            position: relative;
        }

        .coming-soon h1::after {
            content: 'COMING SOON';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, var(--water-light) 0%, var(--water-blue) 50%, var(--water-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: blur(30px);
            opacity: 0.5;
            z-index: -1;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Tagline */
        .tagline {
            font-size: clamp(1rem, 3vw, 1.4rem);
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 50px;
            font-weight: 400;
            letter-spacing: 0.1em;
            animation: fadeIn 1s ease-out 0.5s both;
        }

        .tagline span {
            color: var(--water-blue);
            font-weight: 600;
        }

        /* Countdown */
        .countdown {
            display: flex;
            gap: 15px;
            margin-bottom: 50px;
            animation: fadeInUp 1s ease-out 0.7s both;
            flex-wrap: wrap;
            justify-content: center;
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

        .countdown-item {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 180, 216, 0.3);
            border-radius: 16px;
            padding: 20px 25px;
            min-width: 100px;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .countdown-item:hover {
            transform: translateY(-5px);
            border-color: var(--water-blue);
        }

        .countdown-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--water-dark), var(--water-blue), var(--water-light));
        }

        .countdown-number {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            color: var(--white);
            line-height: 1;
        }

        .countdown-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-top: 5px;
        }

        /* Features preview */
        .features {
            display: flex;
            gap: 40px;
            margin-bottom: 50px;
            animation: fadeInUp 1s ease-out 0.9s both;
            flex-wrap: wrap;
            justify-content: center;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--water-blue), var(--water-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Notify form */
        .notify-section {
            animation: fadeInUp 1s ease-out 1.1s both;
            width: 100%;
            max-width: 500px;
        }

        .notify-section p {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .notify-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .notify-form input {
            flex: 1;
            min-width: 250px;
            padding: 16px 24px;
            border: 2px solid rgba(0, 180, 216, 0.3);
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            color: var(--white);
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            outline: none;
            transition: border-color 0.3s ease, background 0.3s ease;
        }

        .notify-form input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .notify-form input:focus {
            border-color: var(--water-blue);
            background: rgba(0, 180, 216, 0.1);
        }

        .notify-form button {
            padding: 16px 35px;
            background: linear-gradient(135deg, var(--water-blue), var(--water-dark));
            border: none;
            border-radius: 50px;
            color: var(--white);
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .notify-form button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 180, 216, 0.4);
        }

        /* Social links */
        .social-links {
            display: flex;
            gap: 20px;
            margin-top: 50px;
            animation: fadeIn 1s ease-out 1.3s both;
        }

        .social-link {
            width: 50px;
            height: 50px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            border-color: var(--water-blue);
            color: var(--water-blue);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 180, 216, 0.3);
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.8rem;
            z-index: 20;
            animation: fadeIn 1s ease-out 1.5s both;
        }

        /* Droplet decorations */
        .droplet-decoration {
            position: fixed;
            width: 200px;
            height: 200px;
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            background: radial-gradient(circle at 30% 30%, rgba(144, 224, 239, 0.1), transparent);
            pointer-events: none;
            z-index: 0;
        }

        .droplet-1 {
            top: 10%;
            right: 5%;
            animation: dropletFloat 8s ease-in-out infinite;
        }

        .droplet-2 {
            bottom: 20%;
            left: 5%;
            width: 150px;
            height: 150px;
            animation: dropletFloat 10s ease-in-out infinite reverse;
        }

        @keyframes dropletFloat {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-30px) rotate(10deg);
            }
        }

        /* Water ripple effect on click */
        .ripple {
            position: fixed;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 180, 216, 0.3), transparent);
            transform: scale(0);
            animation: rippleEffect 1s ease-out forwards;
            pointer-events: none;
            z-index: 5;
        }

        @keyframes rippleEffect {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Responsive */
        @media (max-width: 600px) {
            .countdown-item {
                min-width: 70px;
                padding: 15px;
            }

            .countdown-number {
                font-size: 2.2rem;
            }

            .features {
                gap: 25px;
            }

            .feature {
                font-size: 0.85rem;
            }

            .notify-form input {
                min-width: 100%;
            }

            .notify-form button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Water background effects -->
    <div class="water-bg" id="waterBg"></div>

    <!-- Decorative droplets -->
    <div class="droplet-decoration droplet-1"></div>
    <div class="droplet-decoration droplet-2"></div>

    <!-- Wave animation -->
    <div class="waves">
        <div class="wave wave1"></div>
        <div class="wave wave2"></div>
        <div class="wave wave3"></div>
    </div>

    <!-- Main content -->
    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('img') }}/waterking-filter.png" alt="Waterking Filter Logo" class="logo">
        </div>

        <div class="coming-soon">
            <h1>COMING SOON</h1>
        </div>

        <p class="tagline">Solusi <span>Filter Air</span> Terbaik untuk Keluarga Indonesia</p>

        <!-- Countdown -->
        <!--<div class="countdown">-->
        <!--    <div class="countdown-item">-->
        <!--        <div class="countdown-number" id="days">00</div>-->
        <!--        <div class="countdown-label">Hari</div>-->
        <!--    </div>-->
        <!--    <div class="countdown-item">-->
        <!--        <div class="countdown-number" id="hours">00</div>-->
        <!--        <div class="countdown-label">Jam</div>-->
        <!--    </div>-->
        <!--    <div class="countdown-item">-->
        <!--        <div class="countdown-number" id="minutes">00</div>-->
        <!--        <div class="countdown-label">Menit</div>-->
        <!--    </div>-->
        <!--    <div class="countdown-item">-->
        <!--        <div class="countdown-number" id="seconds">00</div>-->
        <!--        <div class="countdown-label">Detik</div>-->
        <!--    </div>-->
        <!--</div>-->

        <!-- Features -->
        <div class="features">
            <div class="feature">
                <div class="feature-icon">💧</div>
                <span>Filter Berkualitas</span>
            </div>
            <div class="feature">
                <div class="feature-icon">🏠</div>
                <span>Untuk Rumah Tangga</span>
            </div>
            <div class="feature">
                <div class="feature-icon">✨</div>
                <span>Air Bersih & Sehat</span>
            </div>
        </div>

        <!-- Notify form -->
        <div class="notify-section" hidden>
            <p>Dapatkan notifikasi saat website kami launching!</p>
            <form class="notify-form" onsubmit="handleSubmit(event)">
                <input type="email" placeholder="Masukkan email Anda" required>
                <button type="submit">Notify Me</button>
            </form>
        </div>

        <!-- Social links -->
        <!--<div class="social-links" hidden>-->
        <!--    <a href="#" class="social-link" title="WhatsApp">-->
        <!--        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">-->
        <!--            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>-->
        <!--        </svg>-->
        <!--    </a>-->
        <!--    <a href="#" class="social-link" title="Instagram">-->
        <!--        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">-->
        <!--            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>-->
        <!--        </svg>-->
        <!--    </a>-->
        <!--    <a href="#" class="social-link" title="Facebook">-->
        <!--        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">-->
        <!--            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>-->
        <!--        </svg>-->
        <!--    </a>-->
        <!--</div>-->
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2026 Waterking Filter. All Rights Reserved. By GMT
    </div>

    <script>
        // Create bubbles
        function createBubbles() {
            const waterBg = document.getElementById('waterBg');
            const bubbleCount = 20;

            for (let i = 0; i < bubbleCount; i++) {
                const bubble = document.createElement('div');
                bubble.className = 'bubble';

                const size = Math.random() * 60 + 20;
                const left = Math.random() * 100;
                const duration = Math.random() * 10 + 8;
                const delay = Math.random() * 10;

                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
                bubble.style.left = `${left}%`;
                bubble.style.animationDuration = `${duration}s`;
                bubble.style.animationDelay = `${delay}s`;

                waterBg.appendChild(bubble);
            }
        }

        // Countdown timer
        function updateCountdown() {
            // Set launch date (30 days from now for demo)
            const launchDate = new Date();
            launchDate.setDate(launchDate.getDate() + 70);

            const now = new Date();
            const diff = launchDate - now;

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }

        // Handle form submit
        function handleSubmit(e) {
            e.preventDefault();
            const input = e.target.querySelector('input');
            const email = input.value;

            // Show success message
            alert(`Terima kasih! Kami akan mengirim notifikasi ke ${email} saat website launching.`);
            input.value = '';
        }

        // Ripple effect on click
        document.addEventListener('click', (e) => {
            const ripple = document.createElement('div');
            ripple.className = 'ripple';
            ripple.style.left = `${e.clientX - 50}px`;
            ripple.style.top = `${e.clientY - 50}px`;
            ripple.style.width = '100px';
            ripple.style.height = '100px';
            document.body.appendChild(ripple);

            setTimeout(() => ripple.remove(), 1000);
        });

        // Initialize
        createBubbles();
        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>

</html>
