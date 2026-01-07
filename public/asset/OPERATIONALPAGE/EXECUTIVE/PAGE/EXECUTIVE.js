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
    const openBtn  = document.getElementById('btnOpenBooking');
    const close1   = document.getElementById('btnCloseBookingCreate');
    const close2   = document.getElementById('btnCloseBookingCreate2');

        if (!modal || !openBtn) return;
        if (modal.dataset.inited) return;
        modal.dataset.inited = '1';

        // ===== SLOT ELEMENT =====
        const selPackage = modal.querySelector('#package_id');
        const inputDate  = modal.querySelector('#photoshoot_date');
        const slotList   = modal.querySelector('#slotList');

        const slotCodeInp = modal.querySelector('[name="slot_code"]');
        const startInp    = modal.querySelector('[name="start_time"]');
        const endInp      = modal.querySelector('[name="end_time"]');

        const API_SLOTS = '/executive/api/slots';

        const splitTimeRange = (range) => {
            const [s, e] = String(range || '').split('-');
            return { start: s?.trim() || '', end: e?.trim() || '' };
        };

        const loadSlots = async () => {
        const pkg  = selPackage?.value;
        const date = inputDate?.value;

            if (!pkg || !date) {
                slotList.innerHTML =
                    '<p style="opacity:.7">Pilih paket & tanggal untuk melihat slot.</p>';
                return;
            }

            slotList.innerHTML = '<p style="opacity:.7">Memuat slot...</p>';

            try {
                const url = new URL(API_SLOTS, location.origin);
                url.searchParams.set('package_id', pkg);
                url.searchParams.set('date', date);

                const res = await fetch(url.toString(), {
                    headers: { Accept: 'application/json' },
                });
                if (!res.ok) throw new Error();

                const slots = await res.json();

                // ✅ DEBUG DI SINI
                console.log('slots:', slots);

                renderSlots(Array.isArray(slots) ? slots : []);
            } catch (e) {
                console.error('slot error:', e);
                slotList.innerHTML =
                    '<p style="color:#f56565">Gagal memuat slot.</p>';
            }
        };

        const renderSlots = (slots) => {
            if (!slots.length) {
                slotList.innerHTML =
                    '<p style="opacity:.7">Tidak ada slot tersedia.</p>';
                return;
            }

            slotList.innerHTML = slots.map(s => `
                <div class="slot-item ${s.available ? '' : 'unavail'}"
                    data-code="${s.code}"
                    data-time="${s.time}">
                    <span>${s.time}</span>
                    <small style="margin-left:auto;opacity:.7">${s.code}</small>
                </div>
            `).join('');
        };

        slotList.addEventListener('click', e => {
            const item = e.target.closest('.slot-item');
            if (!item || item.classList.contains('unavail')) return;

            const { start, end } = splitTimeRange(item.dataset.time);

            slotCodeInp.value = item.dataset.code;
            startInp.value    = start;
            endInp.value      = end;

            slotList.querySelectorAll('.slot-item')
                .forEach(el => el.classList.remove('active'));
            item.classList.add('active');
        });

        selPackage?.addEventListener('change', loadSlots);
        inputDate?.addEventListener('change', loadSlots);

        // ===== MODAL OPEN / CLOSE =====
        const show = () => {
            backdrop.classList.add('show');
            modal.classList.add('show');
        };

        const hide = () => {
            backdrop.classList.remove('show');
            modal.classList.remove('show');
        };

        openBtn.addEventListener('click', show);
        close1?.addEventListener('click', hide);
        close2?.addEventListener('click', hide);

        backdrop.addEventListener('click', e => {
            if (e.target === backdrop) hide();
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

});
