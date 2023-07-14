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
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Menu</li>
                <li class="{{ $sidebar_menu == 'dashboard' ? 'mm-active' : '' }} ">
                    <a href="{{ route('adm.index') }}"> <i class="metismenu-icon pe-7s-rocket"></i> Dashboards </a>
                </li>
                <li class="{{ $sidebar_menu == 'donate' ? 'mm-active' : '' }}">
                    <a href="{{ route('adm.donate.index') }}"> <i class="metismenu-icon pe-7s-rocket"></i> Donasi </a>
                </li>
                <li class="{{ $sidebar_menu == 'program' ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-browser"></i> Program
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ $sidebar_menu == 'program' ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('adm.program.index') }}" class="{{ $sidebar_submenu == 'program' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> List Program
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.program-category.index') }}" class="{{ $sidebar_submenu == 'category' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Kategori 
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.organization.index') }}" class="{{ $sidebar_submenu == 'organization' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Lembaga 
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ $sidebar_menu == 'person' ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-rocket"></i> Person
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ $sidebar_menu == 'person' ? 'mm-show' : '' }}">
                        <li>
                            <a class="{{ $sidebar_submenu == 'donatur' ? 'mm-active' : '' }}" href="{{ route('adm.donatur.index') }}">
                                <i class="metismenu-icon"></i> Donatur 
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.fundraiser.index') }}">
                                <i class="metismenu-icon"></i> Fundraiser 
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ $sidebar_menu == 'master-data' ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-rocket"></i>Master Data
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ $sidebar_menu == 'master-data' ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('adm.user.index') }}" href="#">
                                <i class="metismenu-icon"></i> Admin 
                            </a>
                        </li>
                        <li>
                            <a href="#" href="#">
                                <i class="metismenu-icon"></i> Settings 
                            </a>
                        </li>
                        <li>
                            <a href="#" href="#">
                                <i class="metismenu-icon"></i> Kebijakan Privasi 
                            </a>
                        </li>
                        <li>
                            <a href="#" href="#">
                                <i class="metismenu-icon"></i> Tentang Kami 
                            </a>
                        </li>
                        <li>
                            <a href="#" href="#">
                                <i class="metismenu-icon"></i> FAQ 
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="app-sidebar__heading">Auto WA</li>
                <li class="{{ $sidebar_menu == 'chat-format' ? 'mm-active' : '' }}">
                    <a href="#"> <i class="metismenu-icon pe-7s-rocket"></i> Chat Format </a>
                </li>
                <li class="{{ $sidebar_menu == 'wa-campaign' ? 'mm-active' : '' }}">
                    <a href="#"> <i class="metismenu-icon pe-7s-rocket"></i> Campaign </a>
                </li>
                <li class="{{ $sidebar_menu == 'wa-history' ? 'mm-active' : '' }}">
                    <a href="#"> <i class="metismenu-icon pe-7s-rocket"></i> History </a>
                </li>
            </ul>
        </div>
    </div>
</div>