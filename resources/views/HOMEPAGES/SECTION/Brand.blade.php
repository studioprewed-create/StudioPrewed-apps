<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grid partnership · brand & gradasi coklat</title>
    <style>
        /* ROOT persis dari yang diberikan — dominan hitam & gradasi coklat (teal-2, cream, accent) */
        :root {
            --black: #000000;
            --white: #ffffff;
            --dark: #0a0a0a;
            --dark-gray: #1a1a1a;
            --medium-gray: #2a2a2a;
            --light-gray: #e5e5e5;
            --off-white: #f8f8f8;
            --cream: #f5f5dc;
            --accent: #888888;
            --accent-2: #00c2ff;
            --transition: all 0.6s cubic-bezier(0.65, 0, 0.35, 1);
            --primary: #cbcbcb;
            --primary-dark: #ffffff;
            --primary-light: #a8a59c;
            --secondary: #959595;
            --dark-bg: #121212;
            --dark-surface: #1e1e1e;
            --dark-card: #2a2a2a;
            --text: #e0e0e0;
            --text-light: #b0b0b0;
            --text-lighter: #888888;
            --border: #3a3a3a;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            --radius: 10px;
            --bg:#0a0d0e;            
            --panel:#000000;         
            --card:#11181d;          
            --text:#eaf6f6;          
            --muted:#9fb3b5;         
            --line:#1b2a2f;          
            --teal:#f1991694;        
            --teal-2:rgb(212, 172, 90);        
            --shadow:0 18px 60px rgba(0,0,0,.4);
            --radius:18px;
            --maxw:100%;
            --dark-bg: #0f1115;
            --dark-border: #2a3242;
            --text-primary: #e8edf7;
            --text-secondary: #9fb0cc;
            --accent-primary: #7c9bff;
            --accent-success: #22c55e;
            --accent-warning: #f59e0b;
            --accent-danger: #ef4444;
        }

        /* dominan hitam + gradasi coklat dari --teal-2, --cream, sentuhan coklat hangat */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg, #0a0d0e);
            background-image: radial-gradient(circle at 10% 20%, rgba(212, 172, 90, 0.05) 0%, transparent 35%),
                              radial-gradient(circle at 90% 70%, rgba(245, 245, 220, 0.03) 0%, transparent 40%),
                              linear-gradient(145deg, #050707 0%, #0f1417 100%);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: var(--text, #eaf6f6);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 3rem 1.5rem;
        }

        .partnership-section {
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
        }

        /* subtle headline dengan nuansa coklat */
        .section-headline {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-headline h2 {
            font-size: 2.6rem;
            font-weight: 500;
            letter-spacing: -0.02em;
            background: linear-gradient(to right, #f0ead8, var(--teal-2, #d4ac5a), #e6d6b2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 2px 5px rgba(0,0,0,0.7);
        }

        .section-headline p {
            color: var(--muted, #9fb3b5);
            font-size: 1.2rem;
            margin-top: 0.5rem;
            border-bottom: 1px solid rgba(212, 172, 90, 0.2);
            display: inline-block;
            padding-bottom: 0.5rem;
        }

        /* 3-COLUMN GRID — SEMUA KOTAK SAMA RATA, PROPORSIONAL */
        .brand-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.8rem;
        }

        /* CARD / KOTAK — dominan hitam, sentuhan gradasi coklat */
        .brand-card {
            background: var(--panel, #000000);
            background: linear-gradient(145deg, #0c0e10, #030404);
            border: 1px solid var(--line, #1b2a2f);
            border-image: linear-gradient(145deg, rgba(212, 172, 90, 0.4), rgba(100, 80, 40, 0.1)) 1;
            border-radius: var(--radius, 18px);
            padding: 2.2rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: var(--transition, all 0.6s cubic-bezier(0.65, 0, 0.35, 1));
            box-shadow: 0 18px 30px -10px rgba(0,0,0,0.7), 0 0 0 1px rgba(212, 172, 90, 0.1) inset;
            position: relative;
            backdrop-filter: blur(4px);
            height: 100%;
            width: 100%;
        }

        /* efek gradasi coklat di border & hover */
        .brand-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: radial-gradient(circle at 70% 20%, rgba(212,172,90,0.3), transparent 70%);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            opacity: 0.6;
        }

        .brand-card:hover {
            transform: translateY(-8px);
            border-color: rgba(212, 172, 90, 0.6);
            box-shadow: 0 25px 40px -12px rgba(0,0,0,0.9), 0 0 0 1px rgba(245, 245, 220, 0.2) inset;
            background: linear-gradient(145deg, #0f1318, #07090b);
        }

        /* logo container — kotak logo seragam */
        .logo-wrapper {
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(10, 13, 14, 0.7);
            border-radius: 50%;
            margin-bottom: 1.8rem;
            border: 1.5px solid var(--teal-2);
            border-color: rgba(212, 172, 90, 0.6);
            box-shadow: 0 8px 14px rgba(0,0,0,0.8), 0 0 0 1px rgba(212,172,90,0.2) inset;
            padding: 1.2rem;
            backdrop-filter: blur(8px);
            transition: 0.3s ease;
        }

        .brand-card:hover .logo-wrapper {
            border-color: var(--teal-2);
            box-shadow: 0 0 18px rgba(212,172,90,0.4);
        }

        /* SVG / gambar dummy sebagai logo — tetap konsisten */
        .logo-placeholder {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.6));
            color: var(--teal-2);
        }

        /* Nama brand */
        .brand-name {
            font-size: 1.7rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            background: linear-gradient(to right, #f2eee7, var(--cream), #e6dbba);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.01em;
            text-transform: uppercase;
            text-shadow: 0 2px 3px #00000080;
        }

        /* deskripsi */
        .brand-description {
            font-size: 0.98rem;
            line-height: 1.6;
            color: var(--text-light, #b0b0b0);
            color: #cbd5d0;
            max-width: 280px;
            margin: 0 auto;
            font-weight: 400;
            border-top: 1px dashed rgba(212,172,90,0.3);
            padding-top: 1rem;
            margin-top: 0.4rem;
        }

        /* varian aksen coklat teal-2 pada teks tertentu */
        .accent-brown {
            color: var(--teal-2);
            font-weight: 500;
        }

        /* responsive: tablet 2 kolom, mobile 1 kolom */
        @media (max-width: 900px) {
            .brand-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
        }

        @media (max-width: 600px) {
            .brand-grid {
                grid-template-columns: 1fr;
            }
            .section-headline h2 {
                font-size: 2rem;
            }
            .brand-card {
                padding: 2rem 1.2rem;
            }
        }

        /* subtle brown gradient overlay */
        .brand-card {
            position: relative;
            z-index: 2;
            background: radial-gradient(ellipse at 50% 20%, rgba(212, 172, 90, 0.08), var(--panel) 70%);
            background-blend-mode: overlay;
        }

        /* Aspek kotak grid SAMA RATA — card menggunakan flex column, semua elemen stretch */
        .brand-card > * {
            max-width: 100%;
        }

        /* Logo svg dummy — untuk keperluan demo, dengan tone coklat/hitam */
        .logo-svg-bg {
            background: radial-gradient(circle, #2a241a, #0f0e0c);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        /* pemanis garis gradasi coklat */
        .brand-footer {
            margin-top: auto;
            font-size: 0.7rem;
            color: rgba(212,172,90,0.6);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* default fallback logo */
        .brand-logo-symbol {
            fill: var(--teal-2);
            width: 68px;
            height: 68px;
        }

        /* semua logo diatur agar selalu proporsional */
        .logo-wrapper svg {
            width: 70px;
            height: 70px;
            transition: transform 0.4s var(--transition);
        }

        .brand-card:hover svg {
            transform: scale(1.05);
            filter: drop-shadow(0 0 8px #d4ac5a80);
        }

        /* style untuk menegaskan dominan hitam & gradasi coklat */
        hr.brown-hr {
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--teal-2), transparent);
            border: 0;
            margin: 1.8rem auto 0.5rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <section class="partnership-section">
        <!-- headline dengan nuansa root: gradasi coklat & hitam -->
        <div class="section-headline">
            <h2>⚡ PARTNERSHIP & BRAND</h2>
            <p>——  grid 3 · dominan hitam · gradasi coklat  ——</p>
        </div>

        <!-- 3 grid per baris — kotak SAMA RATA, isi: nama, logo, deskripsi -->
        <div class="brand-grid">

            <!-- CARD 1 • BRAND LUMINA -->
            <div class="brand-card">
                <div class="logo-wrapper">
                    <svg viewBox="0 0 100 100" width="70" height="70" xmlns="http://www.w3.org/2000/svg">
                        <rect x="15" y="15" width="70" height="70" rx="18" fill="none" stroke="var(--teal-2, #d4ac5a)" stroke-width="2.5" stroke-dasharray="6 4" opacity="0.9"/>
                        <circle cx="50" cy="50" r="28" fill="rgba(212,172,90,0.2)" stroke="var(--teal-2)" stroke-width="2"/>
                        <path d="M50 30 L60 45 L55 62 L45 62 L40 45 Z" fill="var(--teal-2)" opacity="0.9"/>
                        <text x="50" y="82" font-size="12" fill="var(--cream)" text-anchor="middle" font-weight="bold">LUM</text>
                    </svg>
                </div>
                <h3 class="brand-name">LUMINA</h3>
                <p class="brand-description">
                    <span class="accent-brown">⟡ creative studio ⟡</span><br>
                    Brand identity & immersive experience. Kolaborasi eksklusif dengan nuansa earthy tone.
                </p>
                <hr class="brown-hr">
                <div class="brand-footer">partnership est.2025</div>
            </div>

            <!-- CARD 2 • BRAND TERRA -->
            <div class="brand-card">
                <div class="logo-wrapper">
                    <svg viewBox="0 0 100 100" width="70" height="70" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="34" fill="none" stroke="var(--teal-2)" stroke-width="2.2" stroke-dasharray="5 3"/>
                        <polygon points="50,25 70,50 60,70 40,70 30,50" fill="rgba(212,172,90,0.3)" stroke="var(--teal-2)" stroke-width="2"/>
                        <line x1="32" y1="45" x2="68" y2="55" stroke="var(--cream)" stroke-width="1.8" opacity="0.7"/>
                        <text x="50" y="84" font-size="13" fill="var(--cream)" text-anchor="middle" font-family="monospace">⛰️ TERRA</text>
                    </svg>
                </div>
                <h3 class="brand-name">TERRA</h3>
                <p class="brand-description">
                    <span class="accent-brown">sustainable goods</span><br>
                    Material alami, kemasan daur ulang. Kolaborasi menghadirkan warm brown aesthetic.
                </p>
                <hr class="brown-hr">
                <div class="brand-footer">eco alliance</div>
            </div>

            <!-- CARD 3 • BRAND KOPI NUSANTARA -->
            <div class="brand-card">
                <div class="logo-wrapper">
                    <svg viewBox="0 0 100 100" width="70" height="70" xmlns="http://www.w3.org/2000/svg">
                        <rect x="25" y="35" width="50" height="40" rx="8" fill="none" stroke="var(--teal-2)" stroke-width="2.5"/>
                        <ellipse cx="50" cy="35" rx="22" ry="10" stroke="var(--teal-2)" stroke-width="2" fill="rgba(212,172,90,0.2)"/>
                        <path d="M38 65 L45 75 L55 75 L62 65" stroke="var(--cream)" stroke-width="2.2" fill="none"/>
                        <circle cx="50" cy="55" r="8" fill="var(--teal-2)" opacity="0.5"/>
                        <text x="50" y="92" font-size="12" fill="var(--cream)" text-anchor="middle">☕ KOPI</text>
                    </svg>
                </div>
                <h3 class="brand-name">KOPI NUSA</h3>
                <p class="brand-description">
                    <span class="accent-brown">artisan coffee • </span><br>
                    Single origin dengan sentuhan coklat dan rempah. Kemasan kolaborasi edisi terbatas.
                </p>
                <hr class="brown-hr">
                <div class="brand-footer">roastery since 2019</div>
            </div>

            <!-- CARD 4 • BRAND BRWN STUDIO -->
            <div class="brand-card">
                <div class="logo-wrapper">
                    <svg viewBox="0 0 100 100" width="70" height="70" xmlns="http://www.w3.org/2000/svg">
                        <rect x="22" y="22" width="56" height="56" rx="8" fill="none" stroke="var(--teal-2)" stroke-width="2" stroke-dasharray="8 4"/>
                        <path d="M30 50 L70 50 M50 30 L50 70" stroke="var(--teal-2)" stroke-width="2.5"/>
                        <circle cx="50" cy="50" r="16" fill="rgba(212,172,90,0.2)" stroke="var(--cream)" stroke-width="1.5"/>
                        <text x="50" y="82" font-size="12" fill="#e6dbba" text-anchor="middle">BRWN</text>
                    </svg>
                </div>
                <h3 class="brand-name">BRWN STUDIO</h3>
                <p class="brand-description">
                    <span class="accent-brown">design & architecture</span><br>
                    Eksplorasi material gelap, aksen coklat hangat. Grid 3 kolom, identitas kuat.
                </p>
                <hr class="brown-hr">
                <div class="brand-footer">minimalist lab</div>
            </div>

            <!-- CARD 5 • BRAND SABANA -->
            <div class="brand-card">
                <div class="logo-wrapper">
                    <svg viewBox="0 0 100 100" width="70" height="70" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="50,20 80,40 80,70 50,90 20,70 20,40" fill="none" stroke="var(--teal-2)" stroke-width="2.2"/>
                        <circle cx="50" cy="50" r="22" fill="rgba(212,172,90,0.15)" stroke="var(--cream)" stroke-width="1.5"/>
                        <path d="M35 52 L45 58 L60 44" stroke="var(--teal-2)" stroke-width="3" fill="none"/>
                        <text x="50" y="84" font-size="13" fill="var(--cream)" text-anchor="middle">SABANA</text>
                    </svg>
                </div>
                <h3 class="brand-name">SABANA</h3>
                <p class="brand-description">
                    <span class="accent-brown">outdoor & leather</span><br>
                    Perlengkapan perjalanan dengan sentuhan coklat artisan. Kolaborasi eksklusif.
                </p>
                <hr class="brown-hr">
                <div class="brand-footer">adventure series</div>
            </div>

            <!-- CARD 6 • BRAND CACAO -->
            <div class="brand-card">
                <div class="logo-wrapper">
                    <svg viewBox="0 0 100 100" width="70" height="70" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="50" cy="55" rx="28" ry="20" stroke="var(--teal-2)" stroke-width="2.2" fill="none"/>
                        <path d="M35 44 Q50 30, 65 44" stroke="var(--cream)" stroke-width="2" fill="none"/>
                        <circle cx="40" cy="58" r="5" fill="rgba(212,172,90,0.5)"/>
                        <circle cx="60" cy="58" r="5" fill="rgba(212,172,90,0.6)"/>
                        <text x="50" y="92" font-size="12" fill="var(--cream)" text-anchor="middle">CACAO</text>
                    </svg>
                </div>
                <h3 class="brand-name">CACAO</h3>
                <p class="brand-description">
                    <span class="accent-brown">bean to bar</span><br>
                    Cokelat premium fermentasi alami. Rintisan brand lokal dengan identitas hitam-coklat.
                </p>
                <hr class="brown-hr">
                <div class="brand-footer">chocolate affair</div>
            </div>

            <!-- Card 7, 8, 9 untuk demonstrasi baris kedua penuh 3 grid (total 9 card = 3 baris) -->
            <!-- CARD 7 • ARKANE -->
            <div class="brand-card">
                <div class="logo-wrapper">
                    <svg viewBox="0 0 100 100" width="70" height="70">
                        <rect x="20" y="20" width="60" height="60" rx="12" fill="none" stroke="var(--teal-2)" stroke-width="2.5"/>
                        <path d="M50 35 L65 50 L50 65 L35 50 Z" fill="rgba(212,172,90,0.4)" stroke="var(--cream)" stroke-width="1.5"/>
                        <text x="50" y="82" font-size="13" fill="#f5f5dc" text-anchor="middle">ARK</text>
                    </svg>
                </div>
                <h3 class="brand-name">ARKANE</h3>
                <p class="brand-description">
                    <span class="accent-brown">digital forge</span><br>
                    Web3 & immersive brand. Palette hitam gradasi coklat, kolaborasi strategis.
                </p>
                <hr class="brown-hr">
                <div class="brand-footer">blockchain</div>
            </div>

            <!-- CARD 8 • SOIL -->
            <div class="brand-card">
                <div class="logo-wrapper">
                    <svg viewBox="0 0 100 100" width="70" height="70">
                        <circle cx="50" cy="50" r="30" fill="none" stroke="var(--teal-2)" stroke-width="2" stroke-dasharray="7 3"/>
                        <path d="M40 42 L60 42 L68 60 L32 60 Z" fill="rgba(212,172,90,0.25)" stroke="var(--teal-2)"/>
                        <text x="50" y="78" font-size="14" fill="var(--cream)" text-anchor="middle">SOIL</text>
                    </svg>
                </div>
                <h3 class="brand-name">SOIL</h3>
                <p class="brand-description">
                    <span class="accent-brown">regenerative farm</span><br>
                    Kolaborasi pertanian berkelanjutan. Esensi coklat dan bumi.
                </p>
                <hr class="brown-hr">
                <div class="brand-footer">organic</div>
            </div>

            <!-- CARD 9 • WARĒG -->
            <div class="brand-card">
                <div class="logo-wrapper">
                    <svg viewBox="0 0 100 100" width="70" height="70">
                        <path d="M30 45 L50 28 L70 45 L60 72 L40 72 Z" fill="none" stroke="var(--teal-2)" stroke-width="2.3"/>
                        <path d="M45 55 L55 55 L52 68 L48 68 Z" fill="var(--cream)" opacity="0.7"/>
                        <text x="50" y="90" font-size="12" fill="#e6dbba" text-anchor="middle">WAREG</text>
                    </svg>
                </div>
                <h3 class="brand-name">WARĒG</h3>
                <p class="brand-description">
                    <span class="accent-brown">batik kontemporer</span><br>
                    Tenun dan pola gradasi coklat. Kolaborasi partnership Nusantara.
                </p>
                <hr class="brown-hr">
                <div class="brand-footer">wastra</div>
            </div>
        </div> <!-- end brand-grid -->
        
        <!-- subtle caption untuk memperkuat kesan dominan hitam & gradasi coklat -->
        <div style="text-align: center; margin-top: 3rem; color: var(--teal-2); opacity: 0.6; font-size: 0.8rem; letter-spacing: 3px; border-top: 1px solid rgba(212,172,90,0.2); padding-top: 1.8rem;">
            ⚡ BRAND PARTNERSHIP · GRID 3 KOLOM · DOMINAN HITAM & GRADASI COKLAT · ROOT VARIABLES ⚡
        </div>
    </section>
</body>
</html>