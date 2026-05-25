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

