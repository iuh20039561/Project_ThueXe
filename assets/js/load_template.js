function loadHeader() {
    fetch('views/layouts/header.html')
        .then(r => r.text())
        .then(html => {
            document.body.insertAdjacentHTML('afterbegin', html);
            highlightActiveNav();
            injectBackBar();
        });
}

function loadFooter() {
    fetch('views/layouts/footer.html')
        .then(r => r.text())
        .then(html => {
            document.body.insertAdjacentHTML('beforeend', html);
            // Bootstrap JS phải inject dynamic để thực thi được
            if (!window.bootstrap) {
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
                document.body.appendChild(s);
            }
        });
}

function highlightActiveNav() {
    const page = new URLSearchParams(window.location.search).get('page') || 'home';
    document.getElementById(`nav-${page}`)?.classList.add('active');
}

function injectBackBar() {
    const page = new URLSearchParams(window.location.search).get('page') || 'home';
    if (page === 'home') return;

    const PAGE_LABELS = {
        search:          'Tìm xe',
        'car-detail':    'Chi tiết xe',
        about:           'Giới thiệu',
        services:        'Dịch vụ',
        guide:           'Hướng dẫn',
        contact:         'Liên hệ',
        booking_success: 'Đặt xe thành công',
        track_order:     'Theo dõi đơn',
    };

    // Trang cha (back về đâu khi không có history)
    const PARENT = {
        'car-detail':    'index.php?page=search',
        booking_success: 'index.php?page=home',
    };

    const label  = PAGE_LABELS[page] || page;
    const parent = PARENT[page] || 'index.php?page=home';

    const bar = document.createElement('div');
    bar.className = 'back-bar';
    bar.innerHTML = `
        <div class="container d-flex align-items-center gap-2 py-2">
            <button class="btn btn-back" onclick="goBack('${parent}')">
                <i class="fas fa-arrow-left me-1"></i>Trở lại
            </button>
            <span class="back-sep"><i class="fas fa-chevron-right"></i></span>
            <span class="back-current">${label}</span>
        </div>`;

    // Chèn ngay sau <header>
    const header = document.querySelector('header');
    if (header) header.insertAdjacentElement('afterend', bar);
    else document.body.insertAdjacentElement('afterbegin', bar);
}

function goBack(fallback) {
    if (document.referrer && document.referrer.includes(window.location.hostname)) {
        history.back();
    } else {
        window.location.href = fallback;
    }
}
