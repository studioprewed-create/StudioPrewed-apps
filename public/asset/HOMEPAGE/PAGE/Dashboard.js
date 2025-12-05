 document.addEventListener("DOMContentLoaded", function () {
  const mapEl = document.getElementById('map');
  if (mapEl && window.L) {
    const map = L.map(mapEl).setView([-6.8845402, 107.6135556], 17);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    L.marker([-6.8845402, 107.6135556]).addTo(map)
      .bindPopup("<b>Studio Prewed</b><br>Jl. Ir. H. Juanda No.185, Simpang, Dago, Kecamatan Coblong, Kota Bandung, Jawa Barat 40135")
      .openPopup();
  }
    /* ===== Garis bawah menu header ===== */
    const navLinks = document.querySelectorAll("nav a");
    navLinks.forEach(link => {
        link.addEventListener("click", function() {
            navLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Header scroll effect
        const header = document.getElementById('siteHeader');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            });

    const carouselItems = document.querySelectorAll('.carousel-item');
    const carouselControls = document.querySelectorAll('.carousel-control'); // boleh kosong
    let currentSlide = 0;
    let slideInterval;

    function showSlide(index) {
        if (!carouselItems.length) return; // kalau tidak ada slide, keluar
        carouselItems.forEach(el => el.classList.remove('active'));
        carouselItems[index].classList.add('active');

        // update dot hanya jika memang ada
        if (carouselControls.length) {
        carouselControls.forEach(el => el.classList.remove('active'));
        carouselControls[index]?.classList.add('active');
        }
        currentSlide = index;
    }

    function nextSlide() {
        showSlide((currentSlide + 1) % carouselItems.length);
    }

    function startCarousel() {
        if (carouselItems.length <= 1) return; // 1 slide → jangan auto-rotate
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    }

    if (carouselControls.length) {
        carouselControls.forEach((control, i) => {
        control.addEventListener('click', () => {
            clearInterval(slideInterval);
            showSlide(i);
            startCarousel();
        });
        });
    }

    showSlide(0);
    startCarousel();

    const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            item.querySelector('.faq-question').addEventListener('click', () => {
                faqItems.forEach(other => { if (other !== item) other.classList.remove('active'); });
                item.classList.toggle('active');
            });
        });

        /* ===== Gallery carousel ===== */
        const galleryTrack = document.querySelector('.gallery-track');
        const galleryItems = document.querySelectorAll('.gallery-item');
        const prevBtn = document.querySelector('.nav-btn.prev');
        const nextBtn = document.querySelector('.nav-btn.next');
        let currentCenter = 2;

        function updateGallery() {
            galleryItems.forEach((item, index) => {
                item.classList.toggle('center', index === currentCenter);
            });
            const centerItem = galleryItems[currentCenter];
            galleryTrack.scrollLeft = centerItem.offsetLeft - (galleryTrack.offsetWidth / 2) + (centerItem.offsetWidth / 2);
        }

        prevBtn?.addEventListener('click', () => {
            if (currentCenter > 0) { currentCenter--; updateGallery(); }
        });

        nextBtn?.addEventListener('click', () => {
            if (currentCenter < galleryItems.length - 1) { currentCenter++; updateGallery(); }
        });

        galleryItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                currentCenter = index;
                updateGallery();
            });
        });
        updateGallery();

        /* ===== Service card hover/touch effect ===== */
        document.querySelectorAll('.service-card').forEach(card => {
            const content = card.querySelector('.service-content');
            card.addEventListener('mouseenter', () => {
                content.style.background = 'linear-gradient(to top, rgba(0, 0, 0, 0.95), transparent 70%)';
            });
            card.addEventListener('mouseleave', () => {
                content.style.background = 'linear-gradient(to top, rgba(0, 0, 0, 0.95), transparent 70%)';
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
                    card.classList.remove('active');
                    card.querySelector('.service-content').style.background =
                        'linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent 70%)';
                });
            }
        });

        /* ===== FAQ toggle ===== */
        

        /* ===== Smooth scrolling ===== */
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', e => {
                e.preventDefault();
                const targetId = anchor.getAttribute('href');
                if (targetId !== '#') {
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        const headerHeight = document.querySelector('header').offsetHeight;
                        const targetPosition = targetElement.offsetTop - headerHeight;
                        window.scrollTo({ top: targetPosition, behavior: 'smooth' });
                    }
                }
            });
        });

        /* ===== Scroll animation ===== */
        const animatedEls = document.querySelectorAll('.service-card, .gallery-item, .stat-item, .review-card, .social-card');
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

        /* ===== Booking button ===== */
        const bookingBtn = document.querySelector('.booking-btn');
        bookingBtn?.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
                alert('Redirecting to booking page...');
            }, 200);
        });

        /* ===== Floating middlebar ===== */
        const middlebar = document.querySelector('.middlebar-container');
        if (middlebar) {
            function addFloatingEffect() {
                middlebar.style.transition = 'transform 3s ease-in-out';
                middlebar.style.transform = 'translateY(-5px)';
                setTimeout(() => { middlebar.style.transform = 'translateY(0)'; }, 1500);
            }
            addFloatingEffect();
            setInterval(addFloatingEffect, 4000);
        }
    });

        // Stats counter animation for about section
        const aboutStatBoxes = document.querySelectorAll('.about-stat-box');
        
        const aboutObserverOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const aboutObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statBox = entry.target;
                    const numberElement = statBox.childNodes[0];
                    
                    if (numberElement.nodeType === 3) { // Text node
                        const targetNumber = parseInt(numberElement.textContent);
                        let currentNumber = 0;
                        const increment = targetNumber / 50;
                        const duration = 1500;
                        const stepTime = duration / 50;
                        
                        const timer = setInterval(() => {
                            currentNumber += increment;
                            if (currentNumber >= targetNumber) {
                                currentNumber = targetNumber;
                                clearInterval(timer);
                            }
                            numberElement.textContent = Math.floor(currentNumber);
                        }, stepTime);
                    }
                }
            });
        }, aboutObserverOptions);
        
        aboutStatBoxes.forEach(box => {
            aboutObserver.observe(box);
        });

        // Add some CSS for the ripple effect
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


        (function(){
    // buka modal detail
    document.querySelectorAll('[data-open]').forEach(btn => {
        btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const sel = btn.getAttribute('data-open');
        const modal = document.querySelector(sel);
        if (modal) {
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }
        });
    });

    // tutup modal detail
    function closeDetail(modal){
        modal.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    document.addEventListener('click', (e) => {
        if (e.target.matches('[data-close-detail]')) {
        const modal = e.target.closest('.pkg-modal') || document.querySelector('.pkg-modal.is-open');
        if (modal) closeDetail(modal);
        }
    });

    // klik di luar panel (backdrop)
    document.querySelectorAll('.pkg-modal__backdrop').forEach(bd => {
        bd.addEventListener('click', () => {
        const modal = bd.closest('.pkg-modal');
        if (modal) closeDetail(modal);
        });
    });

    // esc
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
        document.querySelectorAll('.pkg-modal.is-open').forEach(m => closeDetail(m));
        }
    });
    })();

            (function(){
            const wrap = document.getElementById('promoCarousel');
            if (!wrap) return;

            const slides = Array.from(wrap.querySelectorAll('.promo-slide'));
            const dots   = Array.from(wrap.querySelectorAll('.promo-dot'));
            const interval = parseInt(wrap.dataset.interval || '4000', 10);

            let idx = 0, timer;

            function show(n){
                slides[idx]?.classList.remove('is-active');
                dots[idx]?.classList.remove('is-active');
                idx = (n + slides.length) % slides.length;
                slides[idx]?.classList.add('is-active');
                dots[idx]?.classList.add('is-active');
            }

            function next(){ show(idx + 1); }
            function prev(){ show(idx - 1); } // kalau nanti mau tambahkan tombol prev

            function start(){ stop(); timer = setInterval(next, interval); }
            function stop(){ if (timer) clearInterval(timer); }

            // klik dot
            dots.forEach((d, i)=> d.addEventListener('click', ()=>{ show(i); start(); }));

            // pause saat hover (opsional)
            wrap.addEventListener('mouseenter', stop);
            wrap.addEventListener('mouseleave', start);

            // init
            if (slides.length) {
                show(0);
                start();
            }
            })();

            document.querySelectorAll('a[href^="#"]').forEach(a=>{
            a.addEventListener('click', e=>{
                const href = a.getAttribute('href');
                if (!href || href==='#') return;

                // Apakah anchor menargetkan slide di dalam #tSlider?
                const slideTarget = document.querySelector('#tSlider .slides ' + href);
                if (slideTarget) {
                // Kita handle sendiri: jangan biarkan default jump
                e.preventDefault();

                // Update hash (akan memicu hashchange → slider akan goTo)
                if (location.hash !== href) {
                    location.hash = href;
                } else {
                    // Kalau hash sama, secara manual trigger event supaya slider tetap goTo
                    window.dispatchEvent(new Event('hashchange'));
                }

                // Scroll halus ke section #testi (dengan offset header)
                const testiSec = document.getElementById('testi');
                if (testiSec) {
                    const header = document.querySelector('header');
                    const offset = header ? header.offsetHeight : 0;
                    const y = testiSec.getBoundingClientRect().top + window.scrollY - offset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
                return;
                }

                // Anchor biasa → smooth scroll ke target
                const target = document.querySelector(href);
                if (!target) return;
                e.preventDefault();
                const header = document.querySelector('header');
                const offset = header ? header.offsetHeight : 0;
                const y = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({top:y, behavior:'smooth'});
            });
            });

            (function(){
            const slider = document.getElementById('tSlider'); if(!slider) return;
            const track  = slider.querySelector('.slides');
            const items  = Array.from(slider.querySelectorAll('.t-item'));
            const btns   = slider.querySelectorAll('.t-btn');
            if (!items.length) return;

            let idx = 0;
            let timer = null;

            function applyTransform() {
                track.style.transform = `translateX(-${idx * 100}%)`;
                items.forEach((el, i) => el.setAttribute('aria-hidden', i !== idx));
            }

            function go(n, {updateHash=false} = {}) {
                idx = (n + items.length) % items.length;
                applyTransform();
                if (updateHash) {
                const id = items[idx].id || `testi-${idx+1}`;
                // update hash tanpa menggeser halaman
                history.replaceState(null, '', `#${id}`);
                }
            }

            function goById(fragment) {
                if (!fragment) return false;
                const id = fragment.replace(/^#/, '');
                const pos = items.findIndex(el => el.id === id);
                if (pos >= 0) { go(pos); return true; }
                return false;
            }

            // tombol prev/next
            btns.forEach(btn=>{
                btn.addEventListener('click', ()=> {
                const dir = parseInt(btn.dataset.dir || '1', 10);
                go(idx + dir, {updateHash:true});
                });
            });

            // autoplay
            function startAuto(){ stopAuto(); timer = setInterval(()=>go(idx+1, {updateHash:true}), 5000); }
            function stopAuto(){ if(timer) { clearInterval(timer); timer=null; } }
            slider.addEventListener('mouseenter', stopAuto);
            slider.addEventListener('mouseleave', startAuto);

            // inisialisasi: jika URL punya hash ke slide, langsung ke sana
            if (!goById(location.hash)) {
                applyTransform(); // posisi awal 0
            }

            // saat hash berubah (klik link #testi-3 dsb), lompat ke slide
            window.addEventListener('hashchange', ()=>{
                goById(location.hash);
            });

            startAuto();
            })();

           (function(){
            const PAGE_SIZE = 20;

            const section   = document.getElementById('reviewsSection');
            const grid      = document.getElementById('reviewsGrid');
            const openBtn   = document.getElementById('btnOpenReviews');
            const hideBtn   = document.getElementById('btnHideReviews');
            const moreBtn   = document.getElementById('btnLoadMoreReviews');

            if (!section || !grid || !openBtn || !hideBtn || !moreBtn) return;

            const cards = Array.from(grid.querySelectorAll('.review-card'));
            let shown = 0;

            // helper
            function hideAllCards(){
                cards.forEach(c => c.hidden = true);
                shown = 0;
            }
            function updateMoreBtn(){
                const remaining = Math.max(cards.length - shown, 0);
                // label tetap "Selengkapnya" tanpa angka
                moreBtn.textContent = 'Selengkapnya';
                moreBtn.hidden = remaining <= 0;
            }

            function showNextChunk(){
                const end = Math.min(shown + PAGE_SIZE, cards.length);
                for (let i = shown; i < end; i++) cards[i].hidden = false;
                shown = end;
                updateMoreBtn();
            }

            function openSection(){
                section.hidden = false;
                openBtn.hidden = true;
                hideBtn.hidden = false;
                openBtn.setAttribute('aria-expanded', 'true');

                hideAllCards();
                showNextChunk();   // tampilkan 20 pertama
                // fokus geser ke section (opsional)
                try { section.scrollIntoView({behavior:'smooth', block:'start'}); } catch(e){}
            }

            function collapseSection(){
                section.hidden = true;
                openBtn.hidden = false;
                hideBtn.hidden = true;
                openBtn.setAttribute('aria-expanded', 'false');

                hideAllCards();
                updateMoreBtn();
                // scroll balik ke tombol open
                try { openBtn.scrollIntoView({behavior:'smooth', block:'center'}); } catch(e){}
            }

            // init
            hideAllCards();
            updateMoreBtn(); // supaya state tombol benar saat awal (meski section hidden)

            // events
            openBtn.addEventListener('click',  openSection);
            hideBtn.addEventListener('click',  collapseSection);
            moreBtn.addEventListener('click',  showNextChunk);
            })();


            (() => {
            'use strict';
            const prefersReduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
            const pointerFine = matchMedia('(pointer: fine)').matches;
            const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

            /* ======================================================
                ✦ Lazy Loading & Optimization
            ====================================================== */
            $$('#about img, #portrait-services img, #whatWeDo img').forEach(img => {
                img.loading = 'lazy';
                img.decoding = 'async';
                img.fetchPriority = 'low';
            });

            /* ======================================================
                ✦ Reveal Animation (fade-up & staggered)
            ====================================================== */
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

            /* ======================================================
                ✦ Tilt + Glare Hover (Cards & Portrait)
            ====================================================== */
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
                    card.style.transform = `perspective(900px) rotateX(${tx.toFixed(2)}deg) rotateY(${ty.toFixed(2)}deg)`;
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
                    glare.style.background = `radial-gradient(circle at ${px}% ${py}%, rgba(255,195,55,.35), rgba(255,255,255,0) 45%)`;
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

            /* ======================================================
                ✦ Floating Stats Counter (Subtle)
            ====================================================== */
            const counters = $$('#about .stat-number-mosaic');
            counters.forEach(el => {
                const target = parseInt(el.textContent.replace(/\D/g, ''), 10) || 0;
                const duration = 1800;
                let start = 0;

                const update = ts => {
                if (!el.startTime) el.startTime = ts;
                const progress = Math.min((ts - el.startTime) / duration, 1);
                el.textContent = Math.floor(progress * target).toLocaleString() + (el.textContent.match(/\D+/) || '');
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

            })();

            const header = document.getElementById('siteHeader');
  window.addEventListener('scroll', ()=> {
    window.scrollY > 100 ? header.classList.add('scrolled') : header.classList.remove('scrolled');
  });

  // Nav underline active
  document.querySelectorAll("nav a").forEach(a=>{
    a.addEventListener("click", function(){
      document.querySelectorAll("nav a").forEach(l=>l.classList.remove("active"));
      this.classList.add("active");
    });
  });

  // Middlebar floating effect
  document.addEventListener('DOMContentLoaded', function(){
    const middlebar = document.querySelector('.middlebar-container');
    function floatEf(){ middlebar.style.transition='transform 3s ease-in-out'; middlebar.style.transform='translateY(-5px)'; setTimeout(()=> middlebar.style.transform='translateY(0)',1500); }
    floatEf(); setInterval(floatEf, 4000);
  });

  // Filter titles
  const filterTitles = {
    'all':        { title:'All Sessions',       description:'Explore our complete collection of professional photography sessions.' },
    'prewed':     { title:'Prewed Session',     description:'A pre-wedding photoshoot designed to capture the love story of a couple before their big day.' },
    'family':     { title:'Family Session',     description:'Warm, joyful portraits that preserve authentic moments and togetherness.' },
    'maternity':  { title:'Maternity Shoot',   description:'Graceful images celebrating motherhood and new beginnings.' },
    'postwedding':{ title:'Post Wedding',      description:'Relaxed, artistic sessions after the wedding day with creative concepts.' },
    'beauty':     { title:'Beauty Shoot',      description:'Elegant portraits focusing on style, fashion, and confidence.' },
    'birthday':   { title:'Birthday Session',  description:'Vibrant shoots to celebrate special milestones and personalities.' }
  };

  // Filter behavior
  (function(){
  const section      = document.querySelector('.gallery-section');
  const btns         = document.querySelectorAll('.filter-btn');
  const items        = document.querySelectorAll('.gallery-item');
  const dynamicTitle = document.getElementById('dynamicTitle');

  // Kalau elemen tidak ada (lagi di halaman lain), jangan apa-apa
  if (!section || !btns.length || !items.length || !dynamicTitle) return;

  function applyFilter(filterKey) {
    if (!filterTitles[filterKey]) filterKey = 'all';

    // Update active button
    btns.forEach(b => {
      const f = b.getAttribute('data-filter') || 'all';
      b.classList.toggle('active', f === filterKey);
    });

    // Update title + description
    const data = filterTitles[filterKey];
    dynamicTitle.classList.remove('active');

    setTimeout(() => {
      dynamicTitle.querySelector('h2').textContent = data.title;
      dynamicTitle.querySelector('p').textContent  = data.description;
      dynamicTitle.classList.add('active');
    }, 10);

    // Show/hide gallery items
    items.forEach(it => {
      const cat = it.getAttribute('data-category') || 'all';
      if (filterKey === 'all' || cat === filterKey) {
        it.style.display = 'block';
        it.style.opacity = '0';
        it.style.transform = 'scale(0.96)';
        requestAnimationFrame(() => {
          it.style.opacity = '1';
          it.style.transform = 'scale(1)';
        });
      } else {
        it.style.opacity = '0';
        it.style.transform = 'scale(0.9)';
        setTimeout(() => { it.style.display = 'none'; }, 200);
      }
    });
  }

  // Klik tombol filter
  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      const filter = btn.getAttribute('data-filter') || 'all';
      applyFilter(filter);
      // Optional: update URL query supaya bisa di-share
      const url = new URL(window.location.href);
      if (filter === 'all') {
        url.searchParams.delete('category');
      } else {
        url.searchParams.set('category', filter);
      }
      window.history.replaceState({}, '', url.toString());
    });
  });

  // Tentukan filter awal:
  // 1) dari URL ?category=
  // 2) kalau ga ada → dari data-initial-filter di section
  // 3) fallback 'all'
  let initialFilter = 'all';

  try {
    const urlParams = new URLSearchParams(window.location.search);
    const fromQuery = urlParams.get('category');
    if (fromQuery && filterTitles[fromQuery]) {
      initialFilter = fromQuery;
    } else {
      const fromAttr = section.getAttribute('data-initial-filter');
      if (fromAttr && filterTitles[fromAttr]) {
        initialFilter = fromAttr;
      }
    }
  } catch (e) {
    // kalau URLSearchParams gak support, ya sudah fallback 'all'
  }

  applyFilter(initialFilter);
})();

  (function(){
    const modal = document.getElementById('imageModal');
    const expanded = document.getElementById('expandedImage');
    document.querySelectorAll('.gallery-item').forEach(card=>{
      card.addEventListener('click', ()=>{
        expanded.src = card.dataset.img;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
      });
    });
    modal.querySelector('.close').addEventListener('click', ()=>{ modal.style.display='none'; document.body.style.overflow=''; });
    window.addEventListener('click', (e)=>{ if(e.target===modal){ modal.style.display='none'; document.body.style.overflow=''; }});
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape'){ modal.style.display='none'; document.body.style.overflow=''; }});
  })();