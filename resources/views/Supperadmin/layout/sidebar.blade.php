{{-- 
</div> 
{{-- <style>
  .vertical-nav-menu ul > li > a.mm-active {
    color: #fff;
  background: #07151e91;
  }
  </style> 
<div class="scrollbar-sidebar">
 <div class="app-sidebar__inner" style="padding: 5px;">
    <ul class="vertical-nav-menu">
{{-- <li class="app-sidebar__heading">Menu</li> --}}
{{-- one side menu start 
<li class="{{ request()->is('superadmin/Create Menu/*') ? 'mm-active' : '' }}">
    <a href="#">
        <i class="metismenu-icon fa fa-box"></i>Create Menu
        <i class="metismenu-state-icon fa fa-caret-down"></i>
    </a>
    <ul>
        <li>
            <a href="{{ url('/superadmin/Create Menu/superadmin') }}" 
               class="{{ request()->is('superadmin/Create Menu/superadmin') ? 'mm-active' : '' }}">
                <i class="metismenu-icon"></i>Super Admin
            </a>
        </li>
        <li>
            <a href="{{ url('dashboards-commerce.html') }}" 
               class="{{ request()->is('dashboards-commerce.html') ? 'mm-active' : '' }}">
                <i class="metismenu-icon"></i>Admin
            </a>
        </li>
    </ul>
</li>

        {{-- one side menu end 
        </ul>

</div>
</div>
</div>
</div> --}}
<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
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
            <ul class="vertical-nav-menu">
                {{-- <li class="app-sidebar__heading">Menu</li> --}}
                {{-- one side menu start --}}
                <li class="{{ request()->is('superadmin/Create Menu/*') ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon fa fa-box"></i>Create Menu
                        <i class="metismenu-state-icon fa fa-caret-down"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ url('/superadmin/Create Menu/superadmin') }}"
                                class="{{ request()->is('superadmin/Create Menu/superadmin') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>Super Admin
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('dashboards-commerce.html') }}"
                                class="{{ request()->is('dashboards-commerce.html') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>Admin
                            </a>
                        </li>
                    </ul>
                </li>


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
