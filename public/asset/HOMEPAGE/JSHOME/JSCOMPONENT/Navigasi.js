export function initModalNavigation() {
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

                enableModalBackClose(
                    nextModal,
                    () => closeDetail(nextModal)
                );
                
                const wa = document.querySelector('.wa-float');
                if (wa) wa.classList.add('hide');

                const sectionFloat =
                    document.querySelector('.section-float');

                if (sectionFloat)
                    sectionFloat.classList.add('hide');
            }
        });
    }

    bindModalNav('data-tema-nav');
    bindModalNav('data-pkg-nav');
}



