document.addEventListener("DOMContentLoaded", function () {
  /* ================================
     MAP (Leaflet) – optional
     ================================ */
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

  /* ================================
     HEADER: active link + scroll
     ================================ */
  const header = document.getElementById('siteHeader');
  const navLinks = document.querySelectorAll("nav a");

  if (navLinks.length) {
    navLinks.forEach(link => {
      link.addEventListener("click", function () {
        navLinks.forEach(l => l.classList.remove("active"));
        this.classList.add("active");
      });
    });
  }

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

  /* ================================
     HERO CAROUSEL (.carousel-item)
     ================================ */
  (function initHeroCarousel() {
    const carouselItems = document.querySelectorAll('.carousel-item');
    const carouselControls = document.querySelectorAll('.carousel-control');
    if (!carouselItems.length) return;

    let currentSlide = 0;
    let slideInterval;

    function showSlide(index) {
      if (!carouselItems.length) return;
      carouselItems.forEach(el => el.classList.remove('active'));
      carouselItems[index].classList.add('active');

      if (carouselControls.length) {
        carouselControls.forEach(el => el.classList.remove('active'));
        if (carouselControls[index]) {
          carouselControls[index].classList.add('active');
        }
      }
      currentSlide = index;
    }

    function nextSlide() {
      showSlide((currentSlide + 1) % carouselItems.length);
    }

    function startCarousel() {
      if (carouselItems.length <= 1) return;
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
  })();

  
  (function initFaqToggle() {
    const faqItems = document.querySelectorAll('.faq-item');
    if (!faqItems.length) return; // ⬅️ AMAN: hanya keluar dari FAQ

    faqItems.forEach((item, index) => {
      const question = item.querySelector('.faq-question');
      const answer   = item.querySelector('.faq-answer');
      if (!question || !answer) return;

      // Nomor urut
      const frameNumber = (index + 1).toString().padStart(2, '0');
      question.setAttribute('data-index', frameNumber);
      item.style.setProperty('--item-index', index);

      // Aksesibilitas
      item.setAttribute('aria-expanded', 'false');
      question.setAttribute('aria-controls', `faq-answer-${index}`);
      answer.id = `faq-answer-${index}`;

      // Toggle FAQ
      question.addEventListener('click', () => {
        const isActive = item.classList.contains('active');

        // Tutup FAQ lain
        faqItems.forEach(other => {
          if (other !== item && other.classList.contains('active')) {
            other.classList.remove('active');
            other.setAttribute('aria-expanded', 'false');
          }
        });

        // Toggle current
        item.classList.toggle('active', !isActive);
        item.setAttribute('aria-expanded', String(!isActive));
      });
    });

    // Intersection Observer (animasi masuk)
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver(
        entries => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.style.opacity = '1';
              entry.target.style.transform = 'translateY(0)';
              observer.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.1 }
      );

      faqItems.forEach(item => observer.observe(item));
    }
  })();

  /* ================================
     GALLERY CAROUSEL (gallery-track)
     ================================ */
  (function initGalleryCarousel() {
    const galleryTrack = document.querySelector('.gallery-track');
    const galleryItems = document.querySelectorAll('.gallery-item');
    const prevBtn = document.querySelector('.nav-btn.prev');
    const nextBtn = document.querySelector('.nav-btn.next');
    if (!galleryTrack || !galleryItems.length) return;

    let currentCenter = Math.min(2, galleryItems.length - 1);

    function updateGallery() {
      galleryItems.forEach((item, index) => {
        item.classList.toggle('center', index === currentCenter);
      });
      const centerItem = galleryItems[currentCenter];
      if (!centerItem) return;
      galleryTrack.scrollLeft =
        centerItem.offsetLeft - (galleryTrack.offsetWidth / 2) + (centerItem.offsetWidth / 2);
    }

    prevBtn && prevBtn.addEventListener('click', () => {
      if (currentCenter > 0) {
        currentCenter--;
        updateGallery();
      }
    });

    nextBtn && nextBtn.addEventListener('click', () => {
      if (currentCenter < galleryItems.length - 1) {
        currentCenter++;
        updateGallery();
      }
    });

    galleryItems.forEach((item, index) => {
      item.addEventListener('click', () => {
        currentCenter = index;
        updateGallery();
      });
    });

    updateGallery();
  })();

  /* ================================
     SERVICE CARD hover/touch
     ================================ */
  (function initServiceCards() {
    const serviceCards = document.querySelectorAll('.service-card');
    if (!serviceCards.length) return;

    serviceCards.forEach(card => {
      const content = card.querySelector('.service-content');
      if (!content) return;

      const baseBg = 'linear-gradient(to top, rgba(0, 0, 0, 0.95), transparent 70%)';

      card.addEventListener('mouseenter', () => {
        content.style.background = baseBg;
      });
      card.addEventListener('mouseleave', () => {
        content.style.background = baseBg;
      });
      card.addEventListener('touchstart', () => {
        if (window.innerWidth < 768) {
          card.classList.toggle('active');
          content.style.background = card.classList.contains('active')
            ? 'linear-gradient(to top, rgba(0, 0, 0, 0.98), transparent 70%)'
            : baseBg;
        }
      });
    });

    document.addEventListener('click', e => {
      if (!e.target.closest('.service-card')) {
        serviceCards.forEach(card => {
          const content = card.querySelector('.service-content');
          if (!content) return;
          card.classList.remove('active');
          content.style.background =
            'linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent 70%)';
        });
      }
    });
  })();

  /* ================================
     SMOOTH SCROLL (anchor #)
     + khusus slider testi
     ================================ */
  (function initSmoothScroll() {
    const headerEl = document.querySelector('header');

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', e => {
        const href = anchor.getAttribute('href');
        if (!href || href === '#') return;

        // target slide di dalam #tSlider?
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
  })();

  /* ================================
     SCROLL ANIMASI sederhana
     ================================ */
  (function initScrollAnimations() {
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
  })();

  /* ================================
     BOOKING BUTTON kecil
     ================================ */
  (function initBookingButton() {
    const bookingBtn = document.querySelector('.booking-btn');
    if (!bookingBtn) return;

    bookingBtn.addEventListener('click', function () {
      this.style.transform = 'scale(0.95)';
      setTimeout(() => {
        this.style.transform = '';
        alert('Redirecting to booking page...');
      }, 200);
    });
  })();

  /* ================================
     FLOATING MIDDLEBAR
     ================================ */
  (function initMiddlebarFloat() {
    const middlebar = document.querySelector('.middlebar-container');
    if (!middlebar) return;

    function addFloatingEffect() {
      middlebar.style.transition = 'transform 3s ease-in-out';
      middlebar.style.transform = 'translateY(-5px)';
      setTimeout(() => { middlebar.style.transform = 'translateY(0)'; }, 1500);
    }

    addFloatingEffect();
    setInterval(addFloatingEffect, 4000);
  })();

  /* ================================
     ABOUT STATS COUNTER (.about-stat-box)
     ================================ */
  (function initAboutStatBoxes() {
    const aboutStatBoxes = document.querySelectorAll('.about-stat-box');
    if (!aboutStatBoxes.length || !('IntersectionObserver' in window)) return;

    const aboutObserverOptions = {
      threshold: 0.5,
      rootMargin: '0px 0px -50px 0px'
    };

    const aboutObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const statBox = entry.target;
          const numberElement = statBox.childNodes[0];

          if (numberElement && numberElement.nodeType === 3) { // Text node
            const targetNumber = parseInt(numberElement.textContent);
            if (isNaN(targetNumber)) return;

            let currentNumber = 0;
            const steps = 50;
            const increment = targetNumber / steps;
            const duration = 1500;
            const stepTime = duration / steps;

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

    aboutStatBoxes.forEach(box => aboutObserver.observe(box));
  })();

  /* ================================
     MODAL DETAIL PAKET (.pkg-modal)
     ================================ */
  (function initPackageModal() {
    const openers = document.querySelectorAll('[data-open]');
    const backdrops = document.querySelectorAll('.pkg-modal__backdrop');

    if (!openers.length && !backdrops.length) return;

    function closeDetail(modal) {
      modal.classList.remove('is-open');
      document.body.style.overflow = '';
    }

    openers.forEach(btn => {
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

    document.addEventListener('click', (e) => {
      if (e.target.matches('[data-close-detail]')) {
        const modal = e.target.closest('.pkg-modal') || document.querySelector('.pkg-modal.is-open');
        if (modal) closeDetail(modal);
      }
    });

    backdrops.forEach(bd => {
      bd.addEventListener('click', () => {
        const modal = bd.closest('.pkg-modal');
        if (modal) closeDetail(modal);
      });
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        document.querySelectorAll('.pkg-modal.is-open').forEach(m => closeDetail(m));
      }
    });
  })();

  /* ================================
     PROMO CAROUSEL (#promoCarousel)
     ================================ */
  (function initPromoCarousel() {
    const wrap = document.getElementById('promoCarousel');
    if (!wrap) return;

    const slides = Array.from(wrap.querySelectorAll('.promo-slide'));
    const dots = Array.from(wrap.querySelectorAll('.promo-dot'));
    const interval = parseInt(wrap.dataset.interval || '4000', 10);

    if (!slides.length) return;

    let idx = 0;
    let timer;

    function show(n) {
      slides[idx]?.classList.remove('is-active');
      dots[idx]?.classList.remove('is-active');
      idx = (n + slides.length) % slides.length;
      slides[idx]?.classList.add('is-active');
      dots[idx]?.classList.add('is-active');
    }

    function next() { show(idx + 1); }
    function start() { stop(); timer = setInterval(next, interval); }
    function stop() { if (timer) clearInterval(timer); }

    dots.forEach((d, i) => d.addEventListener('click', () => { show(i); start(); }));
    wrap.addEventListener('mouseenter', stop);
    wrap.addEventListener('mouseleave', start);

    show(0);
    start();
  })();

  /* ================================
     TESTIMONIAL SLIDER (#tSlider)
     ================================ */
  (function initTestimonialSlider() {
    const slider = document.getElementById('tSlider');
    if (!slider) return;

    const track = slider.querySelector('.slides');
    const items = Array.from(slider.querySelectorAll('.t-item'));
    const btns = slider.querySelectorAll('.t-btn');
    if (!track || !items.length) return;

    let idx = 0;
    let timer = null;

    function applyTransform() {
      track.style.transform = `translateX(-${idx * 100}%)`;
      items.forEach((el, i) => el.setAttribute('aria-hidden', i !== idx));
    }

    function go(n, { updateHash = false } = {}) {
      idx = (n + items.length) % items.length;
      applyTransform();
      if (updateHash) {
        const id = items[idx].id || `testi-${idx + 1}`;
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

    btns.forEach(btn => {
      btn.addEventListener('click', () => {
        const dir = parseInt(btn.dataset.dir || '1', 10);
        go(idx + dir, { updateHash: true });
      });
    });

    function startAuto() { stopAuto(); timer = setInterval(() => go(idx + 1, { updateHash: true }), 5000); }
    function stopAuto() { if (timer) { clearInterval(timer); timer = null; } }

    slider.addEventListener('mouseenter', stopAuto);
    slider.addEventListener('mouseleave', startAuto);

    if (!goById(location.hash)) {
      applyTransform();
    }

    window.addEventListener('hashchange', () => {
      goById(location.hash);
    });

    startAuto();
  })();

  /* ================================
     REVIEWS SECTION (load more)
     ================================ */
  (function initReviewsSection() {
    const PAGE_SIZE = 20;

    const section = document.getElementById('reviewsSection');
    const grid = document.getElementById('reviewsGrid');
    const openBtn = document.getElementById('btnOpenReviews');
    const hideBtn = document.getElementById('btnHideReviews');
    const moreBtn = document.getElementById('btnLoadMoreReviews');

    if (!section || !grid || !openBtn || !hideBtn || !moreBtn) return;

    const cards = Array.from(grid.querySelectorAll('.review-card'));
    let shown = 0;

    function hideAllCards() {
      cards.forEach(c => c.hidden = true);
      shown = 0;
    }

    function updateMoreBtn() {
      const remaining = Math.max(cards.length - shown, 0);
      moreBtn.textContent = 'Selengkapnya';
      moreBtn.hidden = remaining <= 0;
    }

    function showNextChunk() {
      const end = Math.min(shown + PAGE_SIZE, cards.length);
      for (let i = shown; i < end; i++) cards[i].hidden = false;
      shown = end;
      updateMoreBtn();
    }

    function openSection() {
      section.hidden = false;
      openBtn.hidden = true;
      hideBtn.hidden = false;
      openBtn.setAttribute('aria-expanded', 'true');

      hideAllCards();
      showNextChunk();
      try { section.scrollIntoView({ behavior: 'smooth', block: 'start' }); } catch (e) { }
    }

    function collapseSection() {
      section.hidden = true;
      openBtn.hidden = false;
      hideBtn.hidden = true;
      openBtn.setAttribute('aria-expanded', 'false');

      hideAllCards();
      updateMoreBtn();
      try { openBtn.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (e) { }
    }

    hideAllCards();
    updateMoreBtn();

    openBtn.addEventListener('click', openSection);
    hideBtn.addEventListener('click', collapseSection);
    moreBtn.addEventListener('click', showNextChunk);
  })();

  /* ================================
     LANDING EFFECTS:
     lazy img + reveal + tilt + mosaic counter
     ================================ */
  (function initLandingEffects() {
    const prefersReduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
    const pointerFine = matchMedia('(pointer: fine)').matches;
    const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

    // Lazy load images
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

    // Tilt hover
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

    // Stats counter mosaic (.stat-number-mosaic)
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
  })();

  /* ================================
     GALLERY FILTER + TITLE
     ================================ */
  (function initGalleryFilter() {
    const filterTitles = {
      'all':        { title:'All Sessions',       description:'Explore our complete collection of professional photography sessions.' },
      'prewed':     { title:'Prewed Session',     description:'A pre-wedding photoshoot designed to capture the love story of a couple before their big day.' },
      'family':     { title:'Family Session',     description:'Warm, joyful portraits that preserve authentic moments and togetherness.' },
      'maternity':  { title:'Maternity Shoot',    description:'Graceful images celebrating motherhood and new beginnings.' },
      'postwedding':{ title:'Post Wedding',       description:'Relaxed, artistic sessions after the wedding day with creative concepts.' },
      'beauty':     { title:'Beauty Shoot',       description:'Elegant portraits focusing on style, fashion, and confidence.' },
      'birthday':   { title:'Birthday Session',   description:'Vibrant shoots to celebrate special milestones and personalities.' }
    };

    const section = document.querySelector('.gallery-section');
    const btns = document.querySelectorAll('.filter-btn');
    const items = document.querySelectorAll('.gallery-item');
    const dynamicTitle = document.getElementById('dynamicTitle');

    if (!section || !btns.length || !items.length || !dynamicTitle) return;

    function applyFilter(filterKey) {
      if (!filterTitles[filterKey]) filterKey = 'all';

      btns.forEach(b => {
        const f = b.getAttribute('data-filter') || 'all';
        b.classList.toggle('active', f === filterKey);
      });

      const data = filterTitles[filterKey];
      dynamicTitle.classList.remove('active');

      setTimeout(() => {
        const h2 = dynamicTitle.querySelector('h2');
        const p = dynamicTitle.querySelector('p');
        if (h2) h2.textContent = data.title;
        if (p) p.textContent = data.description;
        dynamicTitle.classList.add('active');
      }, 10);

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

    btns.forEach(btn => {
      btn.addEventListener('click', () => {
        const filter = btn.getAttribute('data-filter') || 'all';
        applyFilter(filter);

        try {
          const url = new URL(window.location.href);
          if (filter === 'all') {
            url.searchParams.delete('category');
          } else {
            url.searchParams.set('category', filter);
          }
          window.history.replaceState({}, '', url.toString());
        } catch (e) {
          // ignore
        }
      });
    });

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
      // ignore
    }

    applyFilter(initialFilter);
  })();

  /* ================================
     GALLERY IMAGE MODAL
     ================================ */
  (function initGalleryModal() {
    const modal = document.getElementById('imageModal');
    const expanded = document.getElementById('expandedImage');
    if (!modal || !expanded) return;

    const closeBtn = modal.querySelector('.close');

    document.querySelectorAll('.gallery-item').forEach(card => {
      card.addEventListener('click', () => {
        const src = card.dataset.img || card.querySelector('img')?.src;
        if (!src) return;
        expanded.src = src;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
      });
    });

    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
      });
    }

    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        modal.style.display = 'none';
        document.body.style.overflow = '';
      }
    });
  })();
    /* ================================
     BOOKING HISTORY MODAL
     ================================ */
  (function initBookingHistoryModal() {
    // tombol "Lihat Detail"
    const openers = document.querySelectorAll('[data-modal-target]');
    if (!openers.length) return;

    function openModal(modal) {
      if (!modal) return;
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeModal(modal) {
      if (!modal) return;
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }

    // OPEN
    openers.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        const sel = btn.getAttribute('data-modal-target');
        const modal = sel ? document.querySelector(sel) : null;
        openModal(modal);
      });
    });

    // CLOSE (klik backdrop / tombol close)
    document.addEventListener('click', (e) => {
      // backdrop
      if (e.target.classList.contains('booking-modal-backdrop')) {
        closeModal(e.target.closest('.booking-modal'));
      }

      // tombol close
      if (e.target.classList.contains('booking-modal-close')) {
        closeModal(e.target.closest('.booking-modal'));
      }
    });

    // ESC
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        document.querySelectorAll('.booking-modal.active')
          .forEach(m => closeModal(m));
      }
    });
  })();
});
