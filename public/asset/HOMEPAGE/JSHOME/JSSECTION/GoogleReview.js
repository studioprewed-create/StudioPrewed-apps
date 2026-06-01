export function initGoogleReviews() {

    const grid = document.getElementById('googleReviewGrid');

    if(!grid) return;

    const pagination = document.getElementById('googleReviewPagination');
    const filterWrap = document.getElementById('googleReviewFilter');
    const modal = document.getElementById('googleReviewModal');
    const modalClose = document.getElementById('googleReviewClose');
    const modalAuthor = document.getElementById('modalAuthor');
    const modalText = document.getElementById('modalText');
    const modalDate = document.getElementById('modalDate');
    const modalStars = document.getElementById('modalStars');
    const modalAvatar = document.getElementById('modalAvatar');
    const dropdown = document.querySelector('.google-filter-dropdown');
    const dropdownBtn = document.getElementById('googleStarDropdownBtn');

    const PAGE_SIZE = 8;
    let currentPage = 1;
    let currentFilter = 'all';
    let cards = Array.from(
        grid.querySelectorAll(
            '.google-review-card'
        )
    );

    cards.sort((a,b)=>{
        const photoA = parseInt(a.dataset.photo);
        const photoB = parseInt(b.dataset.photo);
        const ratingA = parseInt(a.dataset.rating);
        const ratingB = parseInt(b.dataset.rating);
        const dateA = new Date(a.dataset.date);
        const dateB = new Date(b.dataset.date);

        if(photoA !== photoB){
            return photoB - photoA;
        }
        if(ratingA !== ratingB){
            return ratingB - ratingA;
        }
        return dateB - dateA;

    });

    cards.forEach(card=>{
        grid.appendChild(card);
    });


    function getFilteredCards(){
        return cards.filter(card=>{
            const rating = parseInt(card.dataset.rating);
            const photo = parseInt(card.dataset.photo);
            switch(currentFilter){
                case '5':
                case '4':
                case '3':
                case '2':
                case '1':
                    return rating === parseInt(currentFilter);
                case 'photo':
                    return photo === 1;
                default:
                    return true;
            }

        });

    }

    function renderCards(){
        const filtered = getFilteredCards();
        cards.forEach(card=>{
            card.style.display = 'none';
        });
        const start = (currentPage - 1) * PAGE_SIZE;
        const end = start + PAGE_SIZE;
        filtered
            .slice(start,end)
            .forEach(card=>{

                card.style.display = '';

            });
        renderPagination(filtered.length);

    }


    function scrollToGrid(){
        const header = document.getElementById('siteHeader');
        const headerHeight = header ? header.offsetHeight : 0;
        const top =
            grid.getBoundingClientRect().top
            + window.pageYOffset
            - headerHeight
            - 24;

            window.scrollTo({
            top,
            behavior:'smooth'
        });

    }

    function scrollToFilter(){
        const header = document.getElementById('siteHeader');
        const headerHeight = header ? header.offsetHeight : 0;
        const top =
            filterWrap.getBoundingClientRect().top
            + window.pageYOffset
            - headerHeight
            - 24;

        window.scrollTo({
            top,
            behavior:'smooth'
        });

    }

    function renderPagination(totalItems){
        pagination.innerHTML = '';
        const totalPages = Math.ceil(totalItems / PAGE_SIZE);

        if(totalPages <= 1) return;
        const maxVisible = 5;
        let startPage =
            Math.max(
                1,
                currentPage - 2
            );
        let endPage = startPage + maxVisible - 1;

        if(endPage > totalPages){
            endPage = totalPages;
            startPage =
                Math.max(
                    1,
                    endPage - maxVisible + 1
                );

        }

        const prevBtn = document.createElement('button');
        prevBtn.className = 'google-page-btn';
        prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click',()=>{
            if(currentPage <= 1) return;
            currentPage--;
            renderCards();
        });
        pagination.appendChild(prevBtn);

        for(let i=startPage;i<=endPage;i++){
            const btn = document.createElement('button');
            btn.className = 'google-page-btn';
            if(i === currentPage){
                btn.classList.add('active');
            }
            btn.textContent = i;
            btn.addEventListener('click',()=>{
                currentPage = i;
                renderCards();
                scrollToGrid();
            });
            pagination.appendChild(btn);
        }
        const nextBtn = document.createElement('button');
        nextBtn.className = 'google-page-btn';
        nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener('click',()=>{
            if(currentPage >= totalPages) return;
            currentPage++;
            renderCards();
        });
        pagination.appendChild(nextBtn);

    }

    document.addEventListener('click',function(e){

        const filterBtn = e.target.closest('[data-filter]');
        if(!filterBtn) return;
        e.preventDefault();
        document
            .querySelectorAll(
                '.google-review-filter a'
            )
            .forEach(btn=>{
                btn.classList.remove(
                    'active'
                );
            });
            filterBtn.classList.add('active'
        );

        if(dropdown){
            dropdown.classList.remove('active');
        }
        currentFilter = filterBtn.dataset.filter;
        currentPage = 1;
        renderCards();
        scrollToFilter();
    });

    dropdownBtn.addEventListener('click',function(e){
        e.preventDefault();
        dropdown.classList.toggle(
            'active'
        );
    });

    document.addEventListener('click',function(e){
        if(
            !e.target.closest(
                '.google-filter-dropdown'
            )
        ){
            dropdown.classList.remove(
                'active'
            );
        }
    });

    document.addEventListener('click',function(e){
        const btn =
            e.target.closest(
                '.google-review-more'
            );
        if(!btn) return;
        const author = btn.dataset.author;
        const text = btn.dataset.text;
        const date = btn.dataset.date;
        const rating = parseInt(btn.dataset.rating);
        modalAuthor.textContent = author;
        modalText.textContent = text;
        modalDate.textContent = date;
        modalAvatar.textContent =
            author.charAt(0).toUpperCase();
        let stars = '';
        for(let i=1;i<=5;i++){
            if(i <= rating){
                stars += `
                    <i class="fa-solid fa-star"></i>
                `;
            }else{
                stars += `
                    <i class="fa-regular fa-star"></i>
                `;
            }
        }

        modalStars.innerHTML = stars;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        enableModalBackClose(
            modal,
            closeGoogleModal
        );

    });

    function closeGoogleModal(){
        modal.classList.remove(
            'active'
        );
        document.body.style.overflow = '';

    }

    modalClose.addEventListener(
        'click',
        closeGoogleModal
    );

    modal.addEventListener('click',function(e){
        if(
            e.target.classList.contains(
                'google-review-modal-backdrop'
            )
        ){
            closeGoogleModal();
        }
    });

    document.addEventListener('keydown',function(e){
        if(e.key === 'Escape'){
            closeGoogleModal();
        }
    });

    const imageModal = document.getElementById('googleImageModal');
    const imagePreview = document.getElementById('googleImagePreview');
    const imageClose = document.getElementById('googleImageClose');
    const imagePrev = document.getElementById('googleImagePrev');
    const imageNext = document.getElementById('googleImageNext');
    let currentImages = [];
    let currentImageIndex = 0;

    document.addEventListener('click',function(e){
        const image =
            e.target.closest(
                '.google-review-image-item'
            );
        if(!image) return;
        const parent =
            image.closest(
                '.google-review-images'
            );
        currentImages =
            Array.from(
                parent.querySelectorAll(
                    '.google-review-image-item'
                )
            );
        currentImageIndex =
            currentImages.indexOf(image);
        updateModalImage();
        imageModal.classList.add(
            'active'
        );

        document.body.style.overflow =
            'hidden';

        enableModalBackClose(
            imageModal,
            closeImageModal
        );

    });

    function updateModalImage(){

        const current =
            currentImages[currentImageIndex];

        imagePreview.src =
            current.dataset.image;

    }

    imageNext.addEventListener('click',()=>{
        currentImageIndex++;
        if(
            currentImageIndex >=
            currentImages.length
        ){
            currentImageIndex = 0;
        }
        updateModalImage();
    });

    imagePrev.addEventListener('click',()=>{
        currentImageIndex--;
        if(currentImageIndex < 0){
            currentImageIndex =
                currentImages.length - 1;
        }
        updateModalImage();
    });

    function closeImageModal(){
        imageModal.classList.remove(
            'active'
        );
        document.body.style.overflow =
        '';
    }

    imageClose.addEventListener(
        'click',
        closeImageModal
    );

    imageModal.addEventListener(
        'click',
        function(e){
            if(
                e.target.classList.contains(
                    'google-image-backdrop'
                )
            ){
                closeImageModal();
            }
        }
    );

    document.addEventListener(
        'keydown',
        function(e){
            if(
                !imageModal.classList.contains(
                    'active'
                )
            ) return;

            if(e.key === 'ArrowRight'){
                imageNext.click();
            }

            if(e.key === 'ArrowLeft'){
                imagePrev.click();
            }

            if(e.key === 'Escape'){
                closeImageModal();
            }
        }
    );

    renderCards();
}