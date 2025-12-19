// public/js/executive.js
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


    const initJadwalFilter = () => {
        const calGrid   = document.getElementById('jpCalGrid');
        const calLabel  = document.getElementById('jpCalLabel');
        const dateLabel = document.getElementById('jpSelectedDateLabel');
        const todayBtn  = document.getElementById('jpTodayBtn');

        const studio1   = document.getElementById('jpStudio1');
        const studio2   = document.getElementById('jpStudio2');
        const hiddenDate= document.getElementById('jpSelectedDate');

        if (!calGrid || !studio1 || !studio2 || !hiddenDate) return;

        let current = parseISODate(hiddenDate.value);

        /* ===== CALENDAR ===== */
        const renderCalendar = () => {
            calGrid.innerHTML = '';

            const year  = current.getFullYear();
            const month = current.getMonth();

            calLabel.textContent = current.toLocaleString('id-ID', {
                month: 'long',
                year: 'numeric'
            });

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                calGrid.appendChild(document.createElement('div'));
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const btn = document.createElement('button');
                const iso = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;

                btn.textContent = d;
                if (iso === hiddenDate.value) btn.classList.add('active');

                btn.onclick = () => {
                    hiddenDate.value = iso;
                    current = parseISODate(iso);
                    dateLabel.textContent = formatDateID(iso);
                    renderCalendar();
                    loadSlots();
                };

                calGrid.appendChild(btn);
            }
        };

        /* ===== LOAD SLOT ===== */
        const loadSlots = () => {
            studio1.innerHTML = '';
            studio2.innerHTML = '';

            fetch(`/api/slots?date=${hiddenDate.value}`)
                .then(res => res.json())
                .then(slots => {
                    slots.forEach(s => {
                        const cls = s.available ? 'slot-available' : 'slot-unavailable';

                        const el1 = document.createElement('div');
                        el1.className = `slot-item ${cls}`;
                        el1.textContent = s.time;

                        const el2 = el1.cloneNode(true);

                        studio1.appendChild(el1);
                        studio2.appendChild(el2);
                    });
                })
                .catch(err => console.error('Slot API error:', err));
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
            const iso = new Date().toISOString().slice(0,10);
            hiddenDate.value = iso;
            current = parseISODate(iso);
            dateLabel.textContent = formatDateID(iso);
            renderCalendar();
            loadSlots();
        });

        /* ===== INIT ===== */
        dateLabel.textContent = formatDateID(hiddenDate.value);
        renderCalendar();
        loadSlots();
    };

    /* ===== CALL ===== */
    initJadwalFilter();


    /* ============ BOOKING DETAIL MODAL ============ */
    const initBookingDetailModal = () => {
        const backdrop = document.getElementById('bookingDetailModal');
        if (!backdrop) return;

        const openBtns  = document.querySelectorAll('.js-open-booking-detail');
        const closeBtns = backdrop.querySelectorAll('[data-close-detail]');

        const openModal = (data) => {
            backdrop.classList.add('is-open');
            backdrop.setAttribute('aria-hidden', 'false');

            const setText = (id, value) => {
                const el = document.getElementById(id);
                if (el) el.textContent = value || '-';
            };

            setText('d_kode',            data.kode);
            setText('d_status',          data.status);
            setText('d_tanggal',         data.tanggal);
            setText('d_jam',             data.jam);
            setText('d_created_at',      data.created_at);

            setText('d_nama_cpp',        data.nama_cpp);
            setText('d_email_cpp',       data.email_cpp);
            setText('d_phone_cpp',       data.phone_cpp);
            setText('d_alamat_cpp',      data.alamat_cpp);

            setText('d_nama_cpw',        data.nama_cpw);
            setText('d_email_cpw',       data.email_cpw);
            setText('d_phone_cpw',       data.phone_cpw);
            setText('d_alamat_cpw',      data.alamat_cpw);

            setText('d_ig_cpp',          data.ig_cpp);
            setText('d_ig_cpw',          data.ig_cpw);
            setText('d_tiktok_cpp',      data.tiktok_cpp);
            setText('d_tiktok_cpw',      data.tiktok_cpw);

            const sosmedEl = document.getElementById('d_sosmed_lain');
            if (sosmedEl) {
                if (data.sosmed_lain) {
                    try {
                        const parsed = JSON.parse(data.sosmed_lain);
                        sosmedEl.textContent = JSON.stringify(parsed, null, 2);
                    } catch (e) {
                        sosmedEl.textContent = data.sosmed_lain;
                    }
                } else {
                    sosmedEl.textContent = '-';
                }
            }

            setText('d_package',         data.package);
            setText('d_package_price',   data.package_price);
            setText('d_addons_total',    data.addons_total);
            setText('d_grand_total',     data.grand_total);

            setText('d_slot_code',              data.slot_code);
            setText('d_photoshoot_slot',        data.photoshoot_slot);
            setText('d_extra_slot_code',        data.extra_slot_code);
            setText('d_extra_photoshoot_slot',  data.extra_photoshoot_slot);
            setText('d_extra_start_time',       data.extra_start_time);
            setText('d_extra_end_time',         data.extra_end_time);
            setText('d_extra_minutes',          data.extra_minutes);

            setText('d_tema_nama',       data.tema_nama);
            setText('d_tema_kode',       data.tema_kode);
            setText('d_tema2_nama',      data.tema2_nama);
            setText('d_tema2_kode',      data.tema2_kode);

            setText('d_style',           data.style);
            setText('d_wedding_date',    data.wedding_date);
            setText('d_nama_gabungan',   data.nama_gabungan);
            setText('d_email_gabungan',  data.email_gabungan);
            setText('d_phone_gabungan',  data.phone_gabungan);

            const notesEl = document.getElementById('d_notes');
            if (notesEl) {
                notesEl.textContent = data.notes || '-';
            }
        };

        const closeModal = () => {
            backdrop.classList.remove('is-open');
            backdrop.setAttribute('aria-hidden', 'true');
        };

        openBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const ds = btn.dataset;
                openModal({
                    id:                     ds.id,
                    kode:                   ds.kode,
                    status:                 ds.status,
                    tanggal:                ds.tanggal,
                    jam:                    ds.jam,
                    created_at:             ds.created_at,
                    nama_cpp:               ds.nama_cpp,
                    email_cpp:              ds.email_cpp,
                    phone_cpp:              ds.phone_cpp,
                    alamat_cpp:             ds.alamat_cpp,
                    nama_cpw:               ds.nama_cpw,
                    email_cpw:              ds.email_cpw,
                    phone_cpw:              ds.phone_cpw,
                    alamat_cpw:             ds.alamat_cpw,
                    ig_cpp:                 ds.ig_cpp,
                    ig_cpw:                 ds.ig_cpw,
                    tiktok_cpp:             ds.tiktok_cpp,
                    tiktok_cpw:             ds.tiktok_cpw,
                    sosmed_lain:            ds.sosmed_lain,
                    package:                ds.package,
                    package_price:          ds.package_price,
                    addons_total:           ds.addons_total,
                    grand_total:            ds.grand_total,
                    slot_code:              ds.slot_code,
                    photoshoot_slot:        ds.photoshoot_slot,
                    extra_slot_code:        ds.extra_slot_code,
                    extra_photoshoot_slot:  ds.extra_photoshoot_slot,
                    extra_start_time:       ds.extra_start_time,
                    extra_end_time:         ds.extra_end_time,
                    extra_minutes:          ds.extra_minutes,
                    tema_nama:              ds.tema_nama,
                    tema_kode:              ds.tema_kode,
                    tema2_nama:             ds.tema2_nama,
                    tema2_kode:             ds.tema2_kode,
                    style:                  ds.style,
                    wedding_date:           ds.wedding_date,
                    notes:                  ds.notes,
                    nama_gabungan:          ds.nama_gabungan,
                    email_gabungan:         ds.email_gabungan,
                    phone_gabungan:         ds.phone_gabungan
                });
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
});
