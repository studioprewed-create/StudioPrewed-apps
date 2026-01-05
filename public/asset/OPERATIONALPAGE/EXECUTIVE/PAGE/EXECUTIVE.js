document.addEventListener('DOMContentLoaded', () => {
    const mainContent = document.getElementById('main-content');
    const menuLinks   = document.querySelectorAll('.sidebar .menu a[data-page]');
    const dropdowns   = document.querySelectorAll('.menu-item.dropdown > .dropdown-toggle');

    const LS_KEY = 'exec_activeMenu';
    const serverPage = mainContent?.dataset?.currentPage || 'Dashboard';

    /* ============ HELPER ============ */
    const isHttpUrl = (str) => /^https?:\/\//i.test(str);

   const buildImageUrl = (path) => {
        if (!path) return '';
        if (isHttpUrl(path)) return path;

        if (!path.startsWith('storage/') && !path.startsWith('public/storage/')) {
            return '/public/storage/' + path.replace(/^\/+/, '');
        }

        return '/' + path.replace(/^\/+/, '');
    };

    /* ============ MODAL DATA AKUN ============ */
    const initUserModals = () => {
        const backdrop      = document.getElementById('modal-backdrop');
        const createModal   = document.getElementById('modal-create');
        const editModal     = document.getElementById('modal-edit');
        const btnOpenCreate = document.getElementById('btn-open-create');

        if (!backdrop || !createModal || !editModal) return;

        const showModal = (modal) => {
            backdrop.classList.add('show');
            modal.classList.add('show');
        };

        const hideModals = () => {
            backdrop.classList.remove('show');
            createModal.classList.remove('show');
            editModal.classList.remove('show');
        };

        if (btnOpenCreate) {
            btnOpenCreate.onclick = () => showModal(createModal);
        }

        document.querySelectorAll('.btn-edit-user').forEach(btn => {
            btn.onclick = () => {
                const id    = btn.dataset.id;
                const name  = btn.dataset.name;
                const email = btn.dataset.email;
                const role  = btn.dataset.role;

                const form    = document.getElementById('editUserForm');
                if (!form) return;

                const baseUrl = form.dataset.baseUrl;

                form.action = `${baseUrl}/${id}`;
                document.getElementById('edit-name').value  = name;
                document.getElementById('edit-email').value = email;
                document.getElementById('edit-role').value  = role;

                showModal(editModal);
            };
        });

        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.onclick = hideModals;
        });

        backdrop.onclick = hideModals;
    };

    /* ============ MODAL TEMA BAJU (CREATE) ============ */
    const initCatalogueTemaModals = () => {
        const backdrop   = document.getElementById('backdropCreateTema');
        const modal      = document.getElementById('modalCreateTema');
        const btnOpen    = document.getElementById('btnOpenCreateTema');
        const btnClose   = document.getElementById('btnCloseCreateTema');
        const btnClose2  = document.getElementById('btnCloseCreateTema2');

        if (!backdrop || !modal) return;

        const showModal = () => {
            backdrop.classList.add('show');
            modal.classList.add('show');
        };

        const hideModal = () => {
            backdrop.classList.remove('show');
            modal.classList.remove('show');
        };

        if (btnOpen)   btnOpen.onclick   = showModal;
        if (btnClose)  btnClose.onclick  = hideModal;
        if (btnClose2) btnClose2.onclick = hideModal;

        backdrop.onclick = (e) => {
            if (e.target === backdrop) hideModal();
        };

        const inputImages = document.getElementById('inputImages');
        const dropZone    = document.getElementById('uploadDrop');
        const previewWrap = document.getElementById('previewImages');

        if (!inputImages || !dropZone || !previewWrap) return;

        const createThumb = (src) => {
            const wrap = document.createElement('div');
            wrap.classList.add('thumb');
            const img = document.createElement('img');
            img.src = src;
            wrap.appendChild(img);
            return wrap;
        };

        const handleFiles = (files) => {
            previewWrap.innerHTML = '';

            if (!files || files.length === 0) {
                dropZone.style.display = 'block';
                return;
            }

            Array.from(files).forEach(file => {
                if (file.size > 2 * 1024 * 1024) {
                    alert(`Ukuran file "${file.name}" terlalu besar. Maksimal 2MB.`);
                    return;
                }

                const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert(`Format file "${file.name}" tidak didukung. Gunakan JPG, PNG, atau WEBP.`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    const thumb = createThumb(e.target.result);
                    previewWrap.appendChild(thumb);
                };
                reader.readAsDataURL(file);
            });

            dropZone.style.display = 'none';
        };

        inputImages.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');

            const files = e.dataTransfer.files;
            if (!files || files.length === 0) return;

            const validFiles = Array.from(files).filter(f => f.type.match('image.*'));
            if (validFiles.length === 0) {
                alert('File yang di-drop bukan gambar.');
                return;
            }

            const dt = new DataTransfer();
            validFiles.forEach(f => dt.items.add(f));
            inputImages.files = dt.files;

            handleFiles(validFiles);
        });
    };

    /* ============ MODAL PACKAGE (CREATE) ============ */
    const initCataloguePackageModals = () => {
        const backdrop   = document.getElementById('backdropCreatePackage');
        const modal      = document.getElementById('modalCreatePackage');
        const btnOpen    = document.getElementById('btnOpenCreatePackage');
        const btnClose   = document.getElementById('btnCloseCreatePackage');
        const btnClose2  = document.getElementById('btnCloseCreatePackage2');

        if (!backdrop || !modal) return;

        const showModal = () => {
            backdrop.classList.add('show');
            modal.classList.add('show');
        };

        const hideModal = () => {
            backdrop.classList.remove('show');
            modal.classList.remove('show');
        };

        if (btnOpen)   btnOpen.onclick   = showModal;
        if (btnClose)  btnClose.onclick  = hideModal;
        if (btnClose2) btnClose2.onclick = hideModal;

        backdrop.onclick = (e) => {
            if (e.target === backdrop) hideModal();
        };

        const inputImage = document.getElementById('inputPackageImage');
        const dropZone   = document.getElementById('uploadDropPackage');
        const previewBox = document.getElementById('previewPackageImage');

        if (!inputImage || !dropZone || !previewBox) return;

        const imgTag = previewBox.querySelector('img');

        const showPreview = (file) => {
            if (!file) {
                previewBox.style.display = 'none';
                imgTag.src = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert(`Ukuran file "${file.name}" terlalu besar. Maksimal 2MB.`);
                return;
            }

            const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                alert(`Format file "${file.name}" tidak didukung. Gunakan JPG, PNG, atau WEBP.`);
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                imgTag.src = e.target.result;
                previewBox.style.display = 'block';
            };
            reader.readAsDataURL(file);
        };

        inputImage.addEventListener('change', (e) => {
            const file = e.target.files[0];
            showPreview(file);
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');

            const files = e.dataTransfer.files;
            if (!files || files.length === 0) return;

            const file = files[0];
            if (!file.type.match('image.*')) {
                alert('File yang di-drop bukan gambar.');
                return;
            }

            const dt = new DataTransfer();
            dt.items.add(file);
            inputImage.files = dt.files;

            showPreview(file);
        });
    };

    /* ============ MODAL PACKAGE (EDIT) ============ */
    const initCataloguePackageEditModals = () => {
        const backdrop   = document.getElementById('backdropEditPackage');
        const modal      = document.getElementById('modalEditPackage');
        const btnClose   = document.getElementById('btnCloseEditPackage');
        const btnClose2  = document.getElementById('btnCloseEditPackage2');
        const form       = document.getElementById('editPackageForm');

        if (!backdrop || !modal || !form) return;

        const showModal = () => {
            backdrop.classList.add('show');
            modal.classList.add('show');
        };

        const hideModal = () => {
            backdrop.classList.remove('show');
            modal.classList.remove('show');
        };

        if (btnClose)  btnClose.onclick  = hideModal;
        if (btnClose2) btnClose2.onclick = hideModal;
        backdrop.onclick = (e) => {
            if (e.target === backdrop) hideModal();
        };

        document.querySelectorAll('.btn-edit-package').forEach(btn => {
            btn.onclick = () => {
                const id = btn.dataset.id;
                const baseUrl = form.dataset.baseUrl;

                form.action = `${baseUrl}/${id}`;

                document.getElementById('ep-nama').value       = btn.dataset.nama  || '';
                document.getElementById('ep-harga').value      = btn.dataset.harga || '';
                document.getElementById('ep-discount').value   = btn.dataset.discount || '';
                document.getElementById('ep-durasi').value     = btn.dataset.durasi || '';
                document.getElementById('ep-deskripsi').value  = btn.dataset.deskripsi || '';
                document.getElementById('ep-notes').value      = btn.dataset.notes || '';
                document.getElementById('ep-konsep').value     = btn.dataset.konsep || '';
                document.getElementById('ep-rules').value      = btn.dataset.rules || '';

                const previewBox = document.getElementById('previewPackageImageEdit');
                const imgTag     = previewBox?.querySelector('img');
                if (previewBox && imgTag) {
                    previewBox.style.display = 'none';
                    imgTag.src = '';
                }
                const fileInput = document.getElementById('ep-image');
                if (fileInput) fileInput.value = '';

                showModal();
            };
        });

        const inputImage = document.getElementById('ep-image');
        const dropZone   = document.getElementById('uploadDropPackageEdit');
        const previewBox = document.getElementById('previewPackageImageEdit');

        if (!inputImage || !dropZone || !previewBox) return;

        const imgTag = previewBox.querySelector('img');

        const showPreview = (file) => {
            if (!file) {
                previewBox.style.display = 'none';
                imgTag.src = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert(`Ukuran file "${file.name}" terlalu besar. Maksimal 2MB.`);
                return;
            }

            const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                alert(`Format file "${file.name}" tidak didukung. Gunakan JPG, PNG, atau WEBP.`);
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                imgTag.src = e.target.result;
                previewBox.style.display = 'block';
            };
            reader.readAsDataURL(file);
        };

        inputImage.addEventListener('change', (e) => {
            const file = e.target.files[0];
            showPreview(file);
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');

            const files = e.dataTransfer.files;
            if (!files || files.length === 0) return;

            const file = files[0];
            if (!file.type.match('image.*')) {
                alert('File yang di-drop bukan gambar.');
                return;
            }

            const dt = new DataTransfer();
            dt.items.add(file);
            inputImage.files = dt.files;

            showPreview(file);
        });
    };

    /* ============ MODAL TEMA BAJU (EDIT) ============ */
    const initCatalogueTemaEditModals = () => {
        const backdrop  = document.getElementById('backdropEditTema');
        const modal     = document.getElementById('modalEditTema');
        const btnClose  = document.getElementById('btnCloseEditTema');
        const btnClose2 = document.getElementById('btnCloseEditTema2');
        const form      = document.getElementById('editTemaForm');

        if (!backdrop || !modal || !form) return;

        const showModal = () => {
            backdrop.classList.add('show');
            modal.classList.add('show');
        };

        const hideModal = () => {
            backdrop.classList.remove('show');
            modal.classList.remove('show');
        };

        if (btnClose)  btnClose.onclick  = hideModal;
        if (btnClose2) btnClose2.onclick = hideModal;
        backdrop.onclick = (e) => {
            if (e.target === backdrop) hideModal();
        };

        const previewWrap = document.getElementById('previewImagesEdit');
        const fileInput   = document.getElementById('et-images');
        const dropZone    = document.getElementById('uploadDropTemaEdit');

        const renderThumbs = (urls = []) => {
            if (!previewWrap) return;
            previewWrap.innerHTML = '';
            urls.forEach(u => {
                const wrap = document.createElement('div');
                wrap.classList.add('thumb');
                const img = document.createElement('img');
                img.src = u;
                wrap.appendChild(img);
                previewWrap.appendChild(wrap);
            });
        };

        document.querySelectorAll('.btn-edit-tema').forEach(btn => {
            btn.onclick = () => {
                const id       = btn.dataset.id;
                const baseUrl  = form.dataset.baseUrl;

                form.action = `${baseUrl}/${id}`;

                document.getElementById('et-nama').value      = btn.dataset.nama      || '';
                document.getElementById('et-kode').value      = btn.dataset.kode      || '';
                document.getElementById('et-harga').value     = btn.dataset.harga     || '';
                document.getElementById('et-ukuran').value    = btn.dataset.ukuran    || '';
                document.getElementById('et-tipe').value      = btn.dataset.tipe      || '';
                document.getElementById('et-designer').value  = btn.dataset.designer  || '';
                document.getElementById('et-detail').value    = btn.dataset.detail    || '';

                if (fileInput) fileInput.value = '';

                if (previewWrap) {
                    try {
                        const raw = btn.dataset.images || '[]';
                        const paths = JSON.parse(raw);
                        const urls = paths.map(p => buildImageUrl(p));
                        renderThumbs(urls);
                    } catch (e) {
                        console.error('Gagal parse data-images tema:', e);
                        renderThumbs([]);
                    }
                }

                showModal();
            };
        });

        if (fileInput && dropZone && previewWrap) {
            const handleFiles = (files) => {
                if (!files || files.length === 0) {
                    return;
                }

                previewWrap.innerHTML = '';

                Array.from(files).forEach(file => {
                    if (file.size > 2 * 1024 * 1024) {
                        alert(`Ukuran file "${file.name}" terlalu besar. Maksimal 2MB.`);
                        return;
                    }

                    const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
                    if (!validTypes.includes(file.type)) {
                        alert(`Format file "${file.name}" tidak didukung. Gunakan JPG, PNG, atau WEBP.`);
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const wrap = document.createElement('div');
                        wrap.classList.add('thumb');
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        wrap.appendChild(img);
                        previewWrap.appendChild(wrap);
                    };
                    reader.readAsDataURL(file);
                });
            };

            fileInput.addEventListener('change', (e) => {
                handleFiles(e.target.files);
            });

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('drag-over');
            });

            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('drag-over');

                const files = e.dataTransfer.files;
                if (!files || files.length === 0) return;

                const validFiles = Array.from(files).filter(f => f.type.match('image.*'));
                if (validFiles.length === 0) {
                    alert('File yang di-drop bukan gambar.');
                    return;
                }

                const dt = new DataTransfer();
                validFiles.forEach(f => dt.items.add(f));
                fileInput.files = dt.files;

                handleFiles(validFiles);
            });
        }
    };

    const openBtns = document.querySelectorAll('.ticket-action');
    const closeBtns = document.querySelectorAll('.booking-modal-close');
    const backdrop = document.querySelectorAll('.booking-modal-backdrop');

    // Fungsi untuk membuka modal berdasarkan ID modal
    const openModal = (modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('is-open');  // Menampilkan modal
        }
    };

    // Fungsi untuk menutup modal berdasarkan ID modal
    const closeModal = (modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('is-open');  // Menyembunyikan modal
        }
    };

    // Event listener untuk tombol "Lihat Detail"
    openBtns.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal-target');  // Mendapatkan ID modal target
            openModal(modalId);  // Menampilkan modal dengan ID terkait
        });
    });

    // Event listener untuk menutup modal
    closeBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const modal = e.target.closest('.booking-modal');  // Menemukan modal terdekat
            const modalId = modal ? modal.id : '';
            closeModal(modalId);  // Menutup modal berdasarkan ID
        });
    });

    // Event listener untuk menutup modal jika backdrop di-klik
    backdrop.forEach(backdropElement => {
        backdropElement.addEventListener('click', (e) => {
            const modal = e.target.closest('.booking-modal');
            if (modal) {
                closeModal(modal.id);  // Menutup modal jika backdrop diklik
            }
        });
    });

    /* ============ BOOKING MODAL (CREATE / EDIT) ============ */
    const initBookingModals = () => {
        const backdrop = document.getElementById('bookingModal');
        const form     = document.getElementById('bookingForm');

        if (!backdrop || !form) return;

        const storeUrl    = form.dataset.storeUrl || '';
        const updateBase  = (form.dataset.updateBase || '').replace(/\/+$/, '');
        const defaultDate = form.dataset.defaultDate || '';

        const titleEl   = document.querySelector('[data-modal-title]');
        const openBtns  = document.querySelectorAll('.js-open-booking-modal');
        const closeBtns = backdrop.querySelectorAll('[data-close]');

        const filterBtn     = document.querySelector('.js-toggle-filter');
        const filterSection = document.querySelector('.filter-section');

        const removeMethodSpoof = () => {
            const oldMethod = form.querySelector('input[name="_method"]');
            if (oldMethod) oldMethod.remove();
        };

        const openModal = (mode = 'create', data = null) => {
            backdrop.setAttribute('aria-hidden', 'false');
            backdrop.classList.add('is-open');

            removeMethodSpoof();

            if (mode === 'create') {
                if (titleEl) titleEl.textContent = 'Booking Baru';
                if (storeUrl) form.action = storeUrl;
                form.reset();

                const dateInput   = form.querySelector('#f_date');
                const statusInput = form.querySelector('#f_status');

                if (dateInput)   dateInput.value   = defaultDate;
                if (statusInput) statusInput.value = 'submitted';
            } else if (mode === 'edit' && data) {
                if (titleEl) titleEl.textContent = 'Edit Booking';

                if (updateBase && data.id) {
                    form.action = `${updateBase}/${data.id}`;
                }

                const methodInput = document.createElement('input');
                methodInput.type  = 'hidden';
                methodInput.name  = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);

                const setVal = (selector, value) => {
                    const el = form.querySelector(selector);
                    if (el) el.value = value ?? '';
                };

                setVal('#f_nama_cpp',   data.nama_cpp);
                setVal('#f_phone_cpp',  data.phone_cpp);
                setVal('#f_nama_cpw',   data.nama_cpw);
                setVal('#f_phone_cpw',  data.phone_cpw);
                setVal('#f_date',       data.date);
                setVal('#f_start',      data.start);
                setVal('#f_end',        data.end);
                setVal('#f_package_id', data.package_id);
                setVal('#f_style',      data.style || 'Hair');
                setVal('#f_status',     data.status || 'submitted');
                setVal('#f_notes',      data.notes);
            }
        };

        const closeModal = () => {
            backdrop.setAttribute('aria-hidden', 'true');
            backdrop.classList.remove('is-open');
        };

        openBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const mode = btn.dataset.mode || (btn.classList.contains('btn-edit') ? 'edit' : 'create');

                if (mode === 'edit') {
                    const ds = btn.dataset;
                    openModal('edit', {
                        id:         ds.id,
                        nama_cpp:   ds.nama_cpp,
                        phone_cpp:  ds.phone_cpp,
                        nama_cpw:   ds.nama_cpw,
                        phone_cpw:  ds.phone_cpw,
                        date:       ds.date,
                        start:      ds.start,
                        end:        ds.end,
                        package_id: ds.package_id,
                        style:      ds.style,
                        status:     ds.status,
                        notes:      ds.notes
                    });
                } else {
                    openModal('create');
                }
            });
        });

        closeBtns.forEach(btn => {
            btn.addEventListener('click', closeModal);
        });

        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                closeModal();
            }
        });

        if (filterBtn && filterSection) {
            filterBtn.addEventListener('click', () => {
                filterSection.classList.toggle('is-open');
            });
        }
    };

    const initBookingCreateModal = () => {
    const backdrop = document.getElementById('bookingCreateBackdrop');
    const modal    = document.getElementById('bookingCreateModal');
    const btnOpen  = document.getElementById('btnOpenBooking');

        if (!backdrop || !modal || !btnOpen) return;

        const btnClose  = document.getElementById('btnCloseBookingCreate');
        const btnClose2 = document.getElementById('btnCloseBookingCreate2');

        let wizardInitialized = false; // ⬅️ kunci penting

        const showModal = () => {
            backdrop.classList.add('show');
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');

            if (!wizardInitialized) {
                requestAnimationFrame(() => {
                    if (typeof initBookingWizard === 'function') {
                        initBookingWizard();
                        wizardInitialized = true;
                    }
                });
            }
        };

        const hideModal = () => {
            backdrop.classList.remove('show');
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
        };

        btnOpen.addEventListener('click', showModal);
        btnClose  && btnClose.addEventListener('click', hideModal);
        btnClose2 && btnClose2.addEventListener('click', hideModal);

        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) hideModal();
        });
    };

    const initBookingEditModal = () => {
    const backdrop = document.getElementById('bookingEditBackdrop');
    const modal    = document.getElementById('bookingEditModal');

    const openBtns = document.querySelectorAll('.js-open-booking-edit');

    const closeBtns = [
        document.getElementById('btnCloseBookingEdit'),
        document.getElementById('btnCloseBookingEdit2')
    ];

        if (!backdrop || !modal || !openBtns.length) return;

        const show = () => {
            backdrop.classList.add('show');
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
        };

        const hide = () => {
            backdrop.classList.remove('show');
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
        };

        openBtns.forEach(btn => {
            btn.addEventListener('click', show);
        });

        closeBtns.forEach(btn => {
            if (btn) btn.addEventListener('click', hide);
        });

        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) hide();
        });
    };

    const initJadwalPesanan = () => {
    const calGrid   = document.getElementById('jpCalGrid');
    const hiddenDate = document.getElementById('jpSelectedDate');
    const studio1   = document.getElementById('jpStudio1');
    const studio2   = document.getElementById('jpStudio2');
    const dateLabel = document.getElementById('jpSelectedDateLabel');
    const todayBtn  = document.getElementById('jpTodayBtn');
    const calLabel  = document.getElementById('jpCalLabel');

        if (!calGrid || !hiddenDate || !studio1 || !studio2 || !dateLabel) return;

        /* ===== helper ===== */
        const parseISODate = (iso) => {
            const [y, m, d] = iso.split('-').map(Number);
            return new Date(y, m - 1, d);
        };

        const formatDateID = (iso) => {
            const d = parseISODate(iso);
            return d.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        };

        let current = parseISODate(hiddenDate.value);

        /* ===== RELOAD PAGE VIA SPA (INI INTINYA) ===== */
        const reloadPageByDate = (iso) => {
            const link = document.querySelector(
                '.sidebar .menu a[data-page="JadwalPesanan"]'
            );
            if (!link) return;

            const url = new URL(link.getAttribute('href'), window.location.origin);
            url.searchParams.set('date', iso);

            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('main-content').innerHTML = html;
                    initPageScripts(); // PENTING, BIAR JS KELOAD LAGI
                })
                .catch(err => console.error(err));
        };

        /* ===== KALENDAR ===== */
        const renderCalendar = () => {
            calGrid.innerHTML = '';

            const year  = current.getFullYear();
            const month = current.getMonth();
            calLabel.textContent = current.toLocaleString('id-ID', {
                month: 'long',
                year: 'numeric'
            });

            const firstDay    = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                calGrid.appendChild(document.createElement('div'));
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const btn = document.createElement('button');
                const iso = `${year}-${String(month + 1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;

                btn.textContent = d;
                if (iso === hiddenDate.value) btn.classList.add('active');

                btn.onclick = () => {
                    hiddenDate.value = iso;
                    current = parseISODate(iso);
                    dateLabel.textContent = formatDateID(iso);
                    renderCalendar();

                    reloadPageByDate(iso); // <<< INI YANG KAMU MAU
                    loadSlots();
                };

                calGrid.appendChild(btn);
            }
        };

        /* ===== SLOT (API KAMU, BIARKAN) ===== */
        const loadSlots = () => {
            studio1.innerHTML = '<small>Memuat slot...</small>';
            studio2.innerHTML = '<small>Memuat slot...</small>';

            fetch(`/executive/api/slots?date=${hiddenDate.value}`)
                .then(res => res.json())
                .then(slots => {
                    studio1.innerHTML = '';
                    studio2.innerHTML = '';

                    if (!Array.isArray(slots) || slots.length === 0) {
                        studio1.innerHTML = '<small>Tidak ada slot</small>';
                        studio2.innerHTML = '<small>Tidak ada slot</small>';
                        return;
                    }

                    slots.forEach(slot => {
                        const cls = slot.available ? 'slot-available' : 'slot-unavailable';

                        const s1 = document.createElement('div');
                        s1.className = `slot-item ${cls}`;
                        s1.textContent = slot.time;
                        studio1.appendChild(s1);

                        const s2 = document.createElement('div');
                        s2.className = `slot-item ${cls}`;
                        s2.textContent = slot.time;
                        studio2.appendChild(s2);
                    });
                })
                .catch(() => {
                    studio1.innerHTML = '<small style="color:red">Gagal load slot</small>';
                    studio2.innerHTML = '<small style="color:red">Gagal load slot</small>';
                });
        };

        /* ===== NAV ===== */
        document.getElementById('jpCalPrev')?.addEventListener('click', () => {
            current.setMonth(current.getMonth() - 1);
            renderCalendar();
        });

        document.getElementById('jpCalNext')?.addEventListener('click', () => {
            current.setMonth(current.getMonth() + 1);
            renderCalendar();
        });

        todayBtn?.addEventListener('click', () => {
            const iso = new Date().toISOString().slice(0, 10);
            hiddenDate.value = iso;
            current = parseISODate(iso);
            dateLabel.textContent = formatDateID(iso);
            renderCalendar();
            reloadPageByDate(iso);
            loadSlots();
        });

        /* ===== INIT ===== */
        dateLabel.textContent = formatDateID(hiddenDate.value);
        renderCalendar();
        loadSlots();
    };

    const initBookingDetailModal = () => {
    const backdrop = document.getElementById('bookingBackdrop');
    const modal    = document.getElementById('bookingModal');
    const openBtns = document.querySelectorAll('.js-open-booking-modal');

        if (!backdrop || !modal || openBtns.length === 0) return;

        const btnClose  = document.getElementById('btnCloseBooking');
        const btnClose2 = document.getElementById('btnCloseBooking2');

        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value || '-';
        };

        const openModal = (ds) => {
            setText('b_kode',   ds.kode);
            setText('b_status', ds.status);
            setText('b_paket',  ds.paket);
            setText('b_style',  ds.style);
            setText('b_tanggal',ds.tanggal);
            setText('b_slot',   ds.slot);
            setText('b_cpp',    ds.cpp);
            setText('b_cpw',    ds.cpw);
            setText('b_tema',   ds.tema);
            setText('b_tema2',  ds.tema2);
            setText('b_addon',  ds.addon);
            setText('b_total',  ds.total);
            setText('b_notes',  ds.notes);

            backdrop.classList.add('show');
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
        };

        const closeModal = () => {
            backdrop.classList.remove('show');
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
        };

        openBtns.forEach(btn => {
            btn.addEventListener('click', () => openModal(btn.dataset));
        });

        btnClose  && btnClose.addEventListener('click', closeModal);
        btnClose2 && btnClose2.addEventListener('click', closeModal);

        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) closeModal();
        });
    };

    /* ============ INIT PER PAGE ============ */
    const initPageScripts = () => {
        initUserModals();
        initCatalogueTemaModals();
        initCataloguePackageModals();
        initCataloguePackageEditModals();
        initCatalogueTemaEditModals();
        initBookingModals();
        initBookingDetailModal();
        initJadwalPesanan();
        initBookingCreateModal();
        initBookingEditModal();
    };

    /* ============ AJAX LOAD + HISTORY ============ */
    const setActiveMenuItem = (page) => {
        document.querySelectorAll('.sidebar .menu li').forEach(li => li.classList.remove('active'));

        const link = document.querySelector(`.sidebar .menu a[data-page="${page}"]`);
        if (!link) return;

        const li = link.parentElement;
        li.classList.add('active');

        const parentDropdown = li.closest('.menu-item.dropdown');
        if (parentDropdown && parentDropdown !== li) {
            parentDropdown.classList.add('active');
        }
    };

    const loadPage = (link, pushHistory = true) => {
        const url  = link.getAttribute('href');
        const page = link.dataset.page;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => {
                mainContent.innerHTML = html;

                if (pushHistory) {
                    history.pushState({ page }, '', url);
                }

                localStorage.setItem(LS_KEY, page);
                initPageScripts();
                setActiveMenuItem(page);
            })
            .catch(err => {
                console.error(err);
                mainContent.innerHTML = `
                    <div class="alert alert-danger">
                        Gagal memuat halaman ${page}
                    </div>`;
            });
    };

    menuLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            loadPage(link);
        });
    });

    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', (e) => {
            e.preventDefault();
            const parent = dropdown.parentElement;

            document.querySelectorAll('.menu-item.dropdown').forEach(item => {
                if (item !== parent) item.classList.remove('active');
            });

            parent.classList.toggle('active');
        });
    });

    setActiveMenuItem(serverPage);
    initPageScripts();
    localStorage.setItem(LS_KEY, serverPage);

    window.addEventListener('popstate', (event) => {
        const page = event.state?.page || serverPage;
        const link = document.querySelector(`.sidebar .menu a[data-page="${page}"]`);
        if (link) {
            loadPage(link, false);
        }
    });

    (function initBookingWizard() {
        const wizard = $('#bookingWizard');
        if (!wizard) return;

        const APP_ROUTES = window.APP_ROUTES || {};
        const API_SLOTS  = APP_ROUTES.apiSlots || '/executive/api/slots';
        const API_TEMA_BY_NAME = APP_ROUTES.apiTemaByName || '/executive/api/tema-by-name';

        // ====== Elemen dasar ======
        const steps      = $$('.step', wizard);
        const circles    = $$('.step-circle', wizard);
        const progress   = $('#bwProgress', wizard);
        const prevBtn    = $('#prevBtn', wizard);
        const nextBtn    = $('#nextBtn', wizard);
        const submitBtn  = $('#submitBtn', wizard);
        const summaryBox = $('#summaryBox', wizard);
        const hiddenBag  = $('#hiddenBag', wizard);

        let currentStep = 0;

        // Step 1
        const namaCpp   = $('#nama_cpp', wizard);
        const emailCpp  = $('#email_cpp', wizard);
        const phoneCpp  = $('#phone_cpp', wizard);
        const alamatCpp = $('#alamat_cpp', wizard);
        const namaCpw   = $('#nama_cpw', wizard);
        const emailCpw  = $('#email_cpw', wizard);
        const phoneCpw  = $('#phone_cpw', wizard);
        const alamatCpw = $('#alamat_cpw', wizard);

        // Step 2
        const selPackage   = $('#package_id', wizard);
        const inputDate    = $('#photoshoot_date', wizard);
        const selStyle     = $('#style', wizard);
        const slotList     = $('#slotList', wizard);

        const selTemaNama  = $('#tema_nama', wizard);
        const selTemaKode  = $('#tema_kode', wizard);
        const selTemaId    = $('#tema_id', wizard);

        const addonChecks       = $$('.addon-check', wizard);
        const extraSlotWrapper  = $('#extraSlotWrapper', wizard);
        const extraSlotList     = $('#extraSlotList', wizard);
        const extraTemaWrapper  = $('#extraTemaWrapper', wizard);
        const selTema2Nama      = $('#tema2_nama', wizard);
        const selTema2Kode      = $('#tema2_kode', wizard);
        const selTema2Id        = $('#tema2_id', wizard);

        const weddingInput = $('#wedding_date', wizard);
        const notesInput   = $('#notes', wizard);

        // Step 3
        const igCpp = $('#ig_cpp', wizard);
        const igCpw = $('#ig_cpw', wizard);
        const ttCpp = $('#tiktok_cpp', wizard);
        const ttCpw = $('#tiktok_cpw', wizard);

        // State
        let mainSlots  = [];
        let extraSlots = [];

        const state = {
            step1: {},
            step2: {},
            step3: {},
        };

        // ====== Helper umum ======
        function setStep(index) {
            currentStep = Math.max(
                0,
                Math.min(steps.length - 1, index)
            );
            steps.forEach((s, i) =>
                s.classList.toggle('active', i === currentStep)
            );
            circles.forEach((c, i) =>
                c.classList.toggle('active', i <= currentStep)
            );

            if (progress && circles.length > 1) {
                progress.style.width =
                    (currentStep / (circles.length - 1)) * 100 + '%';
            }

            if (prevBtn) prevBtn.disabled = currentStep === 0;
            if (nextBtn)
                nextBtn.textContent =
                    currentStep === steps.length - 1
                        ? 'Selesai'
                        : 'Lanjut';

            if (currentStep === steps.length - 1) {
                renderSummary();
            }

            wizard.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            });
        }

        function showError(msg) {
            alert(msg || 'Lengkapi data yang diperlukan.');
        }

        function splitTimeRange(range) {
            const [s, e] = String(range || '').split('-');
            return { start: (s || '').trim(), end: (e || '').trim() };
        }

        function getSelectedMainSlot() {
            const input = $('input[name="slot_main"]:checked', wizard);
            if (!input) return null;
            const time = input.dataset.time || '';
            const { start, end } = splitTimeRange(time);
            return {
                code: input.value,
                time,
                start,
                end,
            };
        }

        function getSelectedExtraSlot() {
            const input = $('input[name="slot_extra"]:checked', wizard);
            if (!input) return null;
            const time = input.dataset.time || '';
            const { start, end } = splitTimeRange(time);
            return {
                code: input.value,
                time,
                start,
                end,
            };
        }

        // ====== Tema helper (filter kode berdasarkan nama + exclude kode tertentu) ======
        function filterTemaKode(selectKode, nama, excludeKode = null) {
            if (!selectKode) return;

            const opts = Array.from(selectKode.options);
            selectKode.value = '';
            let anyVisible   = false;

            opts.forEach((opt, idx) => {
                if (idx === 0) {
                    opt.style.display = '';
                    return;
                }
                const optNama   = opt.dataset.nama || '';
                const shouldShow =
                    !!nama &&
                    optNama === nama &&
                    (!excludeKode || opt.value !== excludeKode);

                opt.style.display = shouldShow ? '' : 'none';
                if (shouldShow) anyVisible = true;
            });

            selectKode.disabled = !anyVisible;
        }

        function applyTemaAvailabilityToIdSelect(selectEl, unavailableSet, excludeKode = null) {
        if (!selectEl) return;
        const opts = Array.from(selectEl.options);

        opts.forEach((opt, idx) => {
            if (idx === 0) {
                // opsi placeholder
                opt.disabled = false;
                opt.classList.remove('unavail');
                return;
            }

            const kode = opt.dataset.kode || '';
            if (!kode) {
                // kalau kode kosong, biarin saja
                opt.disabled = false;
                opt.classList.remove('unavail');
                return;
            }

            // Jangan izinkan pilih kode yang sama dengan exclude (tema utama untuk tema2)
            if (excludeKode && kode === excludeKode) {
                opt.disabled = true;
                opt.classList.add('unavail');
                return;
            }

            if (unavailableSet.has(String(kode))) {
                opt.disabled = true;
                opt.classList.add('unavail');
            } else {
                opt.disabled = false;
                opt.classList.remove('unavail');
            }
        });

        // Kalau yang lagi kepilih ternyata jadi disabled, kosongkan
        if (
            selectEl.value &&
            selectEl.selectedOptions[0] &&
            selectEl.selectedOptions[0].disabled
        ) {
            selectEl.value = '';
        }
    }

        // ====== ADDON helper ======
        function getSelectedAddons() {
            return addonChecks
                .filter(ch => ch.checked)
                .map(ch => ({
                    id: ch.dataset.id,
                    kategori: ch.dataset.kategori,
                    harga:
                        parseInt(ch.dataset.harga || '0', 10) || 0,
                    durasi:
                        parseInt(ch.dataset.durasi || '0', 10) || 0,
                    name:
                        ch
                            .closest('.addon-item')
                            ?.querySelector('.addon-name')
                            ?.textContent.trim() || '',
                }));
        }

        function getAddonByKategori(kat) {
            return getSelectedAddons().filter(
                a => String(a.kategori) === String(kat)
            );
        }

        function getAddonSlot() {
            const list = getAddonByKategori(1);
            return list.length ? list[0] : null; // paksa cuma 1 addon slot
        }

        function getAddonTema() {
            const list = getAddonByKategori(2);
            return list.length ? list[0] : null;
        }

        function getExtraMinutesFromAddons() {
            const slotAddon = getAddonSlot();
            return slotAddon ? slotAddon.durasi : 0;
        }

        function computeAddonTotal() {
            return getSelectedAddons().reduce(
                (sum, a) => sum + a.harga,
                0
            );
        }

        // Kalau user centang addon kategori 1 lebih dari satu, kita jadikan single-choice (radio style).
        function normalizeAddonSlotSelection(changedCheckbox) {
            if (
                !changedCheckbox ||
                changedCheckbox.dataset.kategori !== '1'
            )
                return;
            if (!changedCheckbox.checked) return;
            addonChecks.forEach(ch => {
                if (
                    ch !== changedCheckbox &&
                    ch.dataset.kategori === '1'
                ) {
                    ch.checked = false;
                }
            });
        }

        // ====== Load slot utama ======
        async function loadMainSlots() {
            const pkg  = selPackage?.value;
            const date = inputDate?.value;

            if (!slotList) return;

            if (!pkg || !date) {
                mainSlots          = [];
                slotList.innerHTML =
                    '<p style="opacity:.7">Pilih paket & tanggal untuk melihat slot.</p>';
                return;
            }

            slotList.innerHTML =
                '<p style="opacity:.7">Memuat slot...</p>';

            try {
                const url = new URL(
                    API_SLOTS,
                    window.location.origin
                );
                url.searchParams.set('package_id', pkg);
                url.searchParams.set('date', date);

                const res = await fetch(url.toString(), {
                    headers: { Accept: 'application/json' },
                });
                if (!res.ok) throw new Error('Gagal memuat slot');

                const data = await res.json();
                mainSlots  = Array.isArray(data) ? data : [];
                renderMainSlots();
            } catch (err) {
                mainSlots          = [];
                slotList.innerHTML =
                    '<p style="color:#b91c1c">Gagal memuat slot. Coba lagi.</p>';
            }
        }

       async function refreshTemaKodeUtama() {
            if (!selTemaNama || !selTemaKode) return;

            const nama = selTemaNama.value || '';
            const date = inputDate?.value || '';
            const main = getSelectedMainSlot();

            // Filter kode berdasarkan nama (tanpa exclude)
            filterTemaKode(selTemaKode, nama, null);

            // Kalau belum ada tanggal atau slot utama, cukup filter nama saja
            if (!nama || !date || !main) {
                Array.from(selTemaKode.options).forEach((opt, idx) => {
                    if (idx === 0) return;
                    opt.disabled = false;
                    opt.classList.remove('unavail');
                });
                // Untuk select by ID (#tema_id), juga bebas semua
                if (selTemaId) {
                    Array.from(selTemaId.options).forEach((opt, idx) => {
                        if (idx === 0) return;
                        opt.disabled = false;
                        opt.classList.remove('unavail');
                    });
                }
                return;
            }

            try {
                const url = new URL(API_TEMA_BY_NAME, window.location.origin);
                url.searchParams.set('nama', nama);
                url.searchParams.set('date', date);
                url.searchParams.set('start', main.start);
                url.searchParams.set('end', main.end);

                const res = await fetch(url.toString(), {
                    headers: { Accept: 'application/json' },
                });
                if (!res.ok) return;

                const data = await res.json();
                const unavailable = new Set(
                    (Array.isArray(data) ? data : [])
                        .filter(t => t.available === false)
                        .map(t => String(t.kode))
                );

                // Disable di select KODE utama
                Array.from(selTemaKode.options).forEach((opt, idx) => {
                    if (idx === 0 || !opt.value) return;

                    if (unavailable.has(String(opt.value))) {
                        opt.disabled = true;
                        opt.classList.add('unavail');
                    } else {
                        opt.disabled = false;
                        opt.classList.remove('unavail');
                    }
                });

                // Disable juga di select "by ID" (#tema_id) berdasarkan data-kode
                if (selTemaId) {
                    applyTemaAvailabilityToIdSelect(selTemaId, unavailable, null);
                }
            } catch (e) {
                // kalau error API, kita biarin, backend tetap punya validasi
            }
        }

        async function refreshTemaKodeTambahan() {
            if (!selTema2Nama || !selTema2Kode) return;
            if (extraTemaWrapper && extraTemaWrapper.style.display === 'none') return;

            const nama2 = selTema2Nama.value || '';
            const date  = inputDate?.value || '';
            const main  = getSelectedMainSlot();
            const temaUtama   = resolveTemaUtama();
            const excludeKode = temaUtama.tema_kode || '';

            // Filter dasar: by nama + exclude kode utama (di select KODE tambahan)
            filterTemaKode(selTema2Kode, nama2, excludeKode);

            // Kalau belum lengkap (nama/tanggal/slot utama), reset disable saja
            if (!nama2 || !date || !main) {
                Array.from(selTema2Kode.options).forEach((opt, idx) => {
                    if (idx === 0) return;
                    opt.disabled = false;
                    opt.classList.remove('unavail');
                });
                if (selTema2Id) {
                    Array.from(selTema2Id.options).forEach((opt, idx) => {
                        if (idx === 0) return;
                        opt.disabled = false;
                        opt.classList.remove('unavail');
                    });
                }
                return;
            }

            try {
                const url = new URL(API_TEMA_BY_NAME, window.location.origin);
                url.searchParams.set('nama', nama2);
                url.searchParams.set('date', date);
                url.searchParams.set('start', main.start);
                url.searchParams.set('end', main.end);
                if (excludeKode) {
                    url.searchParams.set('exclude_kode', excludeKode);
                }

                const res = await fetch(url.toString(), {
                    headers: { Accept: 'application/json' },
                });
                if (!res.ok) return;

                const data = await res.json();
                const unavailable = new Set(
                    (Array.isArray(data) ? data : [])
                        .filter(t => t.available === false)
                        .map(t => String(t.kode))
                );

                // Apply ke select KODE tema tambahan
                Array.from(selTema2Kode.options).forEach((opt, idx) => {
                    if (idx === 0 || !opt.value) return;

                    const kode = String(opt.value);

                    // Jangan izinkan kode yang sama dengan tema utama
                    if (excludeKode && kode === excludeKode) {
                        opt.disabled = true;
                        opt.classList.add('unavail');
                        return;
                    }

                    if (unavailable.has(kode)) {
                        opt.disabled = true;
                        opt.classList.add('unavail');
                    } else {
                        opt.disabled = false;
                        opt.classList.remove('unavail');
                    }
                });

                // Apply juga ke select by ID (#tema2_id) berdasarkan data-kode
                if (selTema2Id) {
                    applyTemaAvailabilityToIdSelect(selTema2Id, unavailable, excludeKode);
                }
            } catch (e) {
                // diam saja kalau error, backend tetap validasi
            }
        }

        function renderMainSlots() {
            if (!slotList) return;

            if (!mainSlots.length) {
                slotList.innerHTML =
                    '<p style="opacity:.7">Tidak ada slot tersedia.</p>';
                return;
            }

            slotList.innerHTML = mainSlots
                .map(s => {
                    const id = `slot_main_${s.code}`;
                    return `
                <label class="slot-item ${
                    s.available ? '' : 'unavail'
                }" for="${id}">
                    <input type="radio"
                           name="slot_main"
                           id="${id}"
                           value="${s.code}"
                           data-time="${s.time}"
                           ${s.available ? '' : 'disabled'}>
                    <span>${s.time}</span>
                    <small style="margin-left:auto;opacity:.7">${s.code}</small>
                </label>
            `;
                })
                .join('');
        }

        // ====== Load slot ekstra (addon kategori 1) ======
        async function loadExtraSlots() {
            if (!extraSlotWrapper || !extraSlotList) return;

            const slotAddon = getAddonSlot();
            const date      = inputDate?.value;
            const main      = getSelectedMainSlot();
            const extraMin  = getExtraMinutesFromAddons();

            if (!slotAddon || !extraMin) {
                extraSlots = [];
                extraSlotWrapper.style.display = 'none';
                return;
            }

            if (!date || !main) {
                extraSlotWrapper.style.display = 'block';
                extraSlotList.innerHTML =
                    '<p style="opacity:.7">Pilih tanggal dan slot utama terlebih dahulu.</p>';
                return;
            }

            extraSlotWrapper.style.display = 'block';
            extraSlotList.innerHTML =
                '<p style="opacity:.7">Memuat slot tambahan...</p>';

            try {
                const url = new URL(API_SLOTS, window.location.origin);
                url.searchParams.set('date', date);
                url.searchParams.set('durasi', String(extraMin)); // durasi addon (mis. 60)
                url.searchParams.set('exclude', main.code);       // supaya kode slot utama nggak kepilih
                url.searchParams.set('main_start', main.start);   // <= BARU
                url.searchParams.set('main_end', main.end);       // <= BARU

                const res = await fetch(url.toString(), {
                    headers: { Accept: 'application/json' },
                });
                if (!res.ok) throw new Error('Gagal memuat slot tambahan');

                const data = await res.json();
                extraSlots = Array.isArray(data) ? data : [];
                renderExtraSlots();
            } catch (err) {
                extraSlots = [];
                extraSlotList.innerHTML =
                    '<p style="color:#b91c1c">Gagal memuat slot tambahan. Coba lagi.</p>';
            }
        }

        function renderExtraSlots() {
            if (!extraSlotList) return;

            const main = getSelectedMainSlot();

            if (!extraSlots.length) {
                extraSlotList.innerHTML =
                    '<p style="opacity:.7">Tidak ada slot tambahan tersedia.</p>';
                return;
            }

            extraSlotList.innerHTML = extraSlots
                .map(s => {
                    const id       = `slot_extra_${s.code}`;
                    const disabled =
                        !s.available || (main && s.code === main.code);
                    return `
                <label class="slot-item ${disabled ? 'unavail' : ''}" for="${id}">
                    <input type="radio"
                        name="slot_extra"
                        id="${id}"
                        value="${s.code}"
                        data-time="${s.time}"
                        ${disabled ? 'disabled' : ''}>
                    <span>${s.time}</span>
                    <small style="margin-left:auto;opacity:.7">${s.code}</small>
                </label>
            `;
                })
                .join('');
        }

        // ====== Simpan state per step ======
        function saveStep1() {
            const s1 = {
                nama_cpp: (namaCpp.value || '').trim(),
                email_cpp: (emailCpp.value || '').trim(),
                phone_cpp: (phoneCpp.value || '').trim(),
                alamat_cpp: (alamatCpp.value || '').trim(),
                nama_cpw: (namaCpw.value || '').trim(),
                email_cpw: (emailCpw.value || '').trim(),
                phone_cpw: (phoneCpw.value || '').trim(),
                alamat_cpw: (alamatCpw.value || '').trim(),
            };

            if (
                !s1.nama_cpp ||
                !s1.phone_cpp ||
                !s1.nama_cpw ||
                !s1.phone_cpw
            ) {
                throw new Error(
                    'Nama & No. Telp CPP/CPW wajib diisi.'
                );
            }

            state.step1 = s1;
        }

        function resolveTemaUtama() {
            let tema_id   = '';
            let tema_nama = '';
            let tema_kode = '';

            if (selTemaId && selTemaId.value) {
                const opt = selTemaId.selectedOptions[0];
                tema_id   = selTemaId.value;
                tema_nama = opt.dataset.nama || '';
                tema_kode = opt.dataset.kode || '';
            } else if (selTemaKode && selTemaKode.value) {
                const opt = selTemaKode.selectedOptions[0];
                tema_kode = selTemaKode.value;
                tema_nama =
                    opt.dataset.nama || selTemaNama.value || '';
                tema_id = opt.dataset.id || '';
            } else if (selTemaNama && selTemaNama.value) {
                tema_nama = selTemaNama.value;
            }

            return { tema_id, tema_nama, tema_kode };
        }

        function resolveTemaAddon() {
            let tema2_id   = '';
            let tema2_nama = '';
            let tema2_kode = '';

            if (selTema2Id && selTema2Id.value) {
                const opt = selTema2Id.selectedOptions[0];
                tema2_id   = selTema2Id.value;
                tema2_nama = opt.dataset.nama || '';
                tema2_kode = opt.dataset.kode || '';
            } else if (selTema2Kode && selTema2Kode.value) {
                const opt = selTema2Kode.selectedOptions[0];
                tema2_kode = selTema2Kode.value;
                tema2_nama =
                    opt.dataset.nama || selTema2Nama.value || '';
                tema2_id = opt.dataset.id || '';
            } else if (selTema2Nama && selTema2Nama.value) {
                tema2_nama = selTema2Nama.value;
            }

            return { tema2_id, tema2_nama, tema2_kode };
        }

        function saveStep2() {
            const pkgId = selPackage?.value || '';
            const date  = inputDate?.value || '';
            const style = selStyle?.value || '';
            const main  = getSelectedMainSlot();

            if (!pkgId) throw new Error('Pilih paket terlebih dahulu.');
            if (!date)
                throw new Error('Pilih tanggal photoshoot.');
            if (!style)
                throw new Error('Pilih style (Hijab/HairDo).');
            if (!main)
                throw new Error('Pilih salah satu slot utama.');

            const { tema_id, tema_nama, tema_kode } =
                resolveTemaUtama();

            const slotAddon = getAddonSlot();
            const temaAddon = getAddonTema();

            const extraSlot = getSelectedExtraSlot();
            const extraMin  = getExtraMinutesFromAddons();

            if (slotAddon && (!extraSlot || !extraSlot.code)) {
                throw new Error(
                    'Addon slot waktu dipilih, silakan pilih slot tambahan.'
                );
            }

            const tema2 = resolveTemaAddon();

            if (temaAddon && !tema2.tema2_kode) {
                throw new Error(
                    'Addon tema baju dipilih, silakan pilih tema baju tambahan.'
                );
            }

            if (
                temaAddon &&
                tema2.tema2_kode &&
                tema_kode &&
                tema2.tema2_kode === tema_kode
            ) {
                throw new Error(
                    'Tema tambahan tidak boleh sama dengan tema utama.'
                );
            }

            state.step2 = {
                package_id: pkgId,
                photoshoot_date: date,
                style,
                slot_code: main.code,
                photoshoot_slot: main.time,
                start_time: main.start,
                end_time: main.end,
                tema_id,
                tema_nama,
                tema_kode,
                wedding_date: weddingInput?.value || '',
                notes: (notesInput?.value || '').trim(),

                // Addon slot
                extra_slot_code: extraSlot ? extraSlot.code : '',
                extra_photoshoot_slot: extraSlot
                    ? extraSlot.time
                    : '',
                extra_start_time: extraSlot
                    ? extraSlot.start
                    : '',
                extra_end_time: extraSlot ? extraSlot.end : '',
                extra_minutes: extraMin || 0,

                // Addon tema
                tema2_id: tema2.tema2_id,
                tema2_nama: tema2.tema2_nama,
                tema2_kode: tema2.tema2_kode,

                // Daftar addon dipilih
                addons: getSelectedAddons(),
            };
        }

        function saveStep3() {
            state.step3 = {
                ig_cpp: (igCpp?.value || '').trim(),
                ig_cpw: (igCpw?.value || '').trim(),
                tiktok_cpp: (ttCpp?.value || '').trim(),
                tiktok_cpw: (ttCpw?.value || '').trim(),
                sosmed_lain: null,
            };
        }

        // ====== Summary & hidden inputs ======
        function renderSummary() {
            if (!summaryBox) return;

            const s1 = state.step1 || {};
            const s2 = state.step2 || {};
            const s3 = state.step3 || {};

            const pkgText =
                selPackage?.selectedOptions[0]?.textContent.trim() ||
                '-';

            let temaText = '-';
            if (s2.tema_kode) {
                temaText = `${s2.tema_nama || '-'} (${
                    s2.tema_kode
                })`;
            } else if (s2.tema_nama) {
                temaText = s2.tema_nama;
            }

            let tema2Text = '-';
            if (s2.tema2_kode) {
                tema2Text = `${s2.tema2_nama || '-'} (${
                    s2.tema2_kode
                })`;
            } else if (s2.tema2_nama) {
                tema2Text = s2.tema2_nama;
            }

            const selectedAddons = s2.addons || [];
            const addonTotal     = selectedAddons.reduce(
                (sum, a) => sum + a.harga,
                0
            );

            const addonListHtml = selectedAddons.length
                ? `<ul style="margin:6px 0 0 18px;padding:0">
                   ${selectedAddons
                       .map(
                           a => `
                       <li>${a.name} <span style="opacity:.7">(Kategori ${a.kategori})</span></li>
                   `
                       )
                       .join('')}
               </ul>`
                : '<p style="margin:6px 0 0;opacity:.7">Tidak ada addon dipilih.</p>';

            const extraSlotInfo = s2.extra_slot_code
                ? `${s2.extra_photoshoot_slot} (${s2.extra_slot_code})`
                : '-';

            summaryBox.innerHTML = `
            <div class="grid-2">
                <div class="summary-card">
                    <h4>Identitas</h4>
                    <p><strong>CPP:</strong> ${s1.nama_cpp} | ${
                s1.phone_cpp
            } | ${s1.email_cpp || '-'} | ${
                s1.alamat_cpp || '-'
            }</p>
                    <p><strong>CPW:</strong> ${s1.nama_cpw} | ${
                s1.phone_cpw
            } | ${s1.email_cpw || '-'} | ${
                s1.alamat_cpw || '-'
            }</p>
                </div>
                <div class="summary-card">
                    <h4>Detail Booking</h4>
                    <p><strong>Paket:</strong> ${pkgText}</p>
                    <p><strong>Tanggal:</strong> ${
                        s2.photoshoot_date
                    }</p>
                    <p><strong>Slot Utama:</strong> ${
                        s2.photoshoot_slot
                    } (${s2.slot_code})</p>
                    <p><strong>Slot Tambahan:</strong> ${extraSlotInfo}</p>
                    <p><strong>Style:</strong> ${s2.style}</p>
                    <p><strong>Tema Utama:</strong> ${temaText}</p>
                    <p><strong>Tema Tambahan:</strong> ${tema2Text}</p>
                    <p><strong>Wedding Date:</strong> ${
                        s2.wedding_date || '-'
                    }</p>
                    <p><strong>Notes:</strong> ${
                        s2.notes || '-'
                    }</p>
                    <p><strong>Extra Minutes:</strong> ${
                        s2.extra_minutes || 0
                    } menit</p>
                </div>
            </div>
            <div class="summary-card" style="margin-top:12px">
                <h4>Sosial Media</h4>
                <p><strong>CPP:</strong> IG ${
                    s3.ig_cpp || '-'
                } | TikTok ${s3.tiktok_cpp || '-'}</p>
                <p><strong>CPW:</strong> IG ${
                    s3.ig_cpw || '-'
                } | TikTok ${s3.tiktok_cpw || '-'}</p>
            </div>
            <div class="summary-card" style="margin-top:12px">
                <h4>Addon</h4>
                ${addonListHtml}
                <p style="margin-top:8px"><strong>Total Addon (estimasi):</strong> Rp ${addonTotal.toLocaleString(
                    'id-ID'
                )}</p>
            </div>
        `;
        }

        function injectHiddenInputs() {
            if (!hiddenBag) return;
            hiddenBag.innerHTML = '';

            const addHidden = (name, value) => {
                const input = document.createElement('input');
                input.type  = 'hidden';
                input.name  = name;
                input.value = value ?? '';
                hiddenBag.appendChild(input);
            };

            const s1 = state.step1 || {};
            const s2 = state.step2 || {};
            const s3 = state.step3 || {};

            // Step 1
            Object.entries(s1).forEach(([k, v]) => addHidden(k, v));

            // Step 2 - utama
            addHidden('package_id', s2.package_id);
            addHidden('photoshoot_date', s2.photoshoot_date);
            addHidden('slot_code', s2.slot_code);
            addHidden('photoshoot_slot', s2.photoshoot_slot);
            addHidden('start_time', s2.start_time);
            addHidden('end_time', s2.end_time);
            addHidden('style', s2.style);
            addHidden('tema_id', s2.tema_id);
            addHidden('tema_nama', s2.tema_nama);
            addHidden('tema_kode', s2.tema_kode);
            addHidden('wedding_date', s2.wedding_date);
            addHidden('notes', s2.notes);

            // Extra slot
            addHidden('extra_slot_code', s2.extra_slot_code);
            addHidden(
                'extra_photoshoot_slot',
                s2.extra_photoshoot_slot
            );
            addHidden('extra_start_time', s2.extra_start_time);
            addHidden('extra_end_time', s2.extra_end_time);
            addHidden('extra_minutes', s2.extra_minutes);

            // Tema tambahan
            addHidden('tema2_id', s2.tema2_id);
            addHidden('tema2_nama', s2.tema2_nama);
            addHidden('tema2_kode', s2.tema2_kode);

            // Step 3
            addHidden('ig_cpp', s3.ig_cpp);
            addHidden('ig_cpw', s3.ig_cpw);
            addHidden('tiktok_cpp', s3.tiktok_cpp);
            addHidden('tiktok_cpw', s3.tiktok_cpw);
            addHidden(
                'sosmed_lain',
                s3.sosmed_lain ? JSON.stringify(s3.sosmed_lain) : ''
            );

            // Addons[]
            (s2.addons || []).forEach(a =>
                addHidden('addons[]', a.id)
            );
        }

        // ====== Event binding ======
        const debounce = (fn, ms = 300) => {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), ms);
            };
        };

        const debouncedLoadMainSlots  = debounce(loadMainSlots, 250);
        const debouncedLoadExtraSlots = debounce(
            loadExtraSlots,
            250
        );

        selPackage &&
            selPackage.addEventListener('change', () => {
                debouncedLoadMainSlots();
                debouncedLoadExtraSlots();
            });

        inputDate &&
            inputDate.addEventListener('change', () => {
                debouncedLoadMainSlots();
                debouncedLoadExtraSlots();
                refreshTemaKodeUtama();
                refreshTemaKodeTambahan();
            });
        wizard.addEventListener('change', e => {
            const t = e.target;

            // Slot utama berubah -> refresh extra slot
            if (t.name === 'slot_main') {
                debouncedLoadExtraSlots();
                refreshTemaKodeUtama();
                refreshTemaKodeTambahan();
            }

            // Addon berubah
            if (
                t.classList &&
                t.classList.contains('addon-check')
            ) {
                normalizeAddonSlotSelection(t);

                const slotAddon = getAddonSlot();
                const temaAddon = getAddonTema();

                // Panel extra slot
                if (slotAddon) {
                    extraSlotWrapper.style.display = 'block';
                    debouncedLoadExtraSlots();
                } else if (extraSlotWrapper) {
                    extraSlotWrapper.style.display = 'none';
                    extraSlotList.innerHTML =
                        '<p style="opacity:.7">\
                    Pilih addon "Tambah Slot Waktu" & slot utama terlebih dahulu.\
                </p>';
                    const pickedExtra = $(
                        'input[name="slot_extra"]:checked',
                        wizard
                    );
                    if (pickedExtra) pickedExtra.checked = false;
                }

                // Panel extra tema
                if (temaAddon && extraTemaWrapper) {
                    extraTemaWrapper.style.display = 'block';
                } else if (extraTemaWrapper) {
                    extraTemaWrapper.style.display = 'none';
                    if (selTema2Nama) selTema2Nama.value = '';
                    if (selTema2Kode) {
                        selTema2Kode.value    = '';
                        selTema2Kode.disabled = true;
                    }
                    if (selTema2Id) selTema2Id.value = '';
                }

                if (currentStep === steps.length - 1) {
                    renderSummary();
                }
            }
        });

        // Tema utama: filter kode berdasarkan nama
       selTemaNama &&
        selTemaNama.addEventListener('change', () => {
            refreshTemaKodeUtama();
        });

        // Tema tambahan: filter kode berdasarkan nama & exclude kode utama
        selTema2Nama &&
        selTema2Nama.addEventListener('change', () => {
            refreshTemaKodeTambahan();
        });

        // Kalau tema utama diganti dan panel tema tambahan aktif, update juga filter kode tema2
        [selTemaKode, selTemaId].forEach(el => {
            if (!el) return;
            el.addEventListener('change', () => {
                if (
                    !extraTemaWrapper ||
                    extraTemaWrapper.style.display === 'none'
                )
                    return;
                refreshTemaKodeTambahan();
            });
        });

        // Navigasi Next/Prev
        nextBtn &&
            nextBtn.addEventListener('click', () => {
                try {
                    if (currentStep === 0) {
                        saveStep1();
                        setStep(1);
                    } else if (currentStep === 1) {
                        saveStep2();
                        setStep(2);
                    } else if (currentStep === 2) {
                        saveStep3();
                        setStep(3);
                    } else if (currentStep === 3) {
                        injectHiddenInputs();
                        $('#finalForm', wizard)?.submit();
                    }
                } catch (err) {
                    showError(err.message);
                }
            });

        prevBtn &&
            prevBtn.addEventListener('click', () => {
                setStep(currentStep - 1);
            });

        submitBtn &&
            submitBtn.addEventListener('click', () => {
                injectHiddenInputs();
                $('#finalForm', wizard)?.submit();
            });

        // Init
        setStep(0);
    })();
});
