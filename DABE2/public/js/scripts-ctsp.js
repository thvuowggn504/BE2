document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.card');
    let autoRotateInterval, restartTimeout, isHovering = false;

    const startAutoRotate = () => {
        autoRotateInterval = setInterval(() => {
            if (!isHovering) document.querySelector('.card[data-position="right"]')?.click();
        }, 5000);
    };

    const stopAutoRotate = () => {
        clearInterval(autoRotateInterval);
        clearTimeout(restartTimeout);
    };

    const restartAutoRotate = () => {
        stopAutoRotate();
        restartTimeout = setTimeout(() => {
            isHovering = false;
            startAutoRotate();
        }, 5000);
    };

    startAutoRotate();

    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            isHovering = true;
            stopAutoRotate();
        });

        card.addEventListener('mouseleave', restartAutoRotate);

        card.addEventListener('click', function () {
            if (this.getAttribute('data-position') !== 'center') {
                const leftCard = document.querySelector('.card[data-position="left"]');
                const centerCard = document.querySelector('.card[data-position="center"]');
                const rightCard = document.querySelector('.card[data-position="right"]');

                if (this.getAttribute('data-position') === 'left') {
                    this.className = 'card center';
                    this.setAttribute('data-position', 'center');
                    centerCard.className = 'card right';
                    centerCard.setAttribute('data-position', 'right');
                    rightCard.className = 'card left';
                    rightCard.setAttribute('data-position', 'left');
                } else {
                    this.className = 'card center';
                    this.setAttribute('data-position', 'center');
                    centerCard.className = 'card left';
                    centerCard.setAttribute('data-position', 'left');
                    leftCard.className = 'card right';
                    leftCard.setAttribute('data-position', 'right');
                }
            }
        });
    });

    document.addEventListener('mousemove', e => {
        const centerCard = document.querySelector('.card.center');
        if (!centerCard) return;

        const rect = centerCard.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        centerCard.style.transform = (x >= 0 && x <= rect.width && y >= 0 && y <= rect.height)
            ? `translateZ(10px) rotateX(${(rect.height / 2 - y) / 20}deg) rotateY(${(x - rect.width / 2) / 20}deg)`
            : '';
    });

    document.addEventListener('mouseleave', () => {
        const centerCard = document.querySelector('.card.center');
        if (centerCard) centerCard.style.transform = '';
    });

    let activeMenu = null;
    document.querySelectorAll("nav a").forEach(item => {
        const normalizedCategoryName = item.textContent.trim().toLowerCase();
        if (categoriesFromDB[normalizedCategoryName]) {
            const dropdownMenu = document.createElement("div");
            dropdownMenu.classList.add("dropdown-menu");

            categoriesFromDB[normalizedCategoryName].forEach(product => {
                const productLink = document.createElement("a");
                productLink.href = "#";
                productLink.textContent = product;
                dropdownMenu.appendChild(productLink);
            });

            document.body.appendChild(dropdownMenu);

            item.addEventListener("mouseenter", () => {
                if (activeMenu) activeMenu.classList.remove("active");
                dropdownMenu.classList.add("active");
                activeMenu = dropdownMenu;
            });

            dropdownMenu.addEventListener("mouseleave", () => {
                dropdownMenu.classList.remove("active");
                activeMenu = null;
            });
        }
    });

    document.addEventListener("click", e => {
        if (activeMenu && !e.target.closest(".dropdown-menu") && !e.target.closest("nav a")) {
            activeMenu.classList.remove("active");
            activeMenu = null;
        }
    });

    const searchInput = document.getElementById("search-input");
    const dropdownSearch = document.getElementById("dropdown-search");

    searchInput.addEventListener("focus", () => dropdownSearch.classList.add("active"));
    document.addEventListener("click", e => {
        if (!e.target.closest(".search-container")) dropdownSearch.classList.remove("active");
    });
    searchInput.addEventListener("input", () => {
        searchInput.setAttribute("placeholder", searchInput.value.trim() === "" ? "Tìm kiếm sản phẩm" : "");
    });

    const loginBtn = document.getElementById("login-btn");
    const userInfo = document.getElementById("user-info");
    const usernameSpan = document.getElementById("username");
    const userDropdown = document.querySelector(".user-dropdown");
    const logoutBtn = document.getElementById("logout-btn");

    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    if (currentUser) {
        loginBtn.style.display = "none";
        userInfo.classList.remove("hidden");
        usernameSpan.textContent = currentUser.name;
    }

    userInfo.addEventListener("mouseenter", () => userDropdown.style.display = "block");
    userInfo.addEventListener("mouseleave", () => userDropdown.style.display = "none");
    logoutBtn.addEventListener("click", () => {
        localStorage.removeItem("currentUser");
        alert("Bạn đã đăng xuất!");
        window.location.reload();
    });
});

document.querySelectorAll(".dropdown-menu a").forEach(item => {
    item.addEventListener("click", function (event) {
        event.preventDefault();
        const productName = this.textContent.trim();
        window.location.href = `ctsp.php?product=${encodeURIComponent(productName)}`;
    });
});

document.addEventListener('DOMContentLoaded', () => {
    // --- Banner Carousel Logic ---
    const bannerSlides = document.querySelectorAll('.banner-slide');
    const prevBtn = document.querySelector('.banner-nav.prev');
    const nextBtn = document.querySelector('.banner-nav.next');
    let currentBannerIndex = 0;
    let bannerInterval;

    // Hàm hiển thị banner
    const showBanner = (index) => {
        bannerSlides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
        document.querySelector('.banner-slides').style.transform = `translateX(-${index * 33.33}%)`;
    };

    // Chuyển banner tiếp theo
    const nextBanner = () => {
        currentBannerIndex = (currentBannerIndex + 1) % bannerSlides.length;
        showBanner(currentBannerIndex);
    };

    // Chuyển banner trước đó
    const prevBanner = () => {
        currentBannerIndex = (currentBannerIndex - 1 + bannerSlides.length) % bannerSlides.length;
        showBanner(currentBannerIndex);
    };

    // Tự động chuyển banner mỗi 5 giây
    const startBannerAutoRotate = () => {
        bannerInterval = setInterval(nextBanner, 5000);
    };

    // Dừng tự động chuyển khi hover
    const stopBannerAutoRotate = () => {
        clearInterval(bannerInterval);
    };

    // Khởi động tự động chuyển banner
    startBannerAutoRotate();

    // Sự kiện cho nút điều hướng
    nextBtn.addEventListener('click', () => {
        stopBannerAutoRotate();
        nextBanner();
        startBannerAutoRotate();
    });

    prevBtn.addEventListener('click', () => {
        stopBannerAutoRotate();
        prevBanner();
        startBannerAutoRotate();
    });

    // Dừng tự động chuyển khi hover vào banner
    document.querySelector('.banner-carousel').addEventListener('mouseenter', stopBannerAutoRotate);
    document.querySelector('.banner-carousel').addEventListener('mouseleave', startBannerAutoRotate);

    // --- Logic hiện có cho card stack (giữ nguyên) ---
    const cards = document.querySelectorAll('.card');
    let autoRotateInterval, restartTimeout, isHovering = false;

    const startAutoRotate = () => {
        autoRotateInterval = setInterval(() => {
            if (!isHovering) document.querySelector('.card[data-position="right"]')?.click();
        }, 5000);
    };

    const stopAutoRotate = () => {
        clearInterval(autoRotateInterval);
        clearTimeout(restartTimeout);
    };

    const restartAutoRotate = () => {
        stopAutoRotate();
        restartTimeout = setTimeout(() => {
            isHovering = false;
            startAutoRotate();
        }, 5000);
    };

    startAutoRotate();

    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            isHovering = true;
            stopAutoRotate();
        });

        card.addEventListener('mouseleave', restartAutoRotate);

        card.addEventListener('click', function () {
            if (this.getAttribute('data-position') !== 'center') {
                const leftCard = document.querySelector('.card[data-position="left"]');
                const centerCard = document.querySelector('.card[data-position="center"]');
                const rightCard = document.querySelector('.card[data-position="right"]');

                if (this.getAttribute('data-position') === 'left') {
                    this.className = 'card center';
                    this.setAttribute('data-position', 'center');
                    centerCard.className = 'card right';
                    centerCard.setAttribute('data-position', 'right');
                    rightCard.className = 'card left';
                    rightCard.setAttribute('data-position', 'left');
                } else {
                    this.className = 'card center';
                    this.setAttribute('data-position', 'center');
                    centerCard.className = 'card left';
                    centerCard.setAttribute('data-position', 'left');
                    leftCard.className = 'card right';
                    leftCard.setAttribute('data-position', 'right');
                }
            }
        });
    });

    document.addEventListener('mousemove', e => {
        const centerCard = document.querySelector('.card.center');
        if (!centerCard) return;

        const rect = centerCard.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        centerCard.style.transform = (x >= 0 && x <= rect.width && y >= 0 && y <= rect.height)
            ? `translateZ(10px) rotateX(${(rect.height / 2 - y) / 20}deg) rotateY(${(x - rect.width / 2) / 20}deg)`
            : '';
    });

    document.addEventListener('mouseleave', () => {
        const centerCard = document.querySelector('.card.center');
        if (centerCard) centerCard.style.transform = '';
    });

    let activeMenu = null;
    document.querySelectorAll("nav a").forEach(item => {
        const normalizedCategoryName = item.textContent.trim().toLowerCase();
        if (categoriesFromDB[normalizedCategoryName]) {
            const dropdownMenu = document.createElement("div");
            dropdownMenu.classList.add("dropdown-menu");

            categoriesFromDB[normalizedCategoryName].forEach(product => {
                const productLink = document.createElement("a");
                productLink.href = "#";
                productLink.textContent = product;
                dropdownMenu.appendChild(productLink);
            });

            document.body.appendChild(dropdownMenu);

            item.addEventListener("mouseenter", () => {
                if (activeMenu) activeMenu.classList.remove("active");
                dropdownMenu.classList.add("active");
                activeMenu = dropdownMenu;
            });

            dropdownMenu.addEventListener("mouseleave", () => {
                dropdownMenu.classList.remove("active");
                activeMenu = null;
            });
        }
    });

    document.addEventListener("click", e => {
        if (activeMenu && !e.target.closest(".dropdown-menu") && !e.target.closest("nav a")) {
            activeMenu.classList.remove("active");
            activeMenu = null;
        }
    });

    const searchInput = document.getElementById("search-input");
    const dropdownSearch = document.getElementById("dropdown-search");

    searchInput.addEventListener("focus", () => dropdownSearch.classList.add("active"));
    document.addEventListener("click", e => {
        if (!e.target.closest(".search-container")) dropdownSearch.classList.remove("active");
    });
    searchInput.addEventListener("input", () => {
        searchInput.setAttribute("placeholder", searchInput.value.trim() === "" ? "Tìm kiếm sản phẩm" : "");
    });

    const loginBtn = document.getElementById("login-btn");
    const userInfo = document.getElementById("user-info");
    const usernameSpan = document.getElementById("username");
    const userDropdown = document.querySelector(".user-dropdown");
    const logoutBtn = document.getElementById("logout-btn");

    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    if (currentUser) {
        loginBtn.style.display = "none";
        userInfo.classList.remove("hidden");
        usernameSpan.textContent = currentUser.name;
    }

    userInfo.addEventListener("mouseenter", () => userDropdown.style.display = "block");
    userInfo.addEventListener("mouseleave", () => userDropdown.style.display = "none");
    logoutBtn.addEventListener("click", () => {
        localStorage.removeItem("currentUser");
        alert("Bạn đã đăng xuất!");
        window.location.reload();
    });
});

document.querySelectorAll(".dropdown-menu a").forEach(item => {
    item.addEventListener("click", function (event) {
        event.preventDefault();
        const productName = this.textContent.trim();
        window.location.href = `ctsp.php?product=${encodeURIComponent(productName)}`;
    });
});