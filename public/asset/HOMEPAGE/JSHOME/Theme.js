/* =========================================
   THEME TOGGLE
========================================= */

document.addEventListener(
    'DOMContentLoaded',
    function(){

    const themeToggle =
        document.getElementById(
            'themeToggle'
        );

    const themeIcon =
        themeToggle?.querySelector('i');

    /* =====================================
       LOAD SAVED THEME
    ===================================== */

    const savedTheme =
        localStorage.getItem(
            'theme'
        );

    if(savedTheme === 'light'){

        document.body.classList.add(
            'light-mode'
        );

        if(themeIcon){

            themeIcon.classList.remove(
                'fa-moon'
            );

            themeIcon.classList.add(
                'fa-sun'
            );

        }
    }

    /* =====================================
       TOGGLE THEME
    ===================================== */

    if(themeToggle){

        themeToggle.addEventListener(
            'click',
            function(){

                document.body.classList.toggle(
                    'light-mode'
                );

                const isLight =
                    document.body.classList.contains(
                        'light-mode'
                    );

                /* SAVE THEME */

                localStorage.setItem(
                    'theme',
                    isLight
                        ? 'light'
                        : 'dark'
                );

                /* CHANGE ICON */

                if(themeIcon){

                    if(isLight){

                        themeIcon.classList.remove(
                            'fa-moon'
                        );

                        themeIcon.classList.add(
                            'fa-sun'
                        );

                    }else{

                        themeIcon.classList.remove(
                            'fa-sun'
                        );

                        themeIcon.classList.add(
                            'fa-moon'
                        );

                    }
                }

            }
        );

    }

});