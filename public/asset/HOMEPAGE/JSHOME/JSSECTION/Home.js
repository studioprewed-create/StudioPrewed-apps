export function initHeroCarousel() {
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
}

export function initFaqToggle() {

    const faqItems = document.querySelectorAll('.faq-item');

    if (!faqItems.length) return;

    faqItems.forEach((item, index) => {

        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const number = item.querySelector('.faq-number');

        if (!question || !answer) return;

        number.textContent =
            (index + 1).toString().padStart(2, '0');

        item.style.setProperty('--item-index', index);

        item.setAttribute('aria-expanded', 'false');

        question.setAttribute(
            'aria-controls',
            `faq-answer-${index}`
        );

        answer.id = `faq-answer-${index}`;

        question.addEventListener('click', () => {

            const isActive =
                item.classList.contains('active');

            faqItems.forEach(other => {

                const otherAnswer =
                    other.querySelector('.faq-answer');

                other.classList.remove('active');

                other.setAttribute(
                    'aria-expanded',
                    'false'
                );

                otherAnswer.style.maxHeight = null;
            });

            if (!isActive) {

                item.classList.add('active');

                item.setAttribute(
                    'aria-expanded',
                    'true'
                );

                answer.style.maxHeight =
                    answer.scrollHeight + 'px';
            }

        });

    });

    if ('IntersectionObserver' in window) {

        const observer = new IntersectionObserver(

            entries => {

                entries.forEach(entry => {

                    if (entry.isIntersecting) {

                        entry.target.animate(
                            [
                                {
                                    opacity:0,
                                    transform:'translateY(30px)'
                                },
                                {
                                    opacity:1,
                                    transform:'translateY(0)'
                                }
                            ],
                            {
                                duration:700,
                                easing:'cubic-bezier(.23,1,.32,1)',
                                fill:'forwards'
                            }
                        );

                        observer.unobserve(entry.target);
                    }

                });

            },

            {
                threshold:.1
            }

        );

        faqItems.forEach(item => {

            observer.observe(item);

        });

    }

}

export function initGalleryCarousel() {
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
}

export function initServiceCards() {
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
}

export function initBookingButton() {
    const bookingBtn = document.querySelector('.booking-btn');
    if (!bookingBtn) return;

    bookingBtn.addEventListener('click', function () {
      this.style.transform = 'scale(0.95)';
      setTimeout(() => {
        this.style.transform = '';
        alert('Redirecting to booking page...');
      }, 200);
    });
}

export function initMiddlebarFloat() {
    const middlebar = document.querySelector('.middlebar-container');
    if (!middlebar) return;

    function addFloatingEffect() {
      middlebar.style.transition = 'transform 3s ease-in-out';
      middlebar.style.transform = 'translateY(-5px)';
      setTimeout(() => { middlebar.style.transform = 'translateY(0)'; }, 1500);
    }

    addFloatingEffect();
    setInterval(addFloatingEffect, 4000);
}

export function initAboutStatBoxes() {
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
}

export function initPromoCarousel() {
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
}

export function initTestimonialSlider() {
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
}

export function initReviewsSection() {
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
}