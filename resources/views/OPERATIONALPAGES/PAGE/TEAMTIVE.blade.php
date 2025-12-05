<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Studio Prewed - Executive')</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/OPERATIONALPAGE/EXECUTIVE/PAGE/EXECUTIVE.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    @php
        $page = $page ?? 'Dashboard';
    @endphp

    {{-- Sidebar & Topbar --}}
    @include('OPERATIONALPAGES.FITUR.MAPPING.sidebar')
    @include('OPERATIONALPAGES.FITUR.MAPPING.topbar')

    <div class="container">
        <div class="content" id="main-content">
            @if(View::exists("OPERATIONALPAGES.FITUR.MAINCONTENT.$page"))
                @include("OPERATIONALPAGES.FITUR.MAINCONTENT.$page")
            @else
                <div class="alert alert-warning" style="padding:20px; background:#2b2b2b; color:#f6ad55; border-radius:8px;">
                    <b>Halaman "{{ $page }}" belum dibuat.</b>
                </div>
            @endif
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
    const mainContent = document.getElementById('main-content');
    const menuLinks   = document.querySelectorAll('.sidebar .menu a[data-page]');
    const dropdowns   = document.querySelectorAll('.menu-item.dropdown > .dropdown-toggle');

    // ============ Inisialisasi MODAL untuk halaman yang sedang aktif ============
    const initModals = () => {
        const backdrop    = document.getElementById('modal-backdrop');
        const createModal = document.getElementById('modal-create');
        const editModal   = document.getElementById('modal-edit');
        const btnOpenCreate = document.getElementById('btn-open-create');

        // kalau bukan di halaman yang punya modal (misal Dashboard), skip aja
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

        // buka modal create
        if (btnOpenCreate) {
            btnOpenCreate.onclick = () => showModal(createModal);
        }

        // buka modal edit
        document.querySelectorAll('.btn-edit-user').forEach(btn => {
            btn.onclick = () => {
                const id    = btn.dataset.id;
                const name  = btn.dataset.name;
                const email = btn.dataset.email;
                const role  = btn.dataset.role;

                const form    = document.getElementById('editUserForm');
                const baseUrl = form.dataset.baseUrl; // dari attribute data-base-url di Blade

                form.action = `${baseUrl}/${id}`;

                document.getElementById('edit-name').value  = name;
                document.getElementById('edit-email').value = email;
                document.getElementById('edit-role').value  = role;

                showModal(editModal);
            };
        });

        // tombol close
        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.onclick = hideModals;
        });

        // klik backdrop untuk tutup modal
        backdrop.onclick = hideModals;
    };

    // ============ Fungsi load konten AJAX ============
    const loadPage = (link, pushHistory = true) => {
        const url  = link.getAttribute('href');
        const page = link.dataset.page;

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.text())
            .then(html => {
                mainContent.innerHTML = html;

                if (pushHistory) {
                    history.pushState({ page }, '', url);
                }

                // setelah HTML baru dimuat, inisialisasi modal jika ada
                initModals();
            })
            .catch(err => {
                console.error(err);
                mainContent.innerHTML = `
                    <div class="alert alert-danger">
                        Gagal memuat halaman ${page}
                    </div>`;
            });
    };

    // ============ Klik menu utama sidebar ============
    menuLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();

            // update active class
            menuLinks.forEach(l => l.parentElement.classList.remove('active'));
            link.parentElement.classList.add('active');

            // simpan nama page terakhir
            localStorage.setItem('activeMenu', link.dataset.page);

            loadPage(link);
        });
    });

    // ============ Dropdown toggle ============
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', e => {
            e.preventDefault();
            const parent = dropdown.parentElement;

            document.querySelectorAll('.menu-item.dropdown').forEach(item => {
                if (item !== parent) item.classList.remove('active');
            });

            parent.classList.toggle('active');
        });
    });

    // ============ Restore active menu saat reload ============
    const activePage = localStorage.getItem('activeMenu');
    if (activePage) {
        const activeLink = document.querySelector(`.sidebar .menu a[data-page="${activePage}"]`);
        if (activeLink) {
            activeLink.parentElement.classList.add('active');
            loadPage(activeLink, false);
        }
    } else {
        // kalau belum ada page tersimpan (misal pertama kali ke dashboard)
        initModals();
    }

    // ============ Handle tombol back/forward browser ============
    window.addEventListener('popstate', event => {
        const page = event.state?.page || localStorage.getItem('activeMenu') || 'Dashboard';
        const link = document.querySelector(`.sidebar .menu a[data-page="${page}"]`);

        if (link) {
            loadPage(link, false);

            // update active menu
            menuLinks.forEach(l => l.parentElement.classList.remove('active'));
            link.parentElement.classList.add('active');
        }
    });
    });
    </script>
</body>
</html>
