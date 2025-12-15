// =======================================
//  IMAGE ZOOM UNTUK PACKAGE CARDS
// =======================================
function expandImage(src) {
    const modal = document.getElementById('imageModal');
    const img   = document.getElementById('expandedImage');
    if (!modal || !img) return;

    img.src = src;
    modal.style.display = 'block';
}

document.addEventListener('click', function (e) {
    if (e.target.matches('[data-close-img]') || e.target.id === 'imageModal') {
        const modal = document.getElementById('imageModal');
        if (modal) modal.style.display = 'none';
    }
});

// =======================================
//  DOM READY
// =======================================
document.addEventListener('DOMContentLoaded', function () {
    const $  = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

    // ==============================
    //  INLINE EDIT HOMEPAGE ADDON
    // ==============================
    document
        .querySelectorAll('.contenteditable[data-section="addon"]')
        .forEach(el => {
            el.addEventListener('blur', () => {
                const id    = el.dataset.id;
                const field = el.dataset.field;
                const value = el.innerText.trim();

                if (!id || !field) return;

                fetch(`/executive/homepages/inline-update/addon/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ [field]: value }),
                }).catch(() => {
                    // kalau gagal diam saja dulu, bisa ditambah toast kalau mau
                });
            });
        });

    // ==============================
    //  LEAFLET MAP
    // ==============================
    const mapEl = document.getElementById('map');
    if (mapEl && window.L) {
        const map = L.map(mapEl).setView([-6.8845402, 107.6135556], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        L.marker([-6.8845402, 107.6135556]).addTo(map)
            .bindPopup(
                "<b>Studio Prewed</b><br>Jl. Ir. H. Juanda No.185, Simpang, Dago, Kecamatan Coblong, Kota Bandung, Jawa Barat 40135"
            )
            .openPopup();
    }

    // ==============================
    //  NAV ACTIVE STATE + HASH SCROLL
    // ==============================
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    const initialHash = window.location.hash;
    if (initialHash) {
        const target = document.querySelector(initialHash);
        if (target) target.scrollIntoView({ behavior: 'smooth' });
    }

    // ==============================
    //  HEADER SCROLL EFFECT
    // ==============================
    const header = document.getElementById('siteHeader');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            header?.classList.add('scrolled');
        } else {
            header?.classList.remove('scrolled');
        }
    });

    // ==============================
    //  HERO CAROUSEL
    // ==============================
    const carouselItems    = document.querySelectorAll('.carousel-item');
    const carouselControls = document.querySelectorAll('.carousel-control');
    let currentSlide       = 0;
    let slideInterval;

    function showSlide(index) {
        if (!carouselItems.length) return;
        carouselItems.forEach(item => item.classList.remove('active'));
        if (carouselControls.length) {
            carouselControls.forEach(control => control.classList.remove('active'));
        }
        carouselItems[index].classList.add('active');
        if (carouselControls.length) {
            carouselControls[index].classList.add('active');
        }
        currentSlide = index;
    }

    function nextSlide() {
        if (!carouselItems.length) return;
        showSlide((currentSlide + 1) % carouselItems.length);
    }

    function startCarousel() {
        if (!carouselItems.length) return;
        slideInterval = setInterval(nextSlide, 5000);
    }

    carouselControls.forEach(control => {
        control.addEventListener('click', function () {
            if (!carouselItems.length) return;
            clearInterval(slideInterval);
            showSlide(parseInt(this.getAttribute('data-index'), 10));
            startCarousel();
        });
    });

    if (carouselItems.length) {
        showSlide(0);
        startCarousel();
    }

    // ==============================
    //  GALLERY CENTER CAROUSEL
    // ==============================
    const galleryTrack   = document.querySelector('.gallery-track');
    const galleryItems   = document.querySelectorAll('.gallery-item');
    const galleryPrevBtn = document.querySelector('.nav-btn.prev');
    const galleryNextBtn = document.querySelector('.nav-btn.next');
    let currentCenter    = 2;

    function updateGallery() {
        if (!galleryTrack || !galleryItems.length) return;
        galleryItems.forEach((item, index) => {
            item.classList.toggle('center', index === currentCenter);
        });
        const centerItem = galleryItems[currentCenter];
        if (centerItem) {
            galleryTrack.scrollLeft =
                centerItem.offsetLeft -
                galleryTrack.offsetWidth / 2 +
                centerItem.offsetWidth / 2;
        }
    }

    galleryPrevBtn &&
        galleryPrevBtn.addEventListener('click', () => {
            if (currentCenter > 0) {
                currentCenter--;
                updateGallery();
            }
        });

    galleryNextBtn &&
        galleryNextBtn.addEventListener('click', () => {
            if (currentCenter < galleryItems.length - 1) {
                currentCenter++;
                updateGallery();
            }
        });

    galleryItems.forEach((item, index) => {
        item.addEventListener('click', e => {
            if (e.target.closest('[data-open]')) return;

            if (currentCenter !== index) {
                currentCenter = index;
                updateGallery();
                return;
            }

            const sel   = item.getAttribute('data-modal');
            const modal = sel ? document.querySelector(sel) : null;
            if (modal) {
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    updateGallery();

    // ==============================
    //  SERVICE CARD HOVER / TOUCH
    // ==============================
    document.querySelectorAll('.service-card').forEach(card => {
        const content = card.querySelector('.service-content');
        if (!content) return;

        card.addEventListener('mouseenter', () => {
            content.style.background =
                'linear-gradient(to top, rgba(0, 0, 0, 0.95), transparent 70%)';
        });
        card.addEventListener('mouseleave', () => {
            content.style.background =
                'linear-gradient(to top, rgba(0, 0, 0, 0.95), transparent 70%)';
        });
        card.addEventListener('touchstart', () => {
            if (window.innerWidth < 768) {
                card.classList.toggle('active');
                content.style.background = card.classList.contains('active')
                    ? 'linear-gradient(to top, rgba(0, 0, 0, 0.98), transparent 70%)'
                    : 'linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent 70%)';
            }
        });
    });

    document.addEventListener('click', e => {
        if (!e.target.closest('.service-card')) {
            document.querySelectorAll('.service-card').forEach(card => {
                const content = card.querySelector('.service-content');
                if (!content) return;
                card.classList.remove('active');
                content.style.background =
                    'linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent 70%)';
            });
        }
    });

    // ==============================
    //  FAQ TOGGLE
    // ==============================
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const q = item.querySelector('.faq-question');
        if (!q) return;
        q.addEventListener('click', () => {
            faqItems.forEach(other => {
                if (other !== item) other.classList.remove('active');
            });
            item.classList.toggle('active');
        });
    });

    // ==============================
    //  SMOOTH ANCHOR SCROLL
    // ==============================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', e => {
            const targetId = anchor.getAttribute('href');
            if (!targetId || targetId === '#') return;
            const targetElement = document.querySelector(targetId);
            if (!targetElement) return;
            e.preventDefault();
            const headerHeight =
                document.querySelector('header')?.offsetHeight || 0;
            const targetPosition = targetElement.offsetTop - headerHeight;
            window.scrollTo({ top: targetPosition, behavior: 'smooth' });
        });
    });

    // ==============================
    //  SCROLL ANIMATION (FADE-IN)
    // ==============================
    const animatedEls = document.querySelectorAll(
        '.service-card, .gallery-item, .stat-item, .review-card, .social-card'
    );
    animatedEls.forEach(el => {
        el.style.opacity   = 0;
        el.style.transform = 'translateY(20px)';
        el.style.transition =
            'opacity 0.6s ease, transform 0.6s ease';
    });

    function animateOnScroll() {
        animatedEls.forEach(el => {
            const elementPosition = el.getBoundingClientRect().top;
            const screenPosition  = window.innerHeight / 1.3;
            if (elementPosition < screenPosition) {
                el.style.opacity   = 1;
                el.style.transform = 'translateY(0)';
            }
        });
    }
    window.addEventListener('scroll', animateOnScroll);
    window.addEventListener('load', animateOnScroll);

    // ==============================
    //  BOOKING BUTTON (MIDDLEBAR)
    // ==============================
    const bookingBtn = document.querySelector('.booking-btn');
    bookingBtn &&
        bookingBtn.addEventListener('click', function () {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
                document
                    .getElementById('bookingWizard')
                    ?.scrollIntoView({ behavior: 'smooth' });
            }, 200);
        });

    const pkgSelectForWizard = document.getElementById('package_id');
    document.querySelectorAll('.booking-trigger').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            const pkgId = this.dataset.packageId;
            if (pkgSelectForWizard && pkgId) {
                pkgSelectForWizard.value = pkgId;
                pkgSelectForWizard.dispatchEvent(new Event('change'));
            }

            const wizard = document.getElementById('bookingWizard');
            if (wizard) {
                wizard.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            }
        });
    });

    // ==============================
    //  FLOATING MIDDLEBAR
    // ==============================
    const middlebar = document.querySelector('.middlebar-container');
    if (middlebar) {
        function addFloatingEffect() {
            middlebar.style.transition = 'transform 3s ease-in-out';
            middlebar.style.transform  = 'translateY(-5px)';
            setTimeout(() => {
                middlebar.style.transform = 'translateY(0)';
            }, 1500);
        }
        addFloatingEffect();
        setInterval(addFloatingEffect, 4000);
    }

    // ==============================
    //  STATS COUNTER (ABOUT SECTION)
    // ==============================
    const aboutStatBoxes = document.querySelectorAll('.about-stat-box');
    if (aboutStatBoxes.length) {
        const aboutObserverOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -50px 0px',
        };

        const aboutObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statBox       = entry.target;
                    const numberElement = statBox.childNodes[0];

                    if (numberElement && numberElement.nodeType === 3) {
                        const targetNumber = parseInt(
                            numberElement.textContent,
                            10
                        );
                        if (isNaN(targetNumber)) return;
                        let currentNumber = 0;
                        const increment   = targetNumber / 50;
                        const duration    = 1500;
                        const stepTime    = duration / 50;

                        const timer = setInterval(() => {
                            currentNumber += increment;
                            if (currentNumber >= targetNumber) {
                                currentNumber = targetNumber;
                                clearInterval(timer);
                            }
                            numberElement.textContent = Math.floor(
                                currentNumber
                            );
                        }, stepTime);
                    }
                }
            });
        }, aboutObserverOptions);

        aboutStatBoxes.forEach(box => {
            aboutObserver.observe(box);
        });
    }

    // ==============================
    //  RIPPLE CSS (JAGA DI SINI SAJA)
    // ==============================
    const rippleStyle = document.createElement('style');
    rippleStyle.textContent = `
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(rippleStyle);

    // ==============================
    //  MODAL DETAIL PACKAGE & TEMA
    // ==============================
    (function () {
        document.querySelectorAll('[data-open]').forEach(btn => {
            btn.addEventListener('click', e => {
                e.stopPropagation();
                const sel   = btn.getAttribute('data-open');
                const modal = document.querySelector(sel);
                if (modal) {
                    modal.classList.add('is-open');
                    document.body.style.overflow = 'hidden';
                }
            });
        });

        function closeDetail(modal) {
            modal.classList.remove('is-open');
            document.body.style.overflow = '';
        }

        document.addEventListener('click', e => {
            if (e.target.matches('[data-close-detail]')) {
                const modal =
                    e.target.closest('.pkg-modal') ||
                    document.querySelector('.pkg-modal.is-open');
                if (modal) closeDetail(modal);
            }
        });

        document
            .querySelectorAll('.pkg-modal__backdrop')
            .forEach(bd => {
                bd.addEventListener('click', () => {
                    const modal = bd.closest('.pkg-modal');
                    if (modal) closeDetail(modal);
                });
            });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document
                    .querySelectorAll('.pkg-modal.is-open')
                    .forEach(m => closeDetail(m));
            }
        });
    })();

    // ==============================
    //  PROMO SLIDER
    // ==============================
    (function () {
        const wrap = document.getElementById('promoCarousel');
        if (!wrap) return;

        const slides   = Array.from(
            wrap.querySelectorAll('.promo-slide')
        );
        const dots     = Array.from(
            wrap.querySelectorAll('.promo-dot')
        );
        const interval = parseInt(
            wrap.dataset.interval || '4000',
            10
        );

        let idx = 0,
            timer;

        function show(n) {
            slides[idx]?.classList.remove('is-active');
            dots[idx]?.classList.remove('is-active');
            idx = (n + slides.length) % slides.length;
            slides[idx]?.classList.add('is-active');
            dots[idx]?.classList.add('is-active');
        }

        function next() {
            show(idx + 1);
        }
        function start() {
            stop();
            timer = setInterval(next, interval);
        }
        function stop() {
            if (timer) clearInterval(timer);
        }

        dots.forEach((d, i) =>
            d.addEventListener('click', () => {
                show(i);
                start();
            })
        );

        wrap.addEventListener('mouseenter', stop);
        wrap.addEventListener('mouseleave', start);

        if (slides.length) {
            show(0);
            start();
        }
    })();

    // ==============================
    //  TEMA / BAJU THUMBNAIL SWITCH
    // ==============================
    document.addEventListener(
        'click',
        function (e) {
            const btn = e.target.closest('.tbm-thumb');
            if (!btn) return;

            const mainSel = btn.getAttribute('data-main');
            const mainImg = document.querySelector(mainSel);
            if (!mainImg) return;

            mainImg.src = btn.getAttribute('data-src');

            const group = btn.closest('.tbm-thumbs');
            group
                ?.querySelectorAll('.tbm-thumb')
                .forEach(el => el.classList.remove('is-active'));
            btn.classList.add('is-active');
        },
        { passive: true }
    );

    // ==============================
    //  MODAL NAV NEXT/PREV TEMA/PAKET
    // ==============================
    (function () {
        function bindModalNav(attr) {
            document.addEventListener('click', e => {
                const btn = e.target.closest(`[${attr}]`);
                if (!btn) return;

                const targetSel = btn.getAttribute('data-target');
                const nextModal = targetSel
                    ? document.querySelector(targetSel)
                    : null;
                const curModal = btn.closest('.pkg-modal');

                if (curModal) curModal.classList.remove('is-open');
                if (nextModal) {
                    nextModal.classList.add('is-open');
                    document.body.style.overflow = 'hidden';
                }
            });
        }

        bindModalNav('data-tema-nav');
        bindModalNav('data-pkg-nav');
    })();

    // ==============================
    // PREFILL DATA DIRI (TANPA JSON)
    // ==============================
    (function prefillDataDiri() {
        const box = document.getElementById('prefillData');
        if (!box) return;

        const namaUser   = box.dataset.nama || '';
        const phoneUser  = box.dataset.phone || '';
        const genderUser = box.dataset.gender || '';
        const emailUser  = box.dataset.email || '';

        const namaPas  = box.dataset.namaPasangan || '';
        const phonePas = box.dataset.phonePasangan || '';

        const namaCpp  = document.getElementById('nama_cpp');
        const phoneCpp = document.getElementById('phone_cpp');
        const emailCpp = document.getElementById('email_cpp');

        const namaCpw  = document.getElementById('nama_cpw');
        const phoneCpw = document.getElementById('phone_cpw');
        const emailCpw = document.getElementById('email_cpw');

        // Perempuan → CPW, Laki-laki → CPP
        if (genderUser === 'perempuan') {
            // USER = CPW
            if (namaCpw)  namaCpw.value  = namaUser;
            if (phoneCpw) phoneCpw.value = phoneUser;
            if (emailCpw) emailCpw.value = emailUser;

            // PASANGAN = CPP
            if (namaCpp)  namaCpp.value  = namaPas;
            if (phoneCpp) phoneCpp.value = phonePas;
        } else {
            // USER = CPP
            if (namaCpp)  namaCpp.value  = namaUser;
            if (phoneCpp) phoneCpp.value = phoneUser;
            if (emailCpp) emailCpp.value = emailUser;

            // PASANGAN = CPW
            if (namaCpw)  namaCpw.value  = namaPas;
            if (phoneCpw) phoneCpw.value = phonePas;
        }
    })();

        // =====================================================================
    //  BOOKING WIZARD – VERSI LENGKAP (slot_main / slot_extra / tema addon)
    // =====================================================================
    (function initBookingWizard() {
        const wizard = $('#bookingWizard');
        if (!wizard) return;

        const APP_ROUTES = window.APP_ROUTES || {};
        const API_SLOTS  = APP_ROUTES.apiSlots || '/executive/api/slots';
        const API_TEMA_BY_NAME = APP_ROUTES.apiTemaByName || '/executive/api/tema-by-name';

        // ====== Elemen dasar ======
        const steps      = $$('.step', wizard);
        const circles    = $$('.step-circle', wizard);
        const progress   = $('#bwProgress', wizard);
        const prevBtn    = $('#prevBtn', wizard);
        const nextBtn    = $('#nextBtn', wizard);
        const submitBtn  = $('#submitBtn', wizard);
        const summaryBox = $('#summaryBox', wizard);
        const hiddenBag  = $('#hiddenBag', wizard);

        let currentStep = 0;

        // Step 1
        const namaCpp   = $('#nama_cpp', wizard);
        const emailCpp  = $('#email_cpp', wizard);
        const phoneCpp  = $('#phone_cpp', wizard);
        const alamatCpp = $('#alamat_cpp', wizard);
        const namaCpw   = $('#nama_cpw', wizard);
        const emailCpw  = $('#email_cpw', wizard);
        const phoneCpw  = $('#phone_cpw', wizard);
        const alamatCpw = $('#alamat_cpw', wizard);

        // Step 2
        const selPackage   = $('#package_id', wizard);
        const inputDate    = $('#photoshoot_date', wizard);
        const selStyle     = $('#style', wizard);
        const slotList     = $('#slotList', wizard);

        const selTemaNama  = $('#tema_nama', wizard);
        const selTemaKode  = $('#tema_kode', wizard);
        const selTemaId    = $('#tema_id', wizard);

        const addonChecks       = $$('.addon-check', wizard);
        const extraSlotWrapper  = $('#extraSlotWrapper', wizard);
        const extraSlotList     = $('#extraSlotList', wizard);
        const extraTemaWrapper  = $('#extraTemaWrapper', wizard);
        const selTema2Nama      = $('#tema2_nama', wizard);
        const selTema2Kode      = $('#tema2_kode', wizard);
        const selTema2Id        = $('#tema2_id', wizard);

        const weddingInput = $('#wedding_date', wizard);
        const notesInput   = $('#notes', wizard);

        // Step 3
        const igCpp = $('#ig_cpp', wizard);
        const igCpw = $('#ig_cpw', wizard);
        const ttCpp = $('#tiktok_cpp', wizard);
        const ttCpw = $('#tiktok_cpw', wizard);

        // State
        let mainSlots  = [];
        let extraSlots = [];

        const state = {
            step1: {},
            step2: {},
            step3: {},
        };

        // ====== Helper umum ======
        function setStep(index) {
            currentStep = Math.max(
                0,
                Math.min(steps.length - 1, index)
            );
            steps.forEach((s, i) =>
                s.classList.toggle('active', i === currentStep)
            );
            circles.forEach((c, i) =>
                c.classList.toggle('active', i <= currentStep)
            );

            if (progress && circles.length > 1) {
                progress.style.width =
                    (currentStep / (circles.length - 1)) * 100 + '%';
            }

            if (prevBtn) prevBtn.disabled = currentStep === 0;
            if (nextBtn)
                nextBtn.textContent =
                    currentStep === steps.length - 1
                        ? 'Selesai'
                        : 'Lanjut';

            if (currentStep === steps.length - 1) {
                renderSummary();
            }

            wizard.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            });
        }

        function showError(msg) {
            alert(msg || 'Lengkapi data yang diperlukan.');
        }

        function splitTimeRange(range) {
            const [s, e] = String(range || '').split('-');
            return { start: (s || '').trim(), end: (e || '').trim() };
        }

        function getSelectedMainSlot() {
            const input = $('input[name="slot_main"]:checked', wizard);
            if (!input) return null;
            const time = input.dataset.time || '';
            const { start, end } = splitTimeRange(time);
            return {
                code: input.value,
                time,
                start,
                end,
            };
        }

        function getSelectedExtraSlot() {
            const input = $('input[name="slot_extra"]:checked', wizard);
            if (!input) return null;
            const time = input.dataset.time || '';
            const { start, end } = splitTimeRange(time);
            return {
                code: input.value,
                time,
                start,
                end,
            };
        }

        // ====== Tema helper (filter kode berdasarkan nama + exclude kode tertentu) ======
        function filterTemaKode(selectKode, nama, excludeKode = null) {
            if (!selectKode) return;

            const opts = Array.from(selectKode.options);
            selectKode.value = '';
            let anyVisible   = false;

            opts.forEach((opt, idx) => {
                if (idx === 0) {
                    opt.style.display = '';
                    return;
                }
                const optNama   = opt.dataset.nama || '';
                const shouldShow =
                    !!nama &&
                    optNama === nama &&
                    (!excludeKode || opt.value !== excludeKode);

                opt.style.display = shouldShow ? '' : 'none';
                if (shouldShow) anyVisible = true;
            });

            selectKode.disabled = !anyVisible;
        }

        function applyTemaAvailabilityToIdSelect(selectEl, unavailableSet, excludeKode = null) {
        if (!selectEl) return;
        const opts = Array.from(selectEl.options);

        opts.forEach((opt, idx) => {
            if (idx === 0) {
                // opsi placeholder
                opt.disabled = false;
                opt.classList.remove('unavail');
                return;
            }

            const kode = opt.dataset.kode || '';
            if (!kode) {
                // kalau kode kosong, biarin saja
                opt.disabled = false;
                opt.classList.remove('unavail');
                return;
            }

            // Jangan izinkan pilih kode yang sama dengan exclude (tema utama untuk tema2)
            if (excludeKode && kode === excludeKode) {
                opt.disabled = true;
                opt.classList.add('unavail');
                return;
            }

            if (unavailableSet.has(String(kode))) {
                opt.disabled = true;
                opt.classList.add('unavail');
            } else {
                opt.disabled = false;
                opt.classList.remove('unavail');
            }
        });

        // Kalau yang lagi kepilih ternyata jadi disabled, kosongkan
        if (
            selectEl.value &&
            selectEl.selectedOptions[0] &&
            selectEl.selectedOptions[0].disabled
        ) {
            selectEl.value = '';
        }
    }

        // ====== ADDON helper ======
        function getSelectedAddons() {
            return addonChecks
                .filter(ch => ch.checked)
                .map(ch => ({
                    id: ch.dataset.id,
                    kategori: ch.dataset.kategori,
                    harga:
                        parseInt(ch.dataset.harga || '0', 10) || 0,
                    durasi:
                        parseInt(ch.dataset.durasi || '0', 10) || 0,
                    name:
                        ch
                            .closest('.addon-item')
                            ?.querySelector('.addon-name')
                            ?.textContent.trim() || '',
                }));
        }

        function getAddonByKategori(kat) {
            return getSelectedAddons().filter(
                a => String(a.kategori) === String(kat)
            );
        }

        function getAddonSlot() {
            const list = getAddonByKategori(1);
            return list.length ? list[0] : null; // paksa cuma 1 addon slot
        }

        function getAddonTema() {
            const list = getAddonByKategori(2);
            return list.length ? list[0] : null;
        }

        function getExtraMinutesFromAddons() {
            const slotAddon = getAddonSlot();
            return slotAddon ? slotAddon.durasi : 0;
        }

        function computeAddonTotal() {
            return getSelectedAddons().reduce(
                (sum, a) => sum + a.harga,
                0
            );
        }

        // Kalau user centang addon kategori 1 lebih dari satu, kita jadikan single-choice (radio style).
        function normalizeAddonSlotSelection(changedCheckbox) {
            if (
                !changedCheckbox ||
                changedCheckbox.dataset.kategori !== '1'
            )
                return;
            if (!changedCheckbox.checked) return;
            addonChecks.forEach(ch => {
                if (
                    ch !== changedCheckbox &&
                    ch.dataset.kategori === '1'
                ) {
                    ch.checked = false;
                }
            });
        }

        // ====== Load slot utama ======
        async function loadMainSlots() {
            const pkg  = selPackage?.value;
            const date = inputDate?.value;

            if (!slotList) return;

            if (!pkg || !date) {
                mainSlots          = [];
                slotList.innerHTML =
                    '<p style="opacity:.7">Pilih paket & tanggal untuk melihat slot.</p>';
                return;
            }

            slotList.innerHTML =
                '<p style="opacity:.7">Memuat slot...</p>';

            try {
                const url = new URL(
                    API_SLOTS,
                    window.location.origin
                );
                url.searchParams.set('package_id', pkg);
                url.searchParams.set('date', date);

                const res = await fetch(url.toString(), {
                    headers: { Accept: 'application/json' },
                });
                if (!res.ok) throw new Error('Gagal memuat slot');

                const data = await res.json();
                mainSlots  = Array.isArray(data) ? data : [];
                renderMainSlots();
            } catch (err) {
                mainSlots          = [];
                slotList.innerHTML =
                    '<p style="color:#b91c1c">Gagal memuat slot. Coba lagi.</p>';
            }
        }

       async function refreshTemaKodeUtama() {
            if (!selTemaNama || !selTemaKode) return;

            const nama = selTemaNama.value || '';
            const date = inputDate?.value || '';
            const main = getSelectedMainSlot();

            // Filter kode berdasarkan nama (tanpa exclude)
            filterTemaKode(selTemaKode, nama, null);

            // Kalau belum ada tanggal atau slot utama, cukup filter nama saja
            if (!nama || !date || !main) {
                Array.from(selTemaKode.options).forEach((opt, idx) => {
                    if (idx === 0) return;
                    opt.disabled = false;
                    opt.classList.remove('unavail');
                });
                // Untuk select by ID (#tema_id), juga bebas semua
                if (selTemaId) {
                    Array.from(selTemaId.options).forEach((opt, idx) => {
                        if (idx === 0) return;
                        opt.disabled = false;
                        opt.classList.remove('unavail');
                    });
                }
                return;
            }

            try {
                const url = new URL(API_TEMA_BY_NAME, window.location.origin);
                url.searchParams.set('nama', nama);
                url.searchParams.set('date', date);
                url.searchParams.set('start', main.start);
                url.searchParams.set('end', main.end);

                const res = await fetch(url.toString(), {
                    headers: { Accept: 'application/json' },
                });
                if (!res.ok) return;

                const data = await res.json();
                const unavailable = new Set(
                    (Array.isArray(data) ? data : [])
                        .filter(t => t.available === false)
                        .map(t => String(t.kode))
                );

                // Disable di select KODE utama
                Array.from(selTemaKode.options).forEach((opt, idx) => {
                    if (idx === 0 || !opt.value) return;

                    if (unavailable.has(String(opt.value))) {
                        opt.disabled = true;
                        opt.classList.add('unavail');
                    } else {
                        opt.disabled = false;
                        opt.classList.remove('unavail');
                    }
                });

                // Disable juga di select "by ID" (#tema_id) berdasarkan data-kode
                if (selTemaId) {
                    applyTemaAvailabilityToIdSelect(selTemaId, unavailable, null);
                }
            } catch (e) {
                // kalau error API, kita biarin, backend tetap punya validasi
            }
        }

        async function refreshTemaKodeTambahan() {
            if (!selTema2Nama || !selTema2Kode) return;
            if (extraTemaWrapper && extraTemaWrapper.style.display === 'none') return;

            const nama2 = selTema2Nama.value || '';
            const date  = inputDate?.value || '';
            const main  = getSelectedMainSlot();
            const temaUtama   = resolveTemaUtama();
            const excludeKode = temaUtama.tema_kode || '';

            // Filter dasar: by nama + exclude kode utama (di select KODE tambahan)
            filterTemaKode(selTema2Kode, nama2, excludeKode);

            // Kalau belum lengkap (nama/tanggal/slot utama), reset disable saja
            if (!nama2 || !date || !main) {
                Array.from(selTema2Kode.options).forEach((opt, idx) => {
                    if (idx === 0) return;
                    opt.disabled = false;
                    opt.classList.remove('unavail');
                });
                if (selTema2Id) {
                    Array.from(selTema2Id.options).forEach((opt, idx) => {
                        if (idx === 0) return;
                        opt.disabled = false;
                        opt.classList.remove('unavail');
                    });
                }
                return;
            }

            try {
                const url = new URL(API_TEMA_BY_NAME, window.location.origin);
                url.searchParams.set('nama', nama2);
                url.searchParams.set('date', date);
                url.searchParams.set('start', main.start);
                url.searchParams.set('end', main.end);
                if (excludeKode) {
                    url.searchParams.set('exclude_kode', excludeKode);
                }

                const res = await fetch(url.toString(), {
                    headers: { Accept: 'application/json' },
                });
                if (!res.ok) return;

                const data = await res.json();
                const unavailable = new Set(
                    (Array.isArray(data) ? data : [])
                        .filter(t => t.available === false)
                        .map(t => String(t.kode))
                );

                // Apply ke select KODE tema tambahan
                Array.from(selTema2Kode.options).forEach((opt, idx) => {
                    if (idx === 0 || !opt.value) return;

                    const kode = String(opt.value);

                    // Jangan izinkan kode yang sama dengan tema utama
                    if (excludeKode && kode === excludeKode) {
                        opt.disabled = true;
                        opt.classList.add('unavail');
                        return;
                    }

                    if (unavailable.has(kode)) {
                        opt.disabled = true;
                        opt.classList.add('unavail');
                    } else {
                        opt.disabled = false;
                        opt.classList.remove('unavail');
                    }
                });

                // Apply juga ke select by ID (#tema2_id) berdasarkan data-kode
                if (selTema2Id) {
                    applyTemaAvailabilityToIdSelect(selTema2Id, unavailable, excludeKode);
                }
            } catch (e) {
                // diam saja kalau error, backend tetap validasi
            }
        }

        function renderMainSlots() {
            if (!slotList) return;

            if (!mainSlots.length) {
                slotList.innerHTML =
                    '<p style="opacity:.7">Tidak ada slot tersedia.</p>';
                return;
            }

            slotList.innerHTML = mainSlots
                .map(s => {
                    const id = `slot_main_${s.code}`;
                    return `
                <label class="slot-item ${
                    s.available ? '' : 'unavail'
                }" for="${id}">
                    <input type="radio"
                           name="slot_main"
                           id="${id}"
                           value="${s.code}"
                           data-time="${s.time}"
                           ${s.available ? '' : 'disabled'}>
                    <span>${s.time}</span>
                    <small style="margin-left:auto;opacity:.7">${s.code}</small>
                </label>
            `;
                })
                .join('');
        }

        // ====== Load slot ekstra (addon kategori 1) ======
        async function loadExtraSlots() {
            if (!extraSlotWrapper || !extraSlotList) return;

            const slotAddon = getAddonSlot();
            const date      = inputDate?.value;
            const main      = getSelectedMainSlot();
            const extraMin  = getExtraMinutesFromAddons();

            if (!slotAddon || !extraMin) {
                extraSlots = [];
                extraSlotWrapper.style.display = 'none';
                return;
            }

            if (!date || !main) {
                extraSlotWrapper.style.display = 'block';
                extraSlotList.innerHTML =
                    '<p style="opacity:.7">Pilih tanggal dan slot utama terlebih dahulu.</p>';
                return;
            }

            extraSlotWrapper.style.display = 'block';
            extraSlotList.innerHTML =
                '<p style="opacity:.7">Memuat slot tambahan...</p>';

            try {
                const url = new URL(API_SLOTS, window.location.origin);
                url.searchParams.set('date', date);
                url.searchParams.set('durasi', String(extraMin)); // durasi addon (mis. 60)
                url.searchParams.set('exclude', main.code);       // supaya kode slot utama nggak kepilih
                url.searchParams.set('main_start', main.start);   // <= BARU
                url.searchParams.set('main_end', main.end);       // <= BARU

                const res = await fetch(url.toString(), {
                    headers: { Accept: 'application/json' },
                });
                if (!res.ok) throw new Error('Gagal memuat slot tambahan');

                const data = await res.json();
                extraSlots = Array.isArray(data) ? data : [];
                renderExtraSlots();
            } catch (err) {
                extraSlots = [];
                extraSlotList.innerHTML =
                    '<p style="color:#b91c1c">Gagal memuat slot tambahan. Coba lagi.</p>';
            }
        }

        function renderExtraSlots() {
            if (!extraSlotList) return;

            const main = getSelectedMainSlot();

            if (!extraSlots.length) {
                extraSlotList.innerHTML =
                    '<p style="opacity:.7">Tidak ada slot tambahan tersedia.</p>';
                return;
            }

            extraSlotList.innerHTML = extraSlots
                .map(s => {
                    const id       = `slot_extra_${s.code}`;
                    const disabled =
                        !s.available || (main && s.code === main.code);
                    return `
                <label class="slot-item ${disabled ? 'unavail' : ''}" for="${id}">
                    <input type="radio"
                        name="slot_extra"
                        id="${id}"
                        value="${s.code}"
                        data-time="${s.time}"
                        ${disabled ? 'disabled' : ''}>
                    <span>${s.time}</span>
                    <small style="margin-left:auto;opacity:.7">${s.code}</small>
                </label>
            `;
                })
                .join('');
        }

        // ====== Simpan state per step ======
        function saveStep1() {
            const s1 = {
                nama_cpp: (namaCpp.value || '').trim(),
                email_cpp: (emailCpp.value || '').trim(),
                phone_cpp: (phoneCpp.value || '').trim(),
                alamat_cpp: (alamatCpp.value || '').trim(),
                nama_cpw: (namaCpw.value || '').trim(),
                email_cpw: (emailCpw.value || '').trim(),
                phone_cpw: (phoneCpw.value || '').trim(),
                alamat_cpw: (alamatCpw.value || '').trim(),
            };

            if (
                !s1.nama_cpp ||
                !s1.phone_cpp ||
                !s1.nama_cpw ||
                !s1.phone_cpw
            ) {
                throw new Error(
                    'Nama & No. Telp CPP/CPW wajib diisi.'
                );
            }

            state.step1 = s1;
        }

        function resolveTemaUtama() {
            let tema_id   = '';
            let tema_nama = '';
            let tema_kode = '';

            if (selTemaId && selTemaId.value) {
                const opt = selTemaId.selectedOptions[0];
                tema_id   = selTemaId.value;
                tema_nama = opt.dataset.nama || '';
                tema_kode = opt.dataset.kode || '';
            } else if (selTemaKode && selTemaKode.value) {
                const opt = selTemaKode.selectedOptions[0];
                tema_kode = selTemaKode.value;
                tema_nama =
                    opt.dataset.nama || selTemaNama.value || '';
                tema_id = opt.dataset.id || '';
            } else if (selTemaNama && selTemaNama.value) {
                tema_nama = selTemaNama.value;
            }

            return { tema_id, tema_nama, tema_kode };
        }

        function resolveTemaAddon() {
            let tema2_id   = '';
            let tema2_nama = '';
            let tema2_kode = '';

            if (selTema2Id && selTema2Id.value) {
                const opt = selTema2Id.selectedOptions[0];
                tema2_id   = selTema2Id.value;
                tema2_nama = opt.dataset.nama || '';
                tema2_kode = opt.dataset.kode || '';
            } else if (selTema2Kode && selTema2Kode.value) {
                const opt = selTema2Kode.selectedOptions[0];
                tema2_kode = selTema2Kode.value;
                tema2_nama =
                    opt.dataset.nama || selTema2Nama.value || '';
                tema2_id = opt.dataset.id || '';
            } else if (selTema2Nama && selTema2Nama.value) {
                tema2_nama = selTema2Nama.value;
            }

            return { tema2_id, tema2_nama, tema2_kode };
        }

        function saveStep2() {
            const pkgId = selPackage?.value || '';
            const date  = inputDate?.value || '';
            const style = selStyle?.value || '';
            const main  = getSelectedMainSlot();

            if (!pkgId) throw new Error('Pilih paket terlebih dahulu.');
            if (!date)
                throw new Error('Pilih tanggal photoshoot.');
            if (!style)
                throw new Error('Pilih style (Hijab/HairDo).');
            if (!main)
                throw new Error('Pilih salah satu slot utama.');

            const { tema_id, tema_nama, tema_kode } =
                resolveTemaUtama();

            const slotAddon = getAddonSlot();
            const temaAddon = getAddonTema();

            const extraSlot = getSelectedExtraSlot();
            const extraMin  = getExtraMinutesFromAddons();

            if (slotAddon && (!extraSlot || !extraSlot.code)) {
                throw new Error(
                    'Addon slot waktu dipilih, silakan pilih slot tambahan.'
                );
            }

            const tema2 = resolveTemaAddon();

            if (temaAddon && !tema2.tema2_kode) {
                throw new Error(
                    'Addon tema baju dipilih, silakan pilih tema baju tambahan.'
                );
            }

            if (
                temaAddon &&
                tema2.tema2_kode &&
                tema_kode &&
                tema2.tema2_kode === tema_kode
            ) {
                throw new Error(
                    'Tema tambahan tidak boleh sama dengan tema utama.'
                );
            }

            state.step2 = {
                package_id: pkgId,
                photoshoot_date: date,
                style,
                slot_code: main.code,
                photoshoot_slot: main.time,
                start_time: main.start,
                end_time: main.end,
                tema_id,
                tema_nama,
                tema_kode,
                wedding_date: weddingInput?.value || '',
                notes: (notesInput?.value || '').trim(),

                // Addon slot
                extra_slot_code: extraSlot ? extraSlot.code : '',
                extra_photoshoot_slot: extraSlot
                    ? extraSlot.time
                    : '',
                extra_start_time: extraSlot
                    ? extraSlot.start
                    : '',
                extra_end_time: extraSlot ? extraSlot.end : '',
                extra_minutes: extraMin || 0,

                // Addon tema
                tema2_id: tema2.tema2_id,
                tema2_nama: tema2.tema2_nama,
                tema2_kode: tema2.tema2_kode,

                // Daftar addon dipilih
                addons: getSelectedAddons(),
            };
        }

        function saveStep3() {
            state.step3 = {
                ig_cpp: (igCpp?.value || '').trim(),
                ig_cpw: (igCpw?.value || '').trim(),
                tiktok_cpp: (ttCpp?.value || '').trim(),
                tiktok_cpw: (ttCpw?.value || '').trim(),
                sosmed_lain: null,
            };
        }

        // ====== Summary & hidden inputs ======
        function renderSummary() {
            if (!summaryBox) return;

            const s1 = state.step1 || {};
            const s2 = state.step2 || {};
            const s3 = state.step3 || {};

            const pkgText =
                selPackage?.selectedOptions[0]?.textContent.trim() ||
                '-';

            let temaText = '-';
            if (s2.tema_kode) {
                temaText = `${s2.tema_nama || '-'} (${
                    s2.tema_kode
                })`;
            } else if (s2.tema_nama) {
                temaText = s2.tema_nama;
            }

            let tema2Text = '-';
            if (s2.tema2_kode) {
                tema2Text = `${s2.tema2_nama || '-'} (${
                    s2.tema2_kode
                })`;
            } else if (s2.tema2_nama) {
                tema2Text = s2.tema2_nama;
            }

            const selectedAddons = s2.addons || [];
            const addonTotal     = selectedAddons.reduce(
                (sum, a) => sum + a.harga,
                0
            );

            const addonListHtml = selectedAddons.length
                ? `<ul style="margin:6px 0 0 18px;padding:0">
                   ${selectedAddons
                       .map(
                           a => `
                       <li>${a.name} <span style="opacity:.7">(Kategori ${a.kategori})</span></li>
                   `
                       )
                       .join('')}
               </ul>`
                : '<p style="margin:6px 0 0;opacity:.7">Tidak ada addon dipilih.</p>';

            const extraSlotInfo = s2.extra_slot_code
                ? `${s2.extra_photoshoot_slot} (${s2.extra_slot_code})`
                : '-';

            summaryBox.innerHTML = `
            <div class="grid-2">
                <div class="summary-card">
                    <h4>Identitas</h4>
                    <p><strong>CPP:</strong> ${s1.nama_cpp} | ${
                s1.phone_cpp
            } | ${s1.email_cpp || '-'} | ${
                s1.alamat_cpp || '-'
            }</p>
                    <p><strong>CPW:</strong> ${s1.nama_cpw} | ${
                s1.phone_cpw
            } | ${s1.email_cpw || '-'} | ${
                s1.alamat_cpw || '-'
            }</p>
                </div>
                <div class="summary-card">
                    <h4>Detail Booking</h4>
                    <p><strong>Paket:</strong> ${pkgText}</p>
                    <p><strong>Tanggal:</strong> ${
                        s2.photoshoot_date
                    }</p>
                    <p><strong>Slot Utama:</strong> ${
                        s2.photoshoot_slot
                    } (${s2.slot_code})</p>
                    <p><strong>Slot Tambahan:</strong> ${extraSlotInfo}</p>
                    <p><strong>Style:</strong> ${s2.style}</p>
                    <p><strong>Tema Utama:</strong> ${temaText}</p>
                    <p><strong>Tema Tambahan:</strong> ${tema2Text}</p>
                    <p><strong>Wedding Date:</strong> ${
                        s2.wedding_date || '-'
                    }</p>
                    <p><strong>Notes:</strong> ${
                        s2.notes || '-'
                    }</p>
                    <p><strong>Extra Minutes:</strong> ${
                        s2.extra_minutes || 0
                    } menit</p>
                </div>
            </div>
            <div class="summary-card" style="margin-top:12px">
                <h4>Sosial Media</h4>
                <p><strong>CPP:</strong> IG ${
                    s3.ig_cpp || '-'
                } | TikTok ${s3.tiktok_cpp || '-'}</p>
                <p><strong>CPW:</strong> IG ${
                    s3.ig_cpw || '-'
                } | TikTok ${s3.tiktok_cpw || '-'}</p>
            </div>
            <div class="summary-card" style="margin-top:12px">
                <h4>Addon</h4>
                ${addonListHtml}
                <p style="margin-top:8px"><strong>Total Addon (estimasi):</strong> Rp ${addonTotal.toLocaleString(
                    'id-ID'
                )}</p>
            </div>
        `;
        }

        function injectHiddenInputs() {
            if (!hiddenBag) return;
            hiddenBag.innerHTML = '';

            const addHidden = (name, value) => {
                const input = document.createElement('input');
                input.type  = 'hidden';
                input.name  = name;
                input.value = value ?? '';
                hiddenBag.appendChild(input);
            };

            const s1 = state.step1 || {};
            const s2 = state.step2 || {};
            const s3 = state.step3 || {};

            // Step 1
            Object.entries(s1).forEach(([k, v]) => addHidden(k, v));

            // Step 2 - utama
            addHidden('package_id', s2.package_id);
            addHidden('photoshoot_date', s2.photoshoot_date);
            addHidden('slot_code', s2.slot_code);
            addHidden('photoshoot_slot', s2.photoshoot_slot);
            addHidden('start_time', s2.start_time);
            addHidden('end_time', s2.end_time);
            addHidden('style', s2.style);
            addHidden('tema_id', s2.tema_id);
            addHidden('tema_nama', s2.tema_nama);
            addHidden('tema_kode', s2.tema_kode);
            addHidden('wedding_date', s2.wedding_date);
            addHidden('notes', s2.notes);

            // Extra slot
            addHidden('extra_slot_code', s2.extra_slot_code);
            addHidden(
                'extra_photoshoot_slot',
                s2.extra_photoshoot_slot
            );
            addHidden('extra_start_time', s2.extra_start_time);
            addHidden('extra_end_time', s2.extra_end_time);
            addHidden('extra_minutes', s2.extra_minutes);

            // Tema tambahan
            addHidden('tema2_id', s2.tema2_id);
            addHidden('tema2_nama', s2.tema2_nama);
            addHidden('tema2_kode', s2.tema2_kode);

            // Step 3
            addHidden('ig_cpp', s3.ig_cpp);
            addHidden('ig_cpw', s3.ig_cpw);
            addHidden('tiktok_cpp', s3.tiktok_cpp);
            addHidden('tiktok_cpw', s3.tiktok_cpw);
            addHidden(
                'sosmed_lain',
                s3.sosmed_lain ? JSON.stringify(s3.sosmed_lain) : ''
            );

            // Addons[]
            (s2.addons || []).forEach(a =>
                addHidden('addons[]', a.id)
            );
        }

        // ====== Event binding ======
        const debounce = (fn, ms = 300) => {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), ms);
            };
        };

        const debouncedLoadMainSlots  = debounce(loadMainSlots, 250);
        const debouncedLoadExtraSlots = debounce(
            loadExtraSlots,
            250
        );

        selPackage &&
            selPackage.addEventListener('change', () => {
                debouncedLoadMainSlots();
                debouncedLoadExtraSlots();
            });

        inputDate &&
            inputDate.addEventListener('change', () => {
                debouncedLoadMainSlots();
                debouncedLoadExtraSlots();
                refreshTemaKodeUtama();
                refreshTemaKodeTambahan();
            });
        wizard.addEventListener('change', e => {
            const t = e.target;

            // Slot utama berubah -> refresh extra slot
            if (t.name === 'slot_main') {
                debouncedLoadExtraSlots();
                refreshTemaKodeUtama();
                refreshTemaKodeTambahan();
            }

            // Addon berubah
            if (
                t.classList &&
                t.classList.contains('addon-check')
            ) {
                normalizeAddonSlotSelection(t);

                const slotAddon = getAddonSlot();
                const temaAddon = getAddonTema();

                // Panel extra slot
                if (slotAddon) {
                    extraSlotWrapper.style.display = 'block';
                    debouncedLoadExtraSlots();
                } else if (extraSlotWrapper) {
                    extraSlotWrapper.style.display = 'none';
                    extraSlotList.innerHTML =
                        '<p style="opacity:.7">\
                    Pilih addon "Tambah Slot Waktu" & slot utama terlebih dahulu.\
                </p>';
                    const pickedExtra = $(
                        'input[name="slot_extra"]:checked',
                        wizard
                    );
                    if (pickedExtra) pickedExtra.checked = false;
                }

                // Panel extra tema
                if (temaAddon && extraTemaWrapper) {
                    extraTemaWrapper.style.display = 'block';
                } else if (extraTemaWrapper) {
                    extraTemaWrapper.style.display = 'none';
                    if (selTema2Nama) selTema2Nama.value = '';
                    if (selTema2Kode) {
                        selTema2Kode.value    = '';
                        selTema2Kode.disabled = true;
                    }
                    if (selTema2Id) selTema2Id.value = '';
                }

                if (currentStep === steps.length - 1) {
                    renderSummary();
                }
            }
        });

        // Tema utama: filter kode berdasarkan nama
       selTemaNama &&
        selTemaNama.addEventListener('change', () => {
            refreshTemaKodeUtama();
        });

        // Tema tambahan: filter kode berdasarkan nama & exclude kode utama
        selTema2Nama &&
        selTema2Nama.addEventListener('change', () => {
            refreshTemaKodeTambahan();
        });

        // Kalau tema utama diganti dan panel tema tambahan aktif, update juga filter kode tema2
        [selTemaKode, selTemaId].forEach(el => {
            if (!el) return;
            el.addEventListener('change', () => {
                if (
                    !extraTemaWrapper ||
                    extraTemaWrapper.style.display === 'none'
                )
                    return;
                refreshTemaKodeTambahan();
            });
        });

        // Navigasi Next/Prev
        nextBtn &&
            nextBtn.addEventListener('click', () => {
                try {
                    if (currentStep === 0) {
                        saveStep1();
                        setStep(1);
                    } else if (currentStep === 1) {
                        saveStep2();
                        setStep(2);
                    } else if (currentStep === 2) {
                        saveStep3();
                        setStep(3);
                    } else if (currentStep === 3) {
                        injectHiddenInputs();
                        $('#finalForm', wizard)?.submit();
                    }
                } catch (err) {
                    showError(err.message);
                }
            });

        prevBtn &&
            prevBtn.addEventListener('click', () => {
                setStep(currentStep - 1);
            });

        submitBtn &&
            submitBtn.addEventListener('click', () => {
                injectHiddenInputs();
                $('#finalForm', wizard)?.submit();
            });

        // Init
        setStep(0);
    })();
});
