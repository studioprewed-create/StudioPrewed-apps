import {
    initBase,
    initScrollLink,
    initSmoothScroll,
    initSuccessAlert,
    initScrollAnimations,
    initLandingEffects,
    enableModalBackClose
} from './JSBASE/Base.js';

import {
    initGalleryModal,
    initBookingHistoryModal,
    initPackageModal
} from './JSCOMPONENT/Modal.js';

import {
    initGoogleReviews
} from './JSSECTION/GoogleReview.js';

import {
    initGalleryFilter
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


document.addEventListener('DOMContentLoaded', () => {

    initBase();
    initScrollLink();
    initSmoothScroll();
    initSuccessAlert();
    initScrollAnimations();
    initLandingEffects();
    enableModalBackClose();

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

});