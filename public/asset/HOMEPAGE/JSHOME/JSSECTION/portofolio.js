export function initGalleryFilter() {
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
}

export function initGalleryModal() {
    const modal = document.getElementById('imageModal');
    const expanded = document.getElementById('expandedImage');
    const title = document.getElementById('modalTitle');
    const desc = document.getElementById('modalDesc');
    if (!modal || !expanded) return;
    const closeBtn = modal.querySelector('.modal-close');
    const prevBtn = modal.querySelector('.porto-prev');
    const nextBtn = modal.querySelector('.porto-next');
    const cards = document.querySelectorAll(
            '.gallery-section .gallery-item'
        );
    const wa = document.querySelector('.wa-float');
    const sectionFloat = document.querySelector('.section-float');
    let currentIndex = 0;

    function hideFloatingUI() {
        if (wa) {
            wa.classList.remove('show');
            wa.classList.add('hide');
        }
        if (sectionFloat) {
            sectionFloat.classList.remove(
                'show'
            );
            sectionFloat.classList.add(
                'hide'
            );
        }
    }

    function showFloatingUI() {

        if (wa) {
            wa.classList.remove('hide');
        }
        if (sectionFloat) {
            sectionFloat.classList.remove(
                'hide'
            );
        }
    }

    function updateModal(index) {
        const card = cards[index];
        if (!card) return;

        expanded.src = card.dataset.img || '';
        title.textContent = card.dataset.title || '';
        desc.textContent = card.dataset.desc || '';
        currentIndex = index;

    }

    function openModal(index) {

        updateModal(index);
        modal.style.display = 'flex';
        document.body.style.overflow =
            'hidden';

        hideFloatingUI();
        enableModalBackClose(
            modal,
            closeModal
        );

    }

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        showFloatingUI();
    }

    function showNext() {
        let nextIndex = currentIndex + 1;
        if (nextIndex >= cards.length) {
            nextIndex = 0;
        }
        updateModal(nextIndex);

    }

    function showPrev() {

        let prevIndex = currentIndex - 1;
        if (prevIndex < 0) {
            prevIndex =
                cards.length - 1;
        }
        updateModal(prevIndex);

    }

    cards.forEach((card, index) => {
        card.addEventListener(
            'click',
            function (e) {
                e.stopPropagation();
                openModal(index);
            }
        );
    });

    if (nextBtn) {
        nextBtn.addEventListener(
            'click',
            showNext
        );
    }

    if (prevBtn) {
        prevBtn.addEventListener(
            'click',
            showPrev
        );
    }

    if (closeBtn) {
        closeBtn.addEventListener(
            'click',
            closeModal
        );
    }

    modal.addEventListener(
        'click',
        function (e) {
            if (
                e.target.classList.contains(
                    'modal-backdrop'
                )
            ) {

                closeModal();

            }

        }
    );


    document.addEventListener(
        'keydown',
        function (e) {
            if (
                modal.style.display !==
                'flex'
            ) return;
            if (e.key === 'Escape') {
                closeModal();
            }

            if (e.key === 'ArrowRight') {
                showNext();
            }

            if (e.key === 'ArrowLeft') {
                showPrev();
            }
        }
    );

}