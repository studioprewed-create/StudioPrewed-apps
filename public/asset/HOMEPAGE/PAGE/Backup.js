function expandImage(src) {
    const modal = document.getElementById('imageModal');
    const img   = document.getElementById('expandedImage');
    if (!modal || !img) return;

    img.src = src;
    modal.style.display = 'block';

    const wa = document.querySelector('.wa-float');
    const sectionFloat = document.querySelector('.section-float');
    if (wa) wa.classList.add('hide');
    if (sectionFloat)
        sectionFloat.classList.add('hide');
}

document.addEventListener('DOMContentLoaded', function () {
    const $  = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

    document.querySelectorAll('.contenteditable[data-section="addon"]').forEach(el => {
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

                enableModalBackClose(
                    modal,
                    () => closeDetail(modal)
                );

                const wa = document.querySelector('.wa-float');
                if (wa) wa.classList.add('hide');

                const sectionFloat =
                    document.querySelector('.section-float');

                if (sectionFloat)
                    sectionFloat.classList.add('hide');
            }
        });
    });
    updateGallery();

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
    (function () {
        document.querySelectorAll('[data-open]').forEach(btn => {
            btn.addEventListener('click', e => {
                e.stopPropagation();
                const sel   = btn.getAttribute('data-open');
                const modal = document.querySelector(sel);
                if (modal) {
                    modal.classList.add('is-open');
                    document.body.style.overflow = 'hidden';

                    enableModalBackClose(
                        modal,
                        () => closeDetail(modal)
                    );

                    const wa = document.querySelector('.wa-float');
                    if (wa) wa.classList.add('hide');

                    const sectionFloat =
                        document.querySelector('.section-float');

                    if (sectionFloat)
                        sectionFloat.classList.add('hide');
                }
            });
        });

        function closeDetail(modal) {
            modal.classList.remove('is-open');
            document.body.style.overflow = '';

            const wa = document.querySelector('.wa-float');
            const sectionFloat = document.querySelector('.section-float');
            if (wa) wa.classList.remove('hide');

            if (sectionFloat)sectionFloat.classList.remove('hide');
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

    (function () {

    const filterWrap = document.querySelector('.gallery-filter');
    if (!filterWrap) return;

    const buttons = filterWrap.querySelectorAll('.filter-btn');
    const items   = document.querySelectorAll('.gallery-card');

        let isFiltering = false;

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {

                if (isFiltering) return;
                isFiltering = true;

                // ACTIVE BUTTON
                buttons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;

                items.forEach(item => {
                    const category = item.dataset.category;

                    if (filter === 'all' || category === filter) {

                        // tampilkan lagi
                        item.classList.remove('hidden');

                        setTimeout(() => {
                            item.classList.remove('hide');
                        }, 10);

                    } else {

                        // animasi keluar
                        item.classList.add('hide');

                        setTimeout(() => {
                            item.classList.add('hidden');
                        }, 300); // sesuai CSS transition
                    }
                });

                // unlock setelah animasi selesai
                setTimeout(() => {
                    isFiltering = false;
                }, 350);

            });
        });

    })();
});
