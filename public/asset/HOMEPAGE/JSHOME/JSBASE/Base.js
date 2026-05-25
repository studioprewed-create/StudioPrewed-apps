export function initBase() {
    const header = document.getElementById('siteHeader');
    const navLinks = document.querySelectorAll("nav a");
    const mapEl = document.getElementById('map');
    const wa = document.querySelector(".wa-float");
    const sectionFloat = document.querySelector(".section-float");

    if (header) {
        const onScrollHeader = () => {
        if (window.scrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        };
        onScrollHeader();
        window.addEventListener('scroll', onScrollHeader);
    }

    if (mapEl && window.L) {
        const map = L.map(mapEl).setView([-6.8845402, 107.6135556], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        L.marker([-6.8845402, 107.6135556]).addTo(map)
        .bindPopup("<b>Studio Prewed</b><br>Jl. Ir. H. Juanda No.185, Simpang, Dago, Kecamatan Coblong, Kota Bandung, Jawa Barat 40135")
        .openPopup();
    }

    if (navLinks.length) {
        navLinks.forEach(link => {
        link.addEventListener("click", function () {
            navLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
        });
        });
    }

    if (wa) {
        function showText() {

        // 🔥 kalau lagi di-hide (misal modal buka), skip
        if (wa.classList.contains('hide')) return;

        wa.classList.add("show");

        setTimeout(() => {
            wa.classList.remove("show");
        }, 4000);
        }

        setTimeout(showText, 2000);
        setInterval(showText, 10000);
    }

    if (sectionFloat) {

        const icon = sectionFloat.querySelector("i");
        const text = sectionFloat.querySelector(".section-text");

        const downTarget =
            sectionFloat.dataset.downTarget;

        const upTarget =
            sectionFloat.dataset.upTarget;

        const downText =
            sectionFloat.dataset.downText ||
            "Explore";

        const upText =
            sectionFloat.dataset.upText ||
            "Back To Top";

        const targetSection =
            document.querySelector(downTarget);

        /* =========================
            FLOAT ANIMATION
        ========================= */

        function showSectionText() {

            if (
                sectionFloat.classList.contains('hide')
            ) return;

            sectionFloat.classList.add("show");

            setTimeout(() => {

                sectionFloat.classList.remove(
                    "show"
                );

            }, 4000);
        }

        setTimeout(showSectionText, 2500);

        setInterval(showSectionText, 12000);

        /* =========================
            UPDATE BUTTON
        ========================= */

        function updateSectionFloat() {

            if (!targetSection) return;

            const triggerPoint =
                targetSection.offsetTop - 100;

            if (window.scrollY >= triggerPoint) {

                sectionFloat.setAttribute(
                    "href",
                    upTarget
                );

                sectionFloat.setAttribute(
                    "aria-label",
                    upText
                );

                text.textContent = upText;

                icon.classList.remove(
                    "fa-arrow-down"
                );

                icon.classList.add(
                    "fa-arrow-up"
                );

            } else {

                sectionFloat.setAttribute(
                    "href",
                    downTarget
                );

                sectionFloat.setAttribute(
                    "aria-label",
                    downText
                );

                text.textContent = downText;

                icon.classList.remove(
                    "fa-arrow-up"
                );

                icon.classList.add(
                    "fa-arrow-down"
                );
            }
        }

        updateSectionFloat();

        window.addEventListener(
            "scroll",
            updateSectionFloat
        );
    }
}

export function initScrollLink() {
    const links = document.querySelectorAll('.scroll-link');

    if (!links.length) return;

        links.forEach(link => {
            link.addEventListener('click', function (e) {

                const targetId = this.dataset.target;
                const target   = document.getElementById(targetId);

                if (!target) return;

                e.preventDefault();
                const header = document.getElementById('siteHeader');
                const offset = header ? header.offsetHeight : 0;

                const top = target.offsetTop - offset;

                window.scrollTo({
                    top: top,
                    behavior: 'smooth'
                });

            });
        });
}

export function initSmoothScroll() {
    const headerEl = document.querySelector('header');

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', e => {
            const href = anchor.getAttribute('href');
            if (!href || href === '#') return;

            const slideTarget = document.querySelector('#tSlider .slides ' + href);
            if (slideTarget) {
            e.preventDefault();

            if (location.hash !== href) {
                location.hash = href;
            } else {
                window.dispatchEvent(new Event('hashchange'));
            }

            const testiSec = document.getElementById('testi');
            if (testiSec) {
                const headerH = headerEl ? headerEl.offsetHeight : 0;
                const y = testiSec.getBoundingClientRect().top + window.scrollY - headerH;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
            return;
            }

            const targetElement = document.querySelector(href);
            if (!targetElement) return;

            e.preventDefault();
            const headerHeight = headerEl ? headerEl.offsetHeight : 0;
            const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - headerHeight;
            window.scrollTo({ top: targetPosition, behavior: 'smooth' });
        });
    });
}

export function initSuccessAlert() {

    const alertBox = document.getElementById('successAlert');

    if (!alertBox) return;

    setTimeout(() => {

      alertBox.style.transition = 'all .4s ease';
      alertBox.style.opacity = '0';
      alertBox.style.transform = 'translateY(-10px)';

      setTimeout(() => {
        alertBox.remove();
      }, 400);

    }, 4000);

}

export function initScrollAnimations() {
    const animatedEls = document.querySelectorAll(
        '.service-card, .gallery-item, .stat-item, .review-card, .social-card'
    );
        if (!animatedEls.length) return;

        animatedEls.forEach(el => {
            el.style.opacity = 0;
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });

    function animateOnScroll() {
        animatedEls.forEach(el => {
        const elementPosition = el.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.3;
        if (elementPosition < screenPosition) {
            el.style.opacity = 1;
            el.style.transform = 'translateY(0)';
        }
        });
    }

    window.addEventListener('scroll', animateOnScroll);
    window.addEventListener('load', animateOnScroll);
    animateOnScroll();
}

export function initLandingEffects() {
    const prefersReduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
    const pointerFine = matchMedia('(pointer: fine)').matches;
    const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

    $$('#about img, #portrait-services img, #whatWeDo img').forEach(img => {
        img.loading = 'lazy';
        img.decoding = 'async';
        img.fetchPriority = 'low';
    });

    // Reveal animation
    const revealTargets = [
        '#about .head',
        '#about .mosaic-item',
        '#about .mosaic-text',
        '#portrait-services .head',
        '#portrait-services .f-card',
        '#whatWeDo .head',
        '#whatWeDo .process-item'
    ];
    const revealEls = revealTargets.flatMap(sel => $$(sel));
    revealEls.forEach((el, i) => {
        el.classList.add('reveal-init');
        el.style.setProperty('--reveal-delay', `${(i % 8) * 80}ms`);
    });

    if (!prefersReduced && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries, obs) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
            e.target.classList.add('reveal-visible');
            obs.unobserve(e.target);
            }
        });
        }, { threshold: 0.15, rootMargin: '0px 0px -10% 0px' });

        revealEls.forEach(el => io.observe(el));
    } else {
        revealEls.forEach(el => el.classList.add('reveal-visible'));
    }

    if (!prefersReduced && pointerFine) {
        const tiltEls = $$('#about .mosaic-item, #portrait-services .f-card, #whatWeDo .process-item');
        tiltEls.forEach(card => {
        const glare = document.createElement('span');
        glare.className = 'tilt-glare';
        card.appendChild(glare);

        let rx = 0, ry = 0, tx = 0, ty = 0, raf = null, hover = false;
        const MAX = 6, EASE = 0.12;

        const loop = () => {
            tx += (rx - tx) * EASE;
            ty += (ry - ty) * EASE;
            card.style.transform =
            `perspective(900px) rotateX(${tx.toFixed(2)}deg) rotateY(${ty.toFixed(2)}deg)`;
            if (hover || Math.abs(tx - rx) > 0.01 || Math.abs(ty - ry) > 0.01) {
            raf = requestAnimationFrame(loop);
            } else {
            cancelAnimationFrame(raf);
            raf = null;
            }
        };

        const onMove = e => {
            const r = card.getBoundingClientRect();
            const cx = r.left + r.width / 2;
            const cy = r.top + r.height / 2;
            const dx = (e.clientX - cx) / (r.width / 2);
            const dy = (e.clientY - cy) / (r.height / 2);
            rx = Math.max(-MAX, Math.min(MAX, -dy * MAX));
            ry = Math.max(-MAX, Math.min(MAX, dx * MAX));

            const px = ((e.clientX - r.left) / r.width) * 100;
            const py = ((e.clientY - r.top) / r.height) * 100;
            glare.style.background =
            `radial-gradient(circle at ${px}% ${py}%, rgba(255,195,55,.35), rgba(255,255,255,0) 45%)`;
            if (!raf) loop();
        };

        const onEnter = () => { hover = true; card.classList.add('has-tilt-hover'); };
        const onLeave = () => {
            hover = false;
            card.classList.remove('has-tilt-hover');
            rx = 0; ry = 0;
            if (!raf) loop();
        };

        card.addEventListener('pointerenter', onEnter, { passive: true });
        card.addEventListener('pointermove', onMove, { passive: true });
        card.addEventListener('pointerleave', onLeave, { passive: true });
        });
    }

    const counters = $$('#about .stat-number-mosaic');
    counters.forEach(el => {
        const target = parseInt(el.textContent.replace(/\D/g, ''), 10) || 0;
        const suffixMatch = el.textContent.match(/\D+/);
        const suffix = suffixMatch ? suffixMatch[0] : '';
        const duration = 1800;

        const update = ts => {
            if (!el._startTime) el._startTime = ts;
            const progress = Math.min((ts - el._startTime) / duration, 1);
            const value = Math.floor(progress * target).toLocaleString() + suffix;
            el.textContent = value;
            if (progress < 1) requestAnimationFrame(update);
            };

        const obs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                requestAnimationFrame(update);
                obs.unobserve(el);
                }
            });
        }, { threshold: 0.6 });

        obs.observe(el);
    });
}

export function enableModalBackClose(modal,closeCallback) {

        if (!modal) return;

    if (!history.state?.modalOpen) {

        history.pushState(
            { modalOpen: true },
            ''
        );

    }

    function handleBack() {

        if (
            modal.classList.contains('active') ||
            modal.style.display === 'flex'
        ) {

            closeCallback();

        }

        window.removeEventListener(
            'popstate',
            handleBack
        );
    }

    window.addEventListener(
        'popstate',
        handleBack
    );
}


