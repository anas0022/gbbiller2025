


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
        $.ajax({
            url: '/sidebar/menu',
            method: 'GET',
            success: function (response) {
                let modules = [];

                response.forEach(function (module) {
                    if (module.menu && module.menu.length > 0) {
                        let moduleActive = false;
                        let moduleParts = [];

                        moduleParts.push(`
                            <li>
                                <a href="${module.route || '#'}">
                                    <i class="metismenu-icon ${module.icon || 'fa fa-box'}"></i>
                                    ${module.modulename}
                                    <i class="metismenu-state-icon fa fa-caret-down"></i>
                                </a>
                                <ul>
                        `);

                        module.menu.forEach(function (menu) {
                            let menuActive = false;
                            let subs = (module.submenu || []).filter(sub => sub.menu_id === menu.id);
                            let subParts = [];

                            if (subs.length > 0) {
                                subParts.push(`<ul>`);
                                subs.forEach(function (sub) {
                                    let subActive = isActive(sub.sub_route);
                                    if (subActive) menuActive = true;
                                    subParts.push(`
                                        <li>
                                            <a href="${sub.sub_route}" class="${subActive ? 'mm-active' : ''}">
                                                <i class="metismenu-icon"></i>${sub.menuname}
                                            </a>
                                        </li>
                                    `);
                                });
                                subParts.push(`</ul>`);
                            }

                            if (isActive(menu.route)) menuActive = true;
                            if (menuActive) moduleActive = true;

                            moduleParts.push(`
                                <li class="${menuActive ? 'mm-active' : ''}">
                                    <a href="${menu.route || '#'}" class="${menuActive ? 'mm-active' : ''}">
                                        <i class="metismenu-icon"></i>${menu.Menuname}
                                        ${subs.length > 0 ? '<i class="metismenu-state-icon fa fa-caret-down"></i>' : ''}
                                    </a>
                                    ${subParts.join("")}
                                </li>
                            `);
                        });

                        moduleParts.push(`</ul></li>`);

                        if (moduleActive) {
                            moduleParts[0] = moduleParts[0].replace('<li>', '<li class="mm-active">');
                        }

                        modules.push(moduleParts.join(""));
                    }
                });

                // Static menu
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

                // ✅ Update DOM once
                $('#sidebaritems')
                    .metisMenu('dispose') // reset
                    .html(staticMenu + modules.join("")) // insert all at once
                    .metisMenu(); // re-init

                // ✅ Hide preloader after menu is ready
                var preloader = document.querySelector('.preloader');
                preloader.classList.add('fade-out');
                setTimeout(() => preloader.style.display = 'none', 300);
            },
            error: function () {
                var preloader = document.querySelector('.preloader');
                preloader.classList.add('fade-out');
                setTimeout(() => preloader.style.display = 'none', 300);
            }
        });
    };

    window.addEventListener('load', function () {
        loadsideMenu();
    });
});

 </script>