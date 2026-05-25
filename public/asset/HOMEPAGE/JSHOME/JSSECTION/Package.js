export function initBookingTrigger() {
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
}

