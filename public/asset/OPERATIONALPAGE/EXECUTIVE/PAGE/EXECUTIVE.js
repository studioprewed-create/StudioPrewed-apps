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
    const titleEl  = document.getElementById('bookingModalTitle');
    const bodyEl   = document.getElementById('bookingModalBody');

    if (!backdrop || !form) return;

    const wizard   = document.getElementById('bookingWizard');
    if (!wizard) return;

    // endpoints (sesuai wizard kamu)
    const API_SLOTS       = window.APP_ROUTES?.apiSlots      || null;
    const API_TEMA_BYNAME = window.APP_ROUTES?.apiTemaByName || null;

    // buttons open
    const openBtns  = document.querySelectorAll('.js-open-booking-modal');
    const closeBtns = backdrop.querySelectorAll('[data-close], .booking-modal-close');

    // wizard nav
    const btnPrev   = document.getElementById('btnPrev');
    const btnNext   = document.getElementById('btnNext');
    const btnSubmit = document.getElementById('btnSubmit');

    const stepsUI   = Array.from(wizard.querySelectorAll('.wiz-step'));
    const panels    = Array.from(wizard.querySelectorAll('.wiz-panel'));

    // method spoof bag + hidden bag
    const methodBag = document.getElementById('methodBag');
    const hiddenBag = document.getElementById('hiddenBag');

    // fields step 1
    const namaCpp   = document.getElementById('nama_cpp');
    const emailCpp  = document.getElementById('email_cpp');
    const phoneCpp  = document.getElementById('phone_cpp');
    const alamatCpp = document.getElementById('alamat_cpp');

    const namaCpw   = document.getElementById('nama_cpw');
    const emailCpw  = document.getElementById('email_cpw');
    const phoneCpw  = document.getElementById('phone_cpw');
    const alamatCpw = document.getElementById('alamat_cpw');

    // fields step 2
    const selPackage  = document.getElementById('package_id');
    const inputDate   = document.getElementById('photoshoot_date');
    const selStyle    = document.getElementById('style');
    const weddingDate = document.getElementById('wedding_date');
    const notesInput  = document.getElementById('notes');

    const slotList    = document.getElementById('slotList');

    // tema utama
    const selTemaNama = document.getElementById('tema_nama');
    const selTemaKode = document.getElementById('tema_kode');
    const selTemaId   = document.getElementById('tema_id');

    // addons
    const addonChecks = Array.from(wizard.querySelectorAll('.addon-check'));

    // extra slot (kategori 1)
    const extraSlotWrapper = document.getElementById('extraSlotWrapper');
    const extraSlotList    = document.getElementById('extraSlotList');

    // extra tema (kategori 2)
    const extraTemaWrapper = document.getElementById('extraTemaWrapper');
    const selTema2Nama     = document.getElementById('tema2_nama');
    const selTema2Kode     = document.getElementById('tema2_kode');
    const selTema2Id       = document.getElementById('tema2_id');

    // step 3
    const igCpp     = document.getElementById('ig_cpp');
    const igCpw     = document.getElementById('ig_cpw');
    const tiktokCpp = document.getElementById('tiktok_cpp');
    const tiktokCpw = document.getElementById('tiktok_cpw');

    // step 4
    const selStatus = document.getElementById('status');
    const summaryBox = document.getElementById('summaryBox');

    // state
    let currentStep = 1; // 1..4
    let editingId   = null;
    let mainSlots   = [];
    let extraSlots  = [];

    const $ = (s, r=document) => r.querySelector(s);

    const openBackdrop = () => {
        backdrop.classList.add('is-open');
        backdrop.setAttribute('aria-hidden', 'false');
    };

    const closeBackdrop = () => {
        backdrop.classList.remove('is-open');
        backdrop.setAttribute('aria-hidden', 'true');
    };

    const scrollTopModal = () => {
        if (bodyEl) bodyEl.scrollTop = 0;
    };

    const setStep = (step) => {
        currentStep = Math.max(1, Math.min(4, step));

        stepsUI.forEach(s => {
            const n = Number(s.dataset.step);
            s.classList.toggle('is-active', n === currentStep);
            s.classList.toggle('is-done', n < currentStep);
        });

        panels.forEach(p => {
            const n = Number(p.dataset.panel);
            p.classList.toggle('is-active', n === currentStep);
        });

        if (btnPrev)   btnPrev.style.display   = currentStep === 1 ? 'none' : 'inline-flex';
        if (btnNext)   btnNext.style.display   = currentStep === 4 ? 'none' : 'inline-flex';
        if (btnSubmit) btnSubmit.style.display = currentStep === 4 ? 'inline-flex' : 'none';

        scrollTopModal();

        if (currentStep === 4) renderSummary();
    };

    // helpers
    const getSelectedMainSlot = () => {
        const r = $('input[name="slot_main"]:checked', wizard);
        if (!r) return null;

        const slot = mainSlots.find(s => String(s.code) === String(r.value));
        if (!slot) {
            // fallback parse from data-time "HH:MM - HH:MM"
            const time = r.dataset.time || '';
            const [a,b] = time.split('-').map(x => (x||'').trim());
            return { code: r.value, time: time, start: a, end: b };
        }
        return slot;
    };

    const getSelectedExtraSlot = () => {
        const r = $('input[name="slot_extra"]:checked', wizard);
        if (!r) return null;

        const slot = extraSlots.find(s => String(s.code) === String(r.value));
        if (!slot) {
            const time = r.dataset.time || '';
            const [a,b] = time.split('-').map(x => (x||'').trim());
            return { code: r.value, time: time, start: a, end: b };
        }
        return slot;
    };

    const getAddonSlot = () => {
        // kategori 1 = slot
        const picked = addonChecks
            .filter(c => c.checked)
            .map(c => ({
                id: Number(c.dataset.id),
                kategori: Number(c.dataset.kategori),
                harga: Number(c.dataset.harga || 0),
                durasi: Number(c.dataset.durasi || 0),
            }))
            .find(a => a.kategori === 1);

        return picked || null;
    };

    const getAddonTema = () => {
        // kategori 2 = tema tambahan
        const picked = addonChecks
            .filter(c => c.checked)
            .map(c => ({
                id: Number(c.dataset.id),
                kategori: Number(c.dataset.kategori),
                harga: Number(c.dataset.harga || 0),
                durasi: Number(c.dataset.durasi || 0),
            }))
            .find(a => a.kategori === 2);

        return picked || null;
    };

    const getExtraMinutesFromAddons = () => {
        const a = getAddonSlot();
        return a ? Number(a.durasi || 0) : 0;
    };

    const normalizeAddonSlotSelection = (changedCheckbox) => {
        const kat = Number(changedCheckbox.dataset.kategori);
        if (kat !== 1) return;

        // hanya boleh 1 addon kategori 1 aktif
        if (!changedCheckbox.checked) return;

        addonChecks.forEach(c => {
            if (c === changedCheckbox) return;
            if (Number(c.dataset.kategori) === 1) c.checked = false;
        });
    };

    // Tema availability: backend mengembalikan array kode available=false untuk nama+date+start+end
    const applyTemaAvailabilityToIdSelect = (selectEl, unavailableSet, excludeKode) => {
        Array.from(selectEl.options).forEach((opt, idx) => {
            if (idx === 0 || !opt.value) return;
            const kode = String(opt.dataset.kode || '');
            if (!kode) return;

            if (excludeKode && kode === excludeKode) {
                opt.disabled = true;
                opt.classList.add('unavail');
                return;
            }

            if (unavailableSet.has(kode)) {
                opt.disabled = true;
                opt.classList.add('unavail');
            } else {
                opt.disabled = false;
                opt.classList.remove('unavail');
            }
        });
    };

    const refreshTemaKodeUtama = async () => {
        if (!API_TEMA_BYNAME) return;
        if (!selTemaNama || !selTemaKode) return;

        const nama = (selTemaNama.value || '').trim();
        const date = inputDate?.value;
        const main = getSelectedMainSlot();

        // enable/disable dasar
        if (!nama) {
            selTemaKode.value = '';
            selTemaKode.disabled = true;
            if (selTemaId) selTemaId.value = '';
            return;
        }

        selTemaKode.disabled = false;

        // availability hanya kalau date + main ada
        if (!date || !main) return;

        try {
            const url = new URL(API_TEMA_BYNAME, window.location.origin);
            url.searchParams.set('nama', nama);
            url.searchParams.set('date', date);
            url.searchParams.set('start', main.start);
            url.searchParams.set('end', main.end);

            const res = await fetch(url.toString(), { headers: { Accept: 'application/json' } });
            if (!res.ok) return;

            const data = await res.json();
            const unavailable = new Set(
                (Array.isArray(data) ? data : [])
                    .filter(t => t.available === false)
                    .map(t => String(t.kode))
            );

            Array.from(selTemaKode.options).forEach((opt, idx) => {
                if (idx === 0 || !opt.value) return;
                const kode = String(opt.value);

                if (unavailable.has(kode)) {
                    opt.disabled = true;
                    opt.classList.add('unavail');
                } else {
                    opt.disabled = false;
                    opt.classList.remove('unavail');
                }
            });

            // jika ada select by ID (hidden id), ikut disable berdasarkan data-kode
            if (selTemaId) applyTemaAvailabilityToIdSelect(selTemaId, unavailable);
        } catch (e) {}
    };

    const refreshTemaKodeTambahan = async () => {
        if (!API_TEMA_BYNAME) return;
        if (!selTema2Nama || !selTema2Kode) return;

        const nama2 = (selTema2Nama.value || '').trim();
        const date  = inputDate?.value;
        const main  = getSelectedMainSlot();
        const excludeKode = selTemaKode?.value || null; // jangan sama dengan tema utama

        if (!nama2) {
            selTema2Kode.value = '';
            selTema2Kode.disabled = true;
            if (selTema2Id) selTema2Id.value = '';
            return;
        }

        selTema2Kode.disabled = false;

        if (!date || !main) return;

        try {
            const url = new URL(API_TEMA_BYNAME, window.location.origin);
            url.searchParams.set('nama', nama2);
            url.searchParams.set('date', date);
            url.searchParams.set('start', main.start);
            url.searchParams.set('end', main.end);
            if (excludeKode) url.searchParams.set('exclude_kode', excludeKode);

            const res = await fetch(url.toString(), { headers: { Accept: 'application/json' } });
            if (!res.ok) return;

            const data = await res.json();
            const unavailable = new Set(
                (Array.isArray(data) ? data : [])
                    .filter(t => t.available === false)
                    .map(t => String(t.kode))
            );

            Array.from(selTema2Kode.options).forEach((opt, idx) => {
                if (idx === 0 || !opt.value) return;

                const kode = String(opt.value);

                // jangan sama dengan tema utama
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

            if (selTema2Id) applyTemaAvailabilityToIdSelect(selTema2Id, unavailable, excludeKode);
        } catch (e) {}
    };

    // render slots
    const renderMainSlots = () => {
        if (!slotList) return;

        if (!mainSlots.length) {
            slotList.innerHTML = '<p style="opacity:.7">Tidak ada slot tersedia.</p>';
            return;
        }

        slotList.innerHTML = mainSlots.map(s => {
            const id = `slot_main_${s.code}`;
            return `
                <label class="slot-item ${s.available ? '' : 'unavail'}" for="${id}">
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
        }).join('');
    };

    const renderExtraSlots = () => {
        if (!extraSlotList) return;

        const main = getSelectedMainSlot();

        if (!extraSlots.length) {
            extraSlotList.innerHTML = '<p style="opacity:.7">Tidak ada slot tambahan tersedia.</p>';
            return;
        }

        extraSlotList.innerHTML = extraSlots.map(s => {
            const id = `slot_extra_${s.code}`;
            const disabled = !s.available || (main && String(s.code) === String(main.code));
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
        }).join('');
    };

    // load main slots
    const loadMainSlots = async (preselectCode = null) => {
        if (!API_SLOTS || !selPackage || !inputDate || !slotList) return;

        const pkg  = selPackage.value;
        const date = inputDate.value;

        if (!pkg || !date) {
            mainSlots = [];
            slotList.innerHTML = '<p style="opacity:.7">Pilih paket & tanggal terlebih dahulu.</p>';
            return;
        }

        slotList.innerHTML = '<p style="opacity:.7">Memuat slot...</p>';

        try {
            const url = new URL(API_SLOTS, window.location.origin);
            url.searchParams.set('package_id', pkg);
            url.searchParams.set('date', date);

            // untuk mode edit, supaya backend bisa exclude id sendiri (kalau endpoint kamu support)
            if (editingId) url.searchParams.set('exclude_id', String(editingId));

            const res = await fetch(url.toString(), { headers: { Accept: 'application/json' } });
            if (!res.ok) throw new Error('Gagal memuat slot');

            const data = await res.json();
            mainSlots = Array.isArray(data) ? data : [];
            renderMainSlots();

            if (preselectCode) {
                const radio = wizard.querySelector(`input[name="slot_main"][value="${preselectCode}"]`);
                if (radio && !radio.disabled) radio.checked = true;
            }

            refreshTemaKodeUtama();
            refreshTemaKodeTambahan();
        } catch (e) {
            mainSlots = [];
            slotList.innerHTML = '<p style="color:#b91c1c">Gagal memuat slot. Coba lagi.</p>';
        }
    };

    // load extra slots (addon kategori 1)
    const loadExtraSlots = async (preselectCode = null) => {
        if (!API_SLOTS || !extraSlotWrapper || !extraSlotList) return;

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
            extraSlotList.innerHTML = '<p style="opacity:.7">Pilih tanggal dan slot utama terlebih dahulu.</p>';
            return;
        }

        extraSlotWrapper.style.display = 'block';
        extraSlotList.innerHTML = '<p style="opacity:.7">Memuat slot tambahan.</p>';

        try {
            const url = new URL(API_SLOTS, window.location.origin);
            url.searchParams.set('date', date);
            url.searchParams.set('durasi', String(extraMin));    // durasi addon
            url.searchParams.set('exclude', String(main.code));  // jangan sama dengan slot utama
            url.searchParams.set('main_start', String(main.start));
            url.searchParams.set('main_end', String(main.end));
            if (editingId) url.searchParams.set('exclude_id', String(editingId));

            const res = await fetch(url.toString(), { headers: { Accept: 'application/json' } });
            if (!res.ok) throw new Error('Gagal memuat slot tambahan');

            const data = await res.json();
            extraSlots = Array.isArray(data) ? data : [];
            renderExtraSlots();

            if (preselectCode) {
                const radio = wizard.querySelector(`input[name="slot_extra"][value="${preselectCode}"]`);
                if (radio && !radio.disabled) radio.checked = true;
            }
        } catch (e) {
            extraSlots = [];
            extraSlotList.innerHTML = '<p style="color:#b91c1c">Gagal memuat slot tambahan. Coba lagi.</p>';
        }
    };

    // ====== summary + inject ======
    const getSelectedAddons = () => {
        return addonChecks
            .filter(c => c.checked)
            .map(c => ({
                id: Number(c.dataset.id),
                kategori: Number(c.dataset.kategori),
                harga: Number(c.dataset.harga || 0),
                durasi: Number(c.dataset.durasi || 0),
                name: (c.closest('.addon-item')?.querySelector('.addon-name')?.textContent || '').trim()
            }));
    };

    const parseSlotTime = (t) => {
        const time = (t || '').replace('–','-');
        const parts = time.split('-').map(x => (x||'').trim());
        return { start: parts[0] || '', end: parts[1] || '' };
    };

    const renderSummary = () => {
        if (!summaryBox) return;

        const pkgText = selPackage?.selectedOptions?.[0]?.textContent?.trim() || '-';

        const main = getSelectedMainSlot();
        const extra = getSelectedExtraSlot();

        const temaText  = (selTemaNama?.value && selTemaKode?.value) ? `${selTemaNama.value} (${selTemaKode.value})` : '-';
        const tema2Text = (selTema2Nama?.value && selTema2Kode?.value) ? `${selTema2Nama.value} (${selTema2Kode.value})` : '-';

        const selectedAddons = getSelectedAddons();
        const addonTotal = selectedAddons.reduce((a,b)=>a+(b.harga||0),0);

        const extraSlotInfo = extra ? `${extra.time} (${extra.code})` : '-';

        const addonListHtml = selectedAddons.length
            ? `<ul style="margin:6px 0 0 18px;padding:0">
                ${selectedAddons.map(a => `<li>${a.name} <span style="opacity:.7">(Kategori ${a.kategori})</span></li>`).join('')}
               </ul>`
            : '<p style="margin:6px 0 0;opacity:.7">Tidak ada addon dipilih.</p>';

        summaryBox.innerHTML = `
            <div class="grid-2">
                <div class="summary-card">
                    <h4>Identitas</h4>
                    <p><strong>CPP:</strong> ${(namaCpp.value||'-')} | ${(phoneCpp.value||'-')} | ${(emailCpp.value||'-')}</p>
                    <p><strong>CPW:</strong> ${(namaCpw.value||'-')} | ${(phoneCpw.value||'-')} | ${(emailCpw.value||'-')}</p>
                </div>
                <div class="summary-card">
                    <h4>Detail Booking</h4>
                    <p><strong>Paket:</strong> ${pkgText}</p>
                    <p><strong>Tanggal:</strong> ${inputDate?.value || '-'}</p>
                    <p><strong>Slot Utama:</strong> ${main ? `${main.time} (${main.code})` : '-'}</p>
                    <p><strong>Slot Tambahan:</strong> ${extraSlotInfo}</p>
                    <p><strong>Style:</strong> ${selStyle?.value || '-'}</p>
                    <p><strong>Tema Utama:</strong> ${temaText}</p>
                    <p><strong>Tema Tambahan:</strong> ${tema2Text}</p>
                    <p><strong>Wedding Date:</strong> ${weddingDate?.value || '-'}</p>
                    <p><strong>Notes:</strong> ${(notesInput?.value || '-')}</p>
                    <p><strong>Extra Minutes:</strong> ${getExtraMinutesFromAddons()} menit</p>
                </div>
            </div>
            <div class="summary-card" style="margin-top:12px">
                <h4>Sosial Media</h4>
                <p><strong>CPP:</strong> IG ${(igCpp?.value||'-')} | TikTok ${(tiktokCpp?.value||'-')}</p>
                <p><strong>CPW:</strong> IG ${(igCpw?.value||'-')} | TikTok ${(tiktokCpw?.value||'-')}</p>
            </div>
            <div class="summary-card" style="margin-top:12px">
                <h4>Addon</h4>
                ${addonListHtml}
                <p style="margin-top:8px"><strong>Total Addon (estimasi):</strong> Rp ${addonTotal.toLocaleString('id-ID')}</p>
            </div>
        `;
    };

    const injectHiddenInputs = () => {
        if (!hiddenBag) return;
        hiddenBag.innerHTML = '';

        const addHidden = (name, value) => {
            const i = document.createElement('input');
            i.type = 'hidden';
            i.name = name;
            i.value = value == null ? '' : String(value);
            hiddenBag.appendChild(i);
        };

        // step 1
        addHidden('nama_cpp', namaCpp.value.trim());
        addHidden('email_cpp', (emailCpp.value||'').trim());
        addHidden('phone_cpp', phoneCpp.value.trim());
        addHidden('alamat_cpp', (alamatCpp.value||'').trim());

        addHidden('nama_cpw', namaCpw.value.trim());
        addHidden('email_cpw', (emailCpw.value||'').trim());
        addHidden('phone_cpw', phoneCpw.value.trim());
        addHidden('alamat_cpw', (alamatCpw.value||'').trim());

        // step 2
        addHidden('package_id', selPackage.value);
        addHidden('photoshoot_date', inputDate.value);
        addHidden('style', selStyle.value);

        // slot utama
        const main = getSelectedMainSlot();
        addHidden('slot_code', main ? main.code : '');
        addHidden('photoshoot_slot', main ? main.time : '');

        if (main) {
            // pastikan start/end terset (fallback parse)
            const start = main.start || parseSlotTime(main.time).start;
            const end   = main.end   || parseSlotTime(main.time).end;
            addHidden('start_time', start);
            addHidden('end_time', end);
        } else {
            addHidden('start_time', '');
            addHidden('end_time', '');
        }

        // tema utama
        addHidden('tema_nama', (selTemaNama?.value || '').trim());
        addHidden('tema_kode', (selTemaKode?.value || '').trim());
        addHidden('tema_id', (selTemaId?.value || ''));

        // wedding + notes
        addHidden('wedding_date', (weddingDate?.value || ''));
        addHidden('notes', (notesInput?.value || '').trim());

        // extra slot
        const extra = getSelectedExtraSlot();
        addHidden('extra_slot_code', extra ? extra.code : '');
        addHidden('extra_photoshoot_slot', extra ? extra.time : '');

        if (extra) {
            const start = extra.start || parseSlotTime(extra.time).start;
            const end   = extra.end   || parseSlotTime(extra.time).end;
            addHidden('extra_start_time', start);
            addHidden('extra_end_time', end);
        } else {
            addHidden('extra_start_time', '');
            addHidden('extra_end_time', '');
        }
        addHidden('extra_minutes', String(getExtraMinutesFromAddons() || 0));

        // tema tambahan
        addHidden('tema2_nama', (selTema2Nama?.value || '').trim());
        addHidden('tema2_kode', (selTema2Kode?.value || '').trim());
        addHidden('tema2_id', (selTema2Id?.value || ''));

        // step 3
        addHidden('ig_cpp', (igCpp?.value || '').trim());
        addHidden('ig_cpw', (igCpw?.value || '').trim());
        addHidden('tiktok_cpp', (tiktokCpp?.value || '').trim());
        addHidden('tiktok_cpw', (tiktokCpw?.value || '').trim());

        // status (admin)
        addHidden('status', selStatus?.value || 'submitted');

        // addons[]
        getSelectedAddons().forEach(a => addHidden('addons[]', a.id));
    };

    // ====== validation ======
    const validateStep1 = () => {
        if (!namaCpp.value.trim()) return alert('Nama CPP wajib diisi'), false;
        if (!phoneCpp.value.trim()) return alert('No HP CPP wajib diisi'), false;
        if (!namaCpw.value.trim()) return alert('Nama CPW wajib diisi'), false;
        if (!phoneCpw.value.trim()) return alert('No HP CPW wajib diisi'), false;
        return true;
    };

    const validateStep2 = () => {
        if (!selPackage.value) return alert('Paket wajib dipilih'), false;
        if (!inputDate.value) return alert('Tanggal wajib dipilih'), false;
        if (!selStyle.value) return alert('Style wajib dipilih'), false;

        const main = getSelectedMainSlot();
        if (!main) return alert('Slot utama wajib dipilih'), false;

        // kalau addon slot dipilih, pastikan slot extra dipilih
        const slotAddon = getAddonSlot();
        if (slotAddon) {
            const extra = getSelectedExtraSlot();
            if (!extra) return alert('Addon slot dipilih, slot tambahan wajib dipilih'), false;
        }

        // kalau addon tema dipilih, pastikan tema2 valid
        const temaAddon = getAddonTema();
        if (temaAddon) {
            if (!selTema2Nama.value || !selTema2Kode.value) {
                return alert('Addon tema dipilih, tema tambahan (nama+kodenya) wajib dipilih'), false;
            }
        }

        // tema utama: kalau nama dipilih, kodenya wajib
        if (selTemaNama.value && !selTemaKode.value) {
            return alert('Jika memilih nama tema utama, kode tema utama juga harus dipilih'), false;
        }

        // tema2 tidak boleh sama dengan tema utama
        if (selTemaKode.value && selTema2Kode.value && selTemaKode.value === selTema2Kode.value) {
            return alert('Kode tema tambahan tidak boleh sama dengan tema utama'), false;
        }

        return true;
    };

    // ====== open modal ======
    const resetAll = () => {
        editingId = null;
        if (methodBag) methodBag.innerHTML = '';
        if (hiddenBag) hiddenBag.innerHTML = '';

        // clear inputs
        [
            namaCpp, emailCpp, phoneCpp, alamatCpp,
            namaCpw, emailCpw, phoneCpw, alamatCpw,
            igCpp, igCpw, tiktokCpp, tiktokCpw
        ].forEach(el => { if (el) el.value = ''; });

        if (selPackage) selPackage.value = '';
        if (selStyle) selStyle.value = '';
        if (weddingDate) weddingDate.value = '';
        if (notesInput) notesInput.value = '';

        if (selTemaNama) selTemaNama.value = '';
        if (selTemaKode) { selTemaKode.value = ''; selTemaKode.disabled = true; }
        if (selTemaId) selTemaId.value = '';

        if (selTema2Nama) selTema2Nama.value = '';
        if (selTema2Kode) { selTema2Kode.value = ''; selTema2Kode.disabled = true; }
        if (selTema2Id) selTema2Id.value = '';

        addonChecks.forEach(c => c.checked = false);

        if (extraSlotWrapper) extraSlotWrapper.style.display = 'none';
        if (extraSlotList) extraSlotList.innerHTML = '<p style="opacity:.7">Pilih addon slot & slot utama terlebih dahulu.</p>';
        if (extraTemaWrapper) extraTemaWrapper.style.display = 'none';

        mainSlots = [];
        extraSlots = [];
        if (slotList) slotList.innerHTML = '<p style="opacity:.7">Pilih paket & tanggal terlebih dahulu.</p>';

        if (selStatus) selStatus.value = 'submitted';
        setStep(1);
    };

    const openCreate = () => {
        resetAll();

        // default date
        const defDate = form.dataset.defaultDate;
        if (inputDate && defDate) inputDate.value = defDate;

        const storeUrl = form.dataset.storeUrl;
        if (storeUrl) form.action = storeUrl;

        if (titleEl) titleEl.textContent = 'Booking Baru';
        openBackdrop();

        // tidak auto-load slot sebelum paket dipilih
    };

    const openEdit = async (ds) => {
        resetAll();

        editingId = ds.id ? String(ds.id) : null;

        // set action + method PUT
        const tpl = form.dataset.updateTemplate || '';
        if (tpl && editingId) form.action = tpl.replace('__ID__', editingId);

        if (methodBag) {
            methodBag.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
        }

        if (titleEl) titleEl.textContent = `Edit Booking (${ds.kode_pesanan || ds.kode || ds.id || ''})`;

        // fill fields
        if (namaCpp) namaCpp.value = ds.nama_cpp || '';
        if (emailCpp) emailCpp.value = ds.email_cpp || '';
        if (phoneCpp) phoneCpp.value = ds.phone_cpp || '';
        if (alamatCpp) alamatCpp.value = ds.alamat_cpp || '';

        if (namaCpw) namaCpw.value = ds.nama_cpw || '';
        if (emailCpw) emailCpw.value = ds.email_cpw || '';
        if (phoneCpw) phoneCpw.value = ds.phone_cpw || '';
        if (alamatCpw) alamatCpw.value = ds.alamat_cpw || '';

        if (igCpp) igCpp.value = ds.ig_cpp || '';
        if (igCpw) igCpw.value = ds.ig_cpw || '';
        if (tiktokCpp) tiktokCpp.value = ds.tiktok_cpp || '';
        if (tiktokCpw) tiktokCpw.value = ds.tiktok_cpw || '';

        if (selPackage) selPackage.value = ds.package_id || '';
        if (inputDate) inputDate.value = ds.date || ds.photoshoot_date || '';
        if (selStyle) selStyle.value = ds.style || '';
        if (weddingDate) weddingDate.value = ds.wedding_date || '';
        if (notesInput) notesInput.value = ds.notes || '';

        if (selStatus) selStatus.value = ds.status || 'submitted';

        // tema utama
        if (selTemaNama) selTemaNama.value = ds.tema_nama || '';
        if (selTemaKode) {
            selTemaKode.disabled = !selTemaNama.value;
            selTemaKode.value = ds.tema_kode || '';
        }
        if (selTemaId) selTemaId.value = ds.tema_id || '';

        // addons precheck
        let addonArr = [];
        try { addonArr = JSON.parse(ds.addons || '[]'); } catch (e) { addonArr = Array.isArray(ds.addons) ? ds.addons : []; }

        if (Array.isArray(addonArr)) {
            addonChecks.forEach(c => {
                const id = Number(c.dataset.id);
                c.checked = addonArr.map(Number).includes(id);
            });
        }

        // tampilkan extra tema bila addon kategori 2 terpilih
        if (getAddonTema() && extraTemaWrapper) extraTemaWrapper.style.display = 'block';

        // load slots + preselect main slot code
        openBackdrop();
        await loadMainSlots(ds.slot_code || null);

        // kalau addon slot, load extra slots + preselect
        if (getAddonSlot()) {
            if (extraSlotWrapper) extraSlotWrapper.style.display = 'block';
            await loadExtraSlots(ds.extra_slot_code || null);
        }

        // tema tambahan
        if (selTema2Nama) selTema2Nama.value = ds.tema2_nama || '';
        if (selTema2Kode) {
            selTema2Kode.disabled = !selTema2Nama.value;
            selTema2Kode.value = ds.tema2_kode || '';
        }
        if (selTema2Id) selTema2Id.value = ds.tema2_id || '';

        setStep(1);
    };

    // bind open buttons
    openBtns.forEach(btn => {
        btn.addEventListener('click', async () => {
            const ds = btn.dataset || {};
            const mode = ds.mode || 'create';
            if (mode === 'edit') {
                await openEdit(ds);
            } else {
                openCreate();
            }
        });
    });

    // close
    closeBtns.forEach(btn => btn.addEventListener('click', closeBackdrop));
    backdrop.addEventListener('click', (e) => { if (e.target === backdrop) closeBackdrop(); });

    // wizard nav clicks
    if (btnPrev) btnPrev.addEventListener('click', () => setStep(currentStep - 1));
    if (btnNext) btnNext.addEventListener('click', () => {
        if (currentStep === 1 && !validateStep1()) return;
        if (currentStep === 2 && !validateStep2()) return;

        setStep(currentStep + 1);
    });

    // change listeners
    const debounce = (fn, ms=250) => {
        let t;
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn(...args), ms);
        };
    };

    const debouncedLoadMain  = debounce(() => loadMainSlots(), 250);
    const debouncedLoadExtra = debounce(() => loadExtraSlots(), 250);

    if (selPackage) selPackage.addEventListener('change', () => {
        debouncedLoadMain();
        debouncedLoadExtra();
    });

    if (inputDate) inputDate.addEventListener('change', () => {
        debouncedLoadMain();
        debouncedLoadExtra();
        refreshTemaKodeUtama();
        refreshTemaKodeTambahan();
    });

    if (selTemaNama) selTemaNama.addEventListener('change', () => {
        selTemaKode.disabled = !selTemaNama.value;
        if (!selTemaNama.value) {
            selTemaKode.value = '';
            if (selTemaId) selTemaId.value = '';
        }
        refreshTemaKodeUtama();
    });

    if (selTema2Nama) selTema2Nama.addEventListener('change', () => {
        selTema2Kode.disabled = !selTema2Nama.value;
        if (!selTema2Nama.value) {
            selTema2Kode.value = '';
            if (selTema2Id) selTema2Id.value = '';
        }
        refreshTemaKodeTambahan();
    });

    wizard.addEventListener('change', (e) => {
        const t = e.target;

        if (t.name === 'slot_main') {
            debouncedLoadExtra();
            refreshTemaKodeUtama();
            refreshTemaKodeTambahan();
        }

        if (t.classList && t.classList.contains('addon-check')) {
            normalizeAddonSlotSelection(t);

            const slotAddon = getAddonSlot();
            const temaAddon = getAddonTema();

            if (slotAddon) {
                if (extraSlotWrapper) extraSlotWrapper.style.display = 'block';
                debouncedLoadExtra();
            } else if (extraSlotWrapper) {
                extraSlotWrapper.style.display = 'none';
                if (extraSlotList) extraSlotList.innerHTML = '<p style="opacity:.7">Pilih addon slot & slot utama terlebih dahulu.</p>';
                const pickedExtra = $('input[name="slot_extra"]:checked', wizard);
                if (pickedExtra) pickedExtra.checked = false;
            }

            if (temaAddon && extraTemaWrapper) {
                extraTemaWrapper.style.display = 'block';
            } else if (extraTemaWrapper) {
                extraTemaWrapper.style.display = 'none';
                if (selTema2Nama) selTema2Nama.value = '';
                if (selTema2Kode) { selTema2Kode.value=''; selTema2Kode.disabled=true; }
                if (selTema2Id) selTema2Id.value = '';
            }

            if (currentStep === 4) renderSummary();
        }
    });

    // submit -> inject hidden inputs dulu
    form.addEventListener('submit', (e) => {
        // validasi final minimal (biar aman)
        if (!validateStep1() || !validateStep2()) {
            e.preventDefault();
            setStep(!validateStep1() ? 1 : 2);
            return;
        }

        injectHiddenInputs();
        // allow submit
    });

    // init step ui
    setStep(1);
};

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
