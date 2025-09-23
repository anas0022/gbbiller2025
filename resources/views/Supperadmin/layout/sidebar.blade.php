


  <!-- MetisMenu jQuery Plugin -->
<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                    data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar ps ps--active-y">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu" id="sidebaritems">
                {{-- <li class="app-sidebar__heading">Menu</li> --}}
                {{-- one side menu start --}}
             


            </ul>
        </div>
        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; height: 260px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 66px;"></div>
        </div>
    </div>
</div>
<!-- <script>
$(document).ready(function () {
    function isActive(path) {
        if (!path) return false;
        return window.location.pathname.startsWith(path);
    }

    window.loadsideMenu = function () {
        $.ajax({
            url: '/sidebar/menu',
            method: 'GET',
            success: function (response) {
                let html = "";

                response.forEach(function (module) {
                    let moduleActive = false;

                    if (module.menu && module.menu.length > 0) {
                        let moduleHtml = `
                            <li>
                                <a href="${module.route || '#'}">
                                    <i class="metismenu-icon ${module.icon || 'fa fa-box'}"></i>
                                    ${module.modulename}
                                    <i class="metismenu-state-icon fa fa-caret-down"></i>
                                </a>
                                <ul>
                        `;

                        module.menu.forEach(function (menu) {
                            let menuActive = false;
                            let subHtml = "";

                            let subs = (module.submenu || []).filter(sub => sub.menu_id === menu.id);
                            if (subs.length > 0) {
                                subHtml += `<ul>`;
                                subs.forEach(function (sub) {
                                    let subActive = isActive(sub.sub_route);
                                    if (subActive) menuActive = true;
                                    subHtml += `
                                        <li>
                                            <a href="${sub.sub_route}" class="${subActive ? 'mm-active' : ''}">
                                                <i class="metismenu-icon"></i>${sub.menuname}
                                            </a>
                                        </li>
                                    `;
                                });
                                subHtml += `</ul>`;
                            }

                            if (isActive(menu.route)) menuActive = true;
                            if (menuActive) moduleActive = true;

                            moduleHtml += `
                                <li class="${menuActive ? 'mm-active' : ''}">
                                    <a href="${menu.route || '#'}" class="${menuActive ? 'mm-active' : ''}">
                                        <i class="metismenu-icon"></i>${menu.Menuname}
                                        ${subs.length > 0 ? '<i class="metismenu-state-icon fa fa-caret-down"></i>' : ''}
                                    </a>
                                    ${subHtml}
                                </li>
                            `;
                        });

                        moduleHtml += `</ul></li>`;

                        if (moduleActive) {
                            moduleHtml = moduleHtml.replace('<li>', '<li class="mm-active">');
                        }

                        html += moduleHtml;
                    }
                });

                let staticMenu = `
                    <li>
                        <a href="#">
                            <i class="metismenu-icon fa fa-box"></i>Create Menu
                            <i class="metismenu-state-icon fa fa-caret-down"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ url('/superadmin/CreateMenu/superadmin') }}">
                                    <i class="metismenu-icon"></i>Super Admin
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('dashboards-commerce.html') }}">
                                    <i class="metismenu-icon"></i>Admin
                                </a>
                            </li>
                        </ul>
                    </li>
                `;

                $('#sidebaritems').metisMenu('dispose');
                $('#sidebaritems').html(staticMenu + html);
                $('#sidebaritems').metisMenu();

                // ✅ Hide preloader only after menu is ready
                var preloader = document.querySelector('.preloader');
                preloader.classList.add('fade-out');
                setTimeout(function () {
                    preloader.style.display = 'none';
                }, 500); // match fade-out duration
            },
            error: function (xhr, status, error) {
                console.error('loadMenu AJAX error:', status, error, xhr.responseText);

                // Even on error, hide preloader
                var preloader = document.querySelector('.preloader');
                preloader.classList.add('fade-out');
                setTimeout(function () {
                    preloader.style.display = 'none';
                }, 500);
            }
        });
    };

    // 🚀 Run menu load when page fully loaded
    window.addEventListener('load', function () {
        loadsideMenu();
    });
});

</script>
 -->



<script>
$(document).ready(function () {
    function isActive(path) {
        if (!path) return false;
        return window.location.pathname.startsWith(path);
    }

    window.loadsideMenu = function () {
        $.getJSON('/sidebar/menu', function (response) {
            let fragment = document.createDocumentFragment();

            // Build dynamic modules
            response.forEach(module => {
                if (!module.menu || module.menu.length === 0) return;

                let li = document.createElement('li');
                let a = document.createElement('a');
                a.href = module.route || '#';
                a.innerHTML = `
                    <i class="metismenu-icon ${module.icon || 'fa fa-box'}"></i>
                    ${module.modulename}
                    <i class="metismenu-state-icon fa fa-caret-down"></i>
                `;
                li.appendChild(a);

                let ul = document.createElement('ul');
                let moduleActive = false;

                module.menu.forEach(menu => {
                    let liMenu = document.createElement('li');
                    let menuActive = false;

                    let aMenu = document.createElement('a');
                    aMenu.href = menu.route || '#';
                    aMenu.textContent = menu.Menuname;

                    if (isActive(menu.route)) {
                        aMenu.classList.add('mm-active');
                        menuActive = true;
                    }

                    // Submenus
                    let subs = (module.submenu || []).filter(sub => sub.menu_id === menu.id);
                    if (subs.length > 0) {
                        let subUl = document.createElement('ul');
                        subs.forEach(sub => {
                            let subLi = document.createElement('li');
                            let subA = document.createElement('a');
                            subA.href = sub.sub_route;
                            subA.textContent = sub.menuname;

                            if (isActive(sub.sub_route)) {
                                subA.classList.add('mm-active');
                                menuActive = true;
                            }

                            subLi.appendChild(subA);
                            subUl.appendChild(subLi);
                        });
                        liMenu.appendChild(subUl);

                        // add caret if submenu exists
                        aMenu.innerHTML = `<i class="metismenu-icon"></i> ${menu.Menuname} 
                                           <i class="metismenu-state-icon fa fa-caret-down"></i>`;
                    }

                    if (menuActive) {
                        liMenu.classList.add('mm-active');
                        moduleActive = true;
                    }

                    liMenu.appendChild(aMenu);
                    ul.appendChild(liMenu);
                });

                if (moduleActive) {
                    li.classList.add('mm-active');
                }

                li.appendChild(ul);
                fragment.appendChild(li);
            });

            // Add static menu
            let staticMenu = document.createElement('li');
            staticMenu.innerHTML = `
                <a href="#">
                    <i class="metismenu-icon fa fa-box"></i>Create Menu
                    <i class="metismenu-state-icon fa fa-caret-down"></i>
                </a>
                <ul>
                    <li>
                        <a href="{{ url('/superadmin/CreateMenu/superadmin') }}">
                            <i class="metismenu-icon"></i>Super Admin
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('dashboards-commerce.html') }}">
                            <i class="metismenu-icon"></i>Admin
                        </a>
                    </li>
                </ul>
            `;
            fragment.prepend(staticMenu);

            // ✅ Insert into DOM only once
            let sidebar = document.getElementById('sidebaritems');
            sidebar.innerHTML = "";
            sidebar.appendChild(fragment);

            // ✅ Initialize metisMenu
            $(sidebar).metisMenu();

            // ✅ Hide preloader
            var preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.classList.add('fade-out');
                setTimeout(() => preloader.style.display = 'none', 300);
            }
        }).fail(() => {
            var preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.classList.add('fade-out');
                setTimeout(() => preloader.style.display = 'none', 300);
            }
        });
    };

    // 🚀 Load menu after page load
    window.addEventListener('load', function () {
        loadsideMenu();
    });
});
</script>
