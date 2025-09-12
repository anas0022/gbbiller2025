<div class="app-header header-shadow">
    <div class="app-header__logo">
        <div class="logo-src" style="height:auto;">
            {{--  <img src="{{ asset('images/GB_logo.png') }}" alt="Logo" width="50%"> --}}
            <img src="http://127.0.0.1:8000/images/Group 11(3).png" alt="Logo"
                style="filter: invert(1) brightness(0); width:50px;">
        </div>
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
    <div class="app-header__content">

        <div class="app-header-right">
            <div class="header-dots">

                <div class="dropdown">
                    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown"
                        class="p-0 mr-2 btn btn-link">
                        <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                            <span class="icon-wrapper-bg bg-danger"></span>
                            <i class="icon text-danger icon-anim-pulse ion-android-notifications"></i>
                            <span class="badge badge-dot badge-dot-sm badge-danger">Notifications</span>
                        </span>
                    </button>

                </div>


            </div>
            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                    <i class="fas fa-user-shield" style="font-size: 20px;"></i>
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true"
                                    class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-info">

                                            <div class="menu-header-content text-left">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-3">
                                                            <i class="fas fa-user-shield" style="font-size: 20px;"></i>
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">{{ auth()->user()->name }}</div>
                                                            <div class="widget-subheading opacity-8">
                                                                {{ auth()->user()->role }}</div>
                                                        </div>
                                                        <div class="widget-content-right mr-2">
                                                            <button class="btn-pill btn-shadow btn-shine btn btn-focus"
                                                                onclick="window.location.href='{{ route('logout') }}'">
                                                                Logout
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                </div>
                            </div>
                        </div>
                        <div class="widget-content-left  ml-3 header-user-info">

                            <div class="widget-heading"> {{ auth()->user()->name }} </div>
                            <div class="widget-subheading"> {{ auth()->user()->role }} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
