export function initGalleryModal() {

    const modal = document.getElementById('imageModal');
    const expanded = document.getElementById('expandedImage');
    const title = document.getElementById('modalTitle');
    const desc = document.getElementById('modalDesc');

    if (!modal || !expanded) return;

    const closeBtn = modal.querySelector('.modal-close');

    // 🔥 AMBIL HANYA ITEM DI GALLERY SECTION (ANTI BENTROK)
    const cards = document.querySelectorAll('.gallery-section .gallery-item');

    cards.forEach(card => {
        card.addEventListener('click', function (e) {

        // 🔥 PENTING: biar gak bentrok sama JS lain
        e.stopPropagation();

        const img = this.dataset.img;
        const t   = this.dataset.title;
        const d   = this.dataset.desc;

        if (!img) return;

        expanded.src = img;
        title.textContent = t || '';
        desc.textContent  = d || '';

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        enableModalBackClose(
            modal,
            closeModal
        );

        hideFloatingUI();

        });
    });

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';

        showFloatingUI();
        
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    modal.addEventListener('click', function (e) {
        if (e.target.classList.contains('modal-backdrop')) {
        closeModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
        closeModal();
        }
    });

}

export function initBookingHistoryModal() {
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

    openers.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        const sel = btn.getAttribute('data-modal-target');
        const modal = sel ? document.querySelector(sel) : null;
        openModal(modal);
      });
    });

    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('booking-modal-backdrop')) {
        closeModal(e.target.closest('.booking-modal'));
      }

      if (e.target.classList.contains('booking-modal-close')) {
        closeModal(e.target.closest('.booking-modal'));
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        document.querySelectorAll('.booking-modal.active')
          .forEach(m => closeModal(m));
      }
    });
}

export function initPackageModal() {
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
}

export function initThumbSwitcher() {
  document.addEventListener('click', function (e) {
    if (e.target.matches('[data-close-img]') || e.target.id === 'imageModal') {
        const modal = document.getElementById('imageModal');
        if (modal) modal.style.display = 'none';

        showFloatingUI();
    }
  });
}

export function expandImage(src) {

    const modal =
        document.getElementById('imageModal');

    const img =
        document.getElementById('expandedImage');

    if (!modal || !img) return;

    img.src = src;

    modal.style.display = 'block';

    hideFloatingUI();
}

export function initGalleryCardModal() {

    const galleryTrack =
        document.querySelector('.gallery-track');

    const galleryItems =
        document.querySelectorAll('.gallery-item');

    if (!galleryTrack || !galleryItems.length) {
        return;
    }

    let currentCenter = 2;

    function updateGallery() {

        galleryItems.forEach((item, index) => {

            item.classList.toggle(
                'center',
                index === currentCenter
            );

        });

        const centerItem =
            galleryItems[currentCenter];

        if (centerItem) {

            galleryTrack.scrollLeft =
                centerItem.offsetLeft -
                galleryTrack.offsetWidth / 2 +
                centerItem.offsetWidth / 2;

        }
    }

    galleryItems.forEach((item, index) => {

        item.addEventListener('click', e => {

            if (
                e.target.closest('[data-open]')
            ) return;

            if (currentCenter !== index) {

                currentCenter = index;

                updateGallery();

                return;
            }

            const sel =
                item.getAttribute('data-modal');

            const modal = sel
                ? document.querySelector(sel)
                : null;

            if (!modal) return;

            modal.classList.add('is-open');

            document.body.style.overflow =
                'hidden';

            enableModalBackClose(
                modal,
                () => closeDetail(modal)
            );

            hideFloatingUI();

        });

    });

    function closeDetail(modal) {

        modal.classList.remove('is-open');

        document.body.style.overflow = '';

       showFloatingUI();
    }

}
