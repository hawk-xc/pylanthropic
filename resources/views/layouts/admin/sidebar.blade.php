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
                    <a href="{{ route('adm.index') }}"> <i
                            class="metismenu-icon pe-7s-rocket icon-gradient bg-arielle-smile"></i> Dashboards </a>
                </li>
                <li class="{{ $sidebar_menu == 'donate' ? 'mm-active' : '' }}">
                    <a href="{{ route('adm.donate.index') }}"> <i
                            class="metismenu-icon pe-7s-cash icon-gradient bg-arielle-smile"></i> Donasi </a>
                </li>
                <li class="{{ $sidebar_menu == 'donate_mutation' ? 'mm-active' : '' }}">
                    <a href="{{ route('adm.donate.mutation') }}"> <i
                            class="metismenu-icon pe-7s-cash icon-gradient bg-arielle-smile"></i> Donasi x Mutasi </a>
                </li>
                <li class="{{ $sidebar_menu == 'donate_qurban' ? 'mm-active' : '' }}">
                    <a href="{{ route('adm.donate.qurban') }}"> <i
                            class="metismenu-icon pe-7s-cash icon-gradient bg-arielle-smile"></i> Donasi Qurban </a>
                </li>
                <li class="{{ $sidebar_menu == 'ads' ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-albums icon-gradient bg-arielle-smile"></i> ADS
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ $sidebar_menu == 'ads' ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('adm.ads.need.action') }}?id=4"
                                class="{{ $sidebar_submenu == 'ads' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Butuh Tindakan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.ads.balance') }}?id=1"
                                class="{{ $sidebar_submenu == 'balance' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Status & Tagihan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.ads.roas') }}"
                                class="{{ $sidebar_submenu == 'roas' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> ROAS
                            </a>
                        </li>
                        <!-- <li>
                            <a href="{{ route('adm.program-category.index') }}" class="{{ $sidebar_submenu == 'category' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Semua List
                            </a>
                        </li> -->
                        <li>
                            <a href="{{ route('adm.ads.campaign.index') }}"
                                class="{{ $sidebar_submenu == 'campaign' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Campaign
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ $sidebar_menu == 'program' ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-albums icon-gradient bg-arielle-smile"></i> Program
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ $sidebar_menu == 'program' ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('adm.program.index') }}"
                                class="{{ $sidebar_submenu == 'program' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> List Program
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.program-info.index') }}" class="{{ $sidebar_submenu == 'program_info' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Kabar Terbaru
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.program.donate.performance') }}"
                                class="{{ $sidebar_submenu == 'donate_performance' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Performa Donasi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.spent.index') }}"
                                class="{{ $sidebar_submenu == 'program_spent' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Pengeluaran
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.payout.index') }}"
                                class="{{ $sidebar_submenu == 'program_payout' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Penyaluran
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.program-category.index') }}"
                                class="{{ $sidebar_submenu == 'category' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Kategori
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.organization.index') }}"
                                class="{{ $sidebar_submenu == 'organization' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Lembaga
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.shorten-link.index') }}"
                                class="{{ $sidebar_submenu == 'shorten_link' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Tautan Pendek
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.crm-leads.index') }}"
                                class="{{ $sidebar_submenu == 'crm-leads' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Leads CRM
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ $sidebar_menu == 'leads' ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-albums icon-gradient bg-arielle-smile"></i> Leads Program
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ $sidebar_menu == 'leads' ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('adm.leads.index') }}"
                                class="{{ $sidebar_submenu == 'leads' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Pengajuan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.leads.grab.list') }}"
                                class="{{ $sidebar_submenu == 'grab' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> List Grab Program
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.leads.org.list') }}"
                                class="{{ $sidebar_submenu == 'org-list' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> List Lembaga
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.leads.grabdo.platform') }}"
                                class="{{ $sidebar_submenu == 'grab_do' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Grab Do
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ $sidebar_menu == 'person' ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-users icon-gradient bg-arielle-smile"></i> Person
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ $sidebar_menu == 'person' ? 'mm-show' : '' }}">
                        <li>
                            <a class="{{ $sidebar_submenu == 'donatur' ? 'mm-active' : '' }}"
                                href="{{ route('adm.donatur.index') }}">
                                <i class="metismenu-icon"></i> Donatur
                            </a>
                        </li>
                        <li>
                            <a class="{{ $sidebar_submenu == 'donatur_dorman' ? 'mm-active' : '' }}"
                                href="{{ route('adm.donatur.dorman') }}">
                                <i class="metismenu-icon"></i> Donatur Dorman 30D
                            </a>
                        </li>
                        <li>
                            <a class="{{ $sidebar_submenu == 'donatur_tetap' ? 'mm-active' : '' }}"
                                href="{{ route('adm.donatur.tetap') }}">
                                <i class="metismenu-icon"></i> Donatur Tetap
                            </a>
                        </li>
                        <li>
                            <a class="{{ $sidebar_submenu == 'donatur_sultan' ? 'mm-active' : '' }}"
                                href="{{ route('adm.donatur.sultan') }}">
                                <i class="metismenu-icon"></i> Donatur Sultan
                            </a>
                        </li>
                        <li>
                            <a class="{{ $sidebar_submenu == 'donatur_hampir' ? 'mm-active' : '' }}"
                                href="{{ route('adm.donatur.hampir') }}">
                                <i class="metismenu-icon"></i> Hampir Jadi Donatur
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.fundraiser.index') }}">
                                <i class="metismenu-icon"></i> Fundraiser
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ $sidebar_menu == 'report' ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-news-paper icon-gradient bg-arielle-smile"></i>Laporan
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ $sidebar_menu == 'report' ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('adm.report.monthly') }}"
                                class="{{ $sidebar_submenu == 'monthly_report' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Rekap Bulanan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.report.mtm') }}"
                                class="{{ $sidebar_submenu == 'mtm_report' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Month to Month
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.report.collection') }}"
                                class="{{ $sidebar_submenu == 'collection' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Penghimpunan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adm.report.settlement') }}"
                                class="{{ $sidebar_submenu == 'settlement' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i> Settlement
                            </a>
                        </li>
                        <li>
                            <a href="#" href="#">
                                <i class="metismenu-icon"></i> Program
                            </a>
                        </li>
                        <li>
                            <a href="#" href="#">
                                <i class="metismenu-icon"></i> Donatur
                            </a>
                        </li>
                        <li>
                            <a href="#" href="#">
                                <i class="metismenu-icon"></i> Penyaluran
                            </a>
                        </li>
                        <li>
                            <a href="#" href="#">
                                <i class="metismenu-icon"></i> Keuangan
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ $sidebar_menu == 'automate' ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-box1 icon-gradient bg-arielle-smile"></i>Automation
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ $sidebar_menu == 'automate' ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('adm.report.auto.monthly') }}" href="#">
                                <i class="metismenu-icon"></i> Bulanan
                            </a>
                        </li>
                        <li>
                            <a href="#" href="#">
                                <i class="metismenu-icon"></i> Lainnya
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ $sidebar_menu == 'master-data' ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-box1 icon-gradient bg-arielle-smile"></i>Master Data
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
                    <a href="#"> <i class="metismenu-icon pe-7s-rocket icon-gradient bg-arielle-smile"></i> Chat
                        Format </a>
                </li>
                <li class="{{ $sidebar_menu == 'wa-campaign' ? 'mm-active' : '' }}">
                    <a href="#"> <i class="metismenu-icon pe-7s-rocket icon-gradient bg-arielle-smile"></i>
                        Campaign </a>
                </li>
                <li class="{{ $sidebar_menu == 'wa-history' ? 'mm-active' : '' }}">
                    <a href="{{ route('adm.chat.index') }}"> <i
                            class="metismenu-icon pe-7s-rocket icon-gradient bg-arielle-smile"></i> History </a>
                </li>

                <li class="app-sidebar__heading">Lainnya</li>
                <li class="{{ $sidebar_menu == 'logs' ? 'mm-active' : '' }}">
                    <a href="{{ route('adm.logs.index') }}"> <i
                            class="metismenu-icon pe-7s-news-paper icon-gradient bg-arielle-smile"></i> Logs </a>
                </li>
                <li class="{{ $sidebar_menu == 'banners' ? 'mm-active' : '' }}">
                    <a href="{{ route('adm.banner.index') }}"> <i
                            class="metismenu-icon pe-7s-photo icon-gradient bg-arielle-smile"></i> Banner Halaman Utama </a>
                </li>
            </ul>
        </div>
    </div>
</div>
