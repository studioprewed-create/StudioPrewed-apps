import {
    initBase,
    initScrollLink,
    initSmoothScroll,
    initSuccessAlert,
    initScrollAnimations,
    initLandingEffects,
    enableModalBackClose,
    initRippleEffect,
    initNavbarScroll
} from './JSBASE/Base.js';

import {
    initBookingHistoryModal,
    initPackageModal,
    initThumbSwitcher,
    initGalleryCardModal,
    expandImage
} from './JSCOMPONENT/Modal.js';

import {
    initGoogleReviews
} from './JSSECTION/GoogleReview.js';

import {
    initGalleryFilter,
    initGalleryModal
} from './JSSECTION/portofolio.js';

import {
    initHeroCarousel,
    initFaqToggle,
    initGalleryCarousel,
    initServiceCards,
    initBookingButton,
    initMiddlebarFloat,
    initAboutStatBoxes,
    initPromoCarousel,
    initTestimonialSlider,
    initReviewsSection
} from './JSSECTION/Home.js';

import {
    initPrefillDataDiri
} from './JSSECTION/Account.js';

import {
    initBookingWizard,
    initAddonInlineEdit
} from './JSSECTION/Booking.js';

import {
    initModalNavigation
} from './JSCOMPONENT/Navigasi.js';

import {
    initBookingTrigger
} from './JSSECTION/Package.js';

document.addEventListener('DOMContentLoaded', () => {

    initBase();
    initScrollLink();
    initSmoothScroll();
    initSuccessAlert();
    initScrollAnimations();
    initLandingEffects();
    enableModalBackClose();
    initRippleEffect();
    initNavbarScroll();

    initGoogleReviews();

    initGalleryFilter();

    initHeroCarousel();
    initFaqToggle();
    initGalleryCarousel();
    initServiceCards();
    initBookingButton();
    initMiddlebarFloat();
    initAboutStatBoxes();
    initPromoCarousel();
    initTestimonialSlider();
    initReviewsSection();

    initGalleryModal();
    initBookingHistoryModal();
    initPackageModal();
    initThumbSwitcher();
    initGalleryCardModal();

    initModalNavigation();

    initBookingTrigger();

    initPrefillDataDiri();

    initBookingWizard();
    initAddonInlineEdit();

});