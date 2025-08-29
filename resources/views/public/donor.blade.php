@extends('layouts.public', [
    'second_title' => 'Donatur',
])

@section('css_plugins')
<style>
.switch-container {
    background-color: #f1f5f9;
    border-radius: 50px;
    padding: 4px;
    display: inline-block;
}
.switch-pills {
    display: flex;
    align-items: center;
}
.switch-link {
    background-color: transparent;
    color: #475569;
    border-radius: 50px;
    padding: 0.35rem 0.75rem;
    transition: all 0.3s ease;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
}
.switch-link:hover {
    color: #0d6efd;
}
.switch-link.active {
    background-color: #ffffff;
    color: #6698e2ff;
    font-weight: 600;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
}
</style>
@endsection

@section('js_plugins')
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="{{ asset('public') }}/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-lazyload@1.9.7/jquery.lazyload.min.js"></script>
@endsection

@section('page_title', 'Donatur')

@section('content')
    <header class="section-t-space pt-0">
        <div class="header-panel header-title header-transparent">
            <a href="{{ url('/' . $program->slug) }}" aria-label="Back to Program">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
        </div>
    </header>

    <div class="">
        <img alt="{{ ucwords($program->title) }}" class="h-auto w-100 lazyload"
            src="{{ asset('public/images/program/' . $program->image) }}">
    </div>

    <!-- Detail section start  -->
    <section class="pt-3">
        <div class="custom-container">
            <h4 class="title-detail-program">{{ ucwords($program->title) }}</h4>
            <div class="short-desc mt-2 pt-1">{{ $program->short_desc }}</div>
            <div class="mt-3 donate-collect"> Rp {{ number_format($sum_amount) }}</div>
            <div class="row mt-1 pb-1 fs-15">
                <div class="col-8">
                    Kebutuhan <span class="fw-semibold">Rp {{ number_format($program->nominal_approved) }}</span>
                </div>
                <div class="col-4 text-end">
                    <span class="fw-semibold">{{ now()->diffInDays(substr($program->end_date, 0, 10)) }}</span> hari
                </div>
            </div>
            @if(isset($program->nominal_approved) && $program->nominal_approved > 0)
            <div class="progress mt-2" role="progressbar" aria-label="Basic example" aria-valuenow="{{ ceil(($sum_amount / $program->nominal_approved) * 100) }}" aria-valuemin="0"
                aria-valuemax="100" style="height: 5px">
                <div class="progress-bar" 
                    style="width: {{ ceil(($sum_amount / $program->nominal_approved) * 100) }}%"></div>
            </div>
            @endif
            <div class="mt-2 row d-flex align-content-center text-center">
                <a href="{{ route('program.donor', $program->slug) }}" class="col-4 btn-donate-detail1">
                    <div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" role="img">
                            <path
                                d="M8.687 4.121c1.03.228 1.895.754 2.633 1.5.226.228.474.435.68.623.25-.232.53-.487.806-.747 1.088-1.025 2.364-1.571 3.877-1.483 1.045.06 1.97.442 2.71 1.18 1.438 1.435 1.918 3.15 1.411 5.122-.26 1.008-.742 1.914-1.322 2.77-.906 1.336-2.018 2.49-3.207 3.574-1.146 1.045-2.367 1.998-3.616 2.916a.993.993 0 0 1-1.168.004l-2.11-1.514c-1.375-1.084-2.659-2.266-3.8-3.596-.747-.87-1.41-1.799-1.901-2.84-.36-.76-.608-1.551-.667-2.394-.08-1.151.22-2.202.856-3.162C4.971 4.415 6.74 3.692 8.687 4.121Z"
                                fill="#3BA8DD"></path>
                        </svg>
                        <span class="inline-block fs-14 fw-semibold">{{ number_format($count_donate) }}</span>
                    </div>
                    <div class="fs-13">Donasi</div>
                </a>
                <a href="{{ route('program.info', $program->slug) }}" class="col-4 btn-donate-detail2">
                    <div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" role="img">
                            <path
                                d="M17.354 21H18a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3h-.646A4.482 4.482 0 0 1 18.5 6v12a4.484 4.484 0 0 1-1.146 3Z"
                                fill="#3BA8DD"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10.25 3a.75.75 0 0 1 .75-.75h3A3.75 3.75 0 0 1 17.75 6v12A3.75 3.75 0 0 1 14 21.75H6A3.75 3.75 0 0 1 2.25 18v-7a.75.75 0 0 1 1.5 0v7A2.25 2.25 0 0 0 6 20.25h8A2.25 2.25 0 0 0 16.25 18V6A2.25 2.25 0 0 0 14 3.75h-3a.75.75 0 0 1-.75-.75Z"
                                fill="#6A6A6A"></path>
                            <path
                                d="M9.5 15.5h2.75a.75.75 0 0 0 0-1.5H9.5a.75.75 0 0 0 0 1.5ZM14.25 18.5H9.5a.75.75 0 0 1 0-1.5h4.75a.75.75 0 0 1 0 1.5ZM5.75 15.5h.5a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5ZM7 17.75a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5a.75.75 0 0 1 .75.75Z"
                                fill="#6A6A6A"></path>
                            <path d="M6 9H3l6-6v3a3 3 0 0 1-3 3Z" fill="#3BA8DD"></path>
                        </svg>
                        <span class="fw-semibold fs-14">{{ number_format($sum_news) }}</span>
                    </div>
                    <div class="fs-13">Kabar Terbaru</div>
                </a>
                <a href={{ route('program.payout', $program->slug) }} class="col-4 btn-donate-detail3">
                    <div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" role="img" class="mt-0.5">
                            <path
                                d="M21 13.65a1 1 0 1 0-2 0v2a2 2 0 0 1-2 2h-5.5l.6-.45a1 1 0 1 0-1.2-1.6l-3 2.25a1 1 0 0 0 0 1.6l3 2.25a1 1 0 0 0 1.2-1.6l-.6-.45H17a4 4 0 0 0 4-4v-2Z"
                                fill="#3BA8DD"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5 3.75A2.25 2.25 0 0 0 2.75 6v6A2.25 2.25 0 0 0 5 14.25h2.5a.75.75 0 0 1 0 1.5H5A3.75 3.75 0 0 1 1.25 12V6A3.75 3.75 0 0 1 5 2.25h14A3.75 3.75 0 0 1 22.75 6v6a.75.75 0 0 1-1.5 0V6A2.25 2.25 0 0 0 19 3.75H5ZM13.75 15a.75.75 0 0 1 .75-.75H17a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75Z"
                                fill="#6A6A6A"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                fill="#6A6A6A"></path>
                        </svg>
                        <span class="fw-semibold fs-14">{{ number_format($count_payout) }} kali</span>
                    </div>
                    <div class="fs-13">Salur Dana</div>
                </a>
            </div>
        </div>
    </section>
    <!-- filter section end  -->

    <!-- Empty section start -->
    <section class="empty-section section-t-space section-b-space pb-0 pt-3">
        <div class="custom-container space-empty pb-2">
        </div>
    </section>
    <!-- Empty section end -->

    <section class="section-t-space pt-0 mt-1" style="padding-bottom: 80px;">
        <div class="custom-container">
            <div class="row gy-3 pt-4">
                <div class="col-12">
                    <h3 class="fw-bold fs-16 mb-2" id="donasi">Donatur ({{ number_format($count_donate) }})</h3>
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <div class="switch-container">
                        <div class="switch-pills">
                            <a class="switch-link {{ ($sort ?? 'terbaru') == 'terbaru' ? 'active' : '' }}" href="{{ route('program.donor', ['slug' => $program->slug, 'sort' => 'terbaru']) }}">Terbaru</a>
                            <a class="switch-link {{ $sort == 'terbesar' ? 'active' : '' }}" href="{{ route('program.donor', ['slug' => $program->slug, 'sort' => 'terbesar']) }}">Terbesar</a>
                            <a class="switch-link {{ $sort == 'berpesan' ? 'active' : '' }}" href="{{ route('program.donor', ['slug' => $program->slug, 'sort' => 'berpesan']) }}">Berpesan</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gy-3 mt-2" id="donor-list">
                @include('public.partials.donor_items', ['donors' => $donors])
            </div>

            <div id="loading" style="display: none; text-align: center; padding: 20px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </section>

    @endsection

@section('content_modal')
    <!-- cart popup start -->
    <div class="cart-popup">
        <button class="btn share-btn me-2">
            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                role="img" class="mr-2 w-4">
                <path
                    d="M17.5 10A3.5 3.5 0 1 0 14 6.5c0 .43-.203.86-.595 1.037L10.034 9.07c-.427.194-.924.052-1.283-.25a3.5 3.5 0 1 0-.2 5.517c.38-.275.885-.381 1.297-.156l3.585 1.955c.412.225.597.707.572 1.176a3.5 3.5 0 1 0 1.445-2.649c-.38-.275-.886.381-1.298.156l-3.585-1.955c-.412-.225-.597-.707-.572-1.176.003-.062.005-.125.005-.188 0-.43.203-.86.595-1.037l3.371-1.533c.428-.194.924-.052 1.283.25.609.512 1.394.82 2.251.82Z"
                    fill="#3BA8DD"></path>
            </svg>
            Bagikan
        </button>
        <?php
        $uri = explode('?', url()->full());
        if (!empty($uri[1])) {
            $uri_param = '?' . $uri[1];
        } else {
            $uri_param = '';
        }
        ?>
        <a href="{{ route('donate.amount', $program->slug) . $uri_param }}" class="btn donate-btn">Donasi Sekarang</a>
    </div>
    <!-- cart popup end -->

    <!-- pwa install app popup start -->
    <div class="offcanvas offcanvas-bottom addtohome-popup theme-offcanvas rounded-top-8" tabindex="-1" id="offcanvas">
        <div class="offcanvas-body text-center rounded-top-8">
            <h2 class="fw-bold mb-4 d-flex fs-16 justify-content-between">
                <span>Bagikan Program</span>
                <button type="button" class="btn-share-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </h2>
            <div class="d-flex flex-wrap">
                <button aria-label="facebook" class="btn-icon-share">
                    <svg viewBox="0 0 64 64" width="36" height="36">
                        <rect width="64" height="64" rx="10" ry="10" fill="#3b5998"></rect>
                        <path
                            d="M34.1,47V33.3h4.6l0.7-5.3h-5.3v-3.4c0-1.5,0.4-2.6,2.6-2.6l2.8,0v-4.8c-0.5-0.1-2.2-0.2-4.1-0.2 c-4.1,0-6.9,2.5-6.9,7V28H24v5.3h4.6V47H34.1z"
                            fill="white"></path>
                    </svg>
                    <span class="mt-2 fs-14 lh-24">Facebook</span>
                </button>
                <button aria-label="twitter" class="btn-icon-share">
                    <svg viewBox="0 0 64 64" width="36" height="36">
                        <rect width="64" height="64" rx="10" ry="10" fill="#00aced"></rect>
                        <path
                            d="M48,22.1c-1.2,0.5-2.4,0.9-3.8,1c1.4-0.8,2.4-2.1,2.9-3.6c-1.3,0.8-2.7,1.3-4.2,1.6 C41.7,19.8,40,19,38.2,19c-3.6,0-6.6,2.9-6.6,6.6c0,0.5,0.1,1,0.2,1.5c-5.5-0.3-10.3-2.9-13.5-6.9c-0.6,1-0.9,2.1-0.9,3.3 c0,2.3,1.2,4.3,2.9,5.5c-1.1,0-2.1-0.3-3-0.8c0,0,0,0.1,0,0.1c0,3.2,2.3,5.8,5.3,6.4c-0.6,0.1-1.1,0.2-1.7,0.2c-0.4,0-0.8,0-1.2-0.1 c0.8,2.6,3.3,4.5,6.1,4.6c-2.2,1.8-5.1,2.8-8.2,2.8c-0.5,0-1.1,0-1.6-0.1c2.9,1.9,6.4,2.9,10.1,2.9c12.1,0,18.7-10,18.7-18.7 c0-0.3,0-0.6,0-0.8C46,24.5,47.1,23.4,48,22.1z"
                            fill="white"></path>
                    </svg>
                    <span class="mt-2 fs-14 lh-24">Twitter</span>
                </button>
                <button aria-label="whatsapp" class="btn-icon-share">
                    <svg viewBox="0 0 64 64" width="36" height="36">
                        <rect width="64" height="64" rx="10" ry="10" fill="#25D366"></rect>
                        <path
                            d="m42.32286,33.93287c-0.5178,-0.2589 -3.04726,-1.49644 -3.52105,-1.66732c-0.4712,-0.17346 -0.81554,-0.2589 -1.15987,0.2589c-0.34175,0.51004 -1.33075,1.66474 -1.63108,2.00648c-0.30032,0.33658 -0.60064,0.36247 -1.11327,0.12945c-0.5178,-0.2589 -2.17994,-0.80259 -4.14759,-2.56312c-1.53269,-1.37217 -2.56312,-3.05503 -2.86603,-3.57283c-0.30033,-0.5178 -0.03366,-0.80259 0.22524,-1.06149c0.23301,-0.23301 0.5178,-0.59547 0.7767,-0.90616c0.25372,-0.31068 0.33657,-0.5178 0.51262,-0.85437c0.17088,-0.36246 0.08544,-0.64725 -0.04402,-0.90615c-0.12945,-0.2589 -1.15987,-2.79613 -1.58964,-3.80584c-0.41424,-1.00971 -0.84142,-0.88027 -1.15987,-0.88027c-0.29773,-0.02588 -0.64208,-0.02588 -0.98382,-0.02588c-0.34693,0 -0.90616,0.12945 -1.37736,0.62136c-0.4712,0.5178 -1.80194,1.76053 -1.80194,4.27186c0,2.51134 1.84596,4.945 2.10227,5.30747c0.2589,0.33657 3.63497,5.51458 8.80262,7.74113c1.23237,0.5178 2.1903,0.82848 2.94111,1.08738c1.23237,0.38836 2.35599,0.33657 3.24402,0.20712c0.99159,-0.15534 3.04985,-1.24272 3.47963,-2.45956c0.44013,-1.21683 0.44013,-2.22654 0.31068,-2.45955c-0.12945,-0.23301 -0.46601,-0.36247 -0.98382,-0.59548m-9.40068,12.84407l-0.02589,0c-3.05503,0 -6.08417,-0.82849 -8.72495,-2.38189l-0.62136,-0.37023l-6.47252,1.68286l1.73463,-6.29129l-0.41424,-0.64725c-1.70875,-2.71846 -2.6149,-5.85116 -2.6149,-9.07706c0,-9.39809 7.68934,-17.06155 17.15993,-17.06155c4.58253,0 8.88029,1.78642 12.11655,5.02268c3.23625,3.21036 5.02267,7.50812 5.02267,12.06476c-0.0078,9.3981 -7.69712,17.06155 -17.14699,17.06155m14.58906,-31.58846c-3.93529,-3.80584 -9.1133,-5.95471 -14.62789,-5.95471c-11.36055,0 -20.60848,9.2065 -20.61625,20.52564c0,3.61684 0.94757,7.14565 2.75211,10.26282l-2.92557,10.63564l10.93337,-2.85309c3.0136,1.63108 6.4052,2.4958 9.85634,2.49839l0.01037,0c11.36574,0 20.61884,-9.2091 20.62403,-20.53082c0,-5.48093 -2.14111,-10.64081 -6.03239,-14.51915"
                            fill="white"></path>
                    </svg>
                    <span class="mt-2 fs-14 lh-24">Whatsapp</span>
                </button>
                <button aria-label="telegram" class="btn-icon-share">
                    <svg viewBox="0 0 64 64" width="36" height="36">
                        <rect width="64" height="64" rx="10" ry="10" fill="#37aee2"></rect>
                        <path
                            d="m45.90873,15.44335c-0.6901,-0.0281 -1.37668,0.14048 -1.96142,0.41265c-0.84989,0.32661 -8.63939,3.33986 -16.5237,6.39174c-3.9685,1.53296 -7.93349,3.06593 -10.98537,4.24067c-3.05012,1.1765 -5.34694,2.05098 -5.4681,2.09312c-0.80775,0.28096 -1.89996,0.63566 -2.82712,1.72788c-0.23354,0.27218 -0.46884,0.62161 -0.58825,1.10275c-0.11941,0.48114 -0.06673,1.09222 0.16682,1.5716c0.46533,0.96052 1.25376,1.35737 2.18443,1.71383c3.09051,0.99037 6.28638,1.93508 8.93263,2.8236c0.97632,3.44171 1.91401,6.89571 2.84116,10.34268c0.30554,0.69185 0.97105,0.94823 1.65764,0.95525l-0.00351,0.03512c0,0 0.53908,0.05268 1.06412,-0.07375c0.52679,-0.12292 1.18879,-0.42846 1.79109,-0.99212c0.662,-0.62161 2.45836,-2.38812 3.47683,-3.38552l7.6736,5.66477l0.06146,0.03512c0,0 0.84989,0.59703 2.09312,0.68132c0.62161,0.04214 1.4399,-0.07726 2.14229,-0.59176c0.70766,-0.51626 1.1765,-1.34683 1.396,-2.29506c0.65673,-2.86224 5.00979,-23.57745 5.75257,-27.00686l-0.02107,0.08077c0.51977,-1.93157 0.32837,-3.70159 -0.87096,-4.74991c-0.60054,-0.52152 -1.2924,-0.7498 -1.98425,-0.77965l0,0.00176zm-0.2072,3.29069c0.04741,0.0439 0.0439,0.0439 0.00351,0.04741c-0.01229,-0.00351 0.14048,0.2072 -0.15804,1.32576l-0.01229,0.04214l-0.00878,0.03863c-0.75858,3.50668 -5.15554,24.40802 -5.74203,26.96472c-0.08077,0.34417 -0.11414,0.31959 -0.09482,0.29852c-0.1756,-0.02634 -0.50045,-0.16506 -0.52679,-0.1756l-13.13468,-9.70175c4.4988,-4.33199 9.09945,-8.25307 13.744,-12.43229c0.8218,-0.41265 0.68483,-1.68573 -0.29852,-1.70681c-1.04305,0.24584 -1.92279,0.99564 -2.8798,1.47502c-5.49971,3.2626 -11.11882,6.13186 -16.55882,9.49279c-2.792,-0.97105 -5.57873,-1.77704 -8.15298,-2.57601c2.2336,-0.89555 4.00889,-1.55579 5.75608,-2.23009c3.05188,-1.1765 7.01687,-2.7042 10.98537,-4.24067c7.94051,-3.06944 15.92667,-6.16346 16.62028,-6.43037l0.05619,-0.02283l0.05268,-0.02283c0.19316,-0.0878 0.30378,-0.09658 0.35471,-0.10009c0,0 -0.01756,-0.05795 -0.00351,-0.04566l-0.00176,0zm-20.91715,22.0638l2.16687,1.60145c-0.93... [truncated]
                            fill="white"></path>
                    </svg>
                    <span class="mt-2 fs-14 lh-24">Telegram</span>
                </button>
                <button aria-label="line" class="btn-icon-share">
                    <svg viewBox="0 0 64 64" width="36" height="36">
                        <rect width="64" height="64" rx="10" ry="10" fill="#00b800"></rect>
                        <path
                            d="M52.62 30.138c0 3.693-1.432 7.019-4.42 10.296h.001c-4.326 4.979-14 11.044-16.201 11.972-2.2.927-1.876-.591-1.786-1.112l.294-1.765c.069-.527.142-1.343-.066-1.865-.232-.574-1.146-.872-1.817-1.016-9.909-1.31-17.245-8.238-17.245-16.51 0-9.226 9.251-16.733 20.62-16.733 11.37 0 20.62 7.507 20.62 16.733zM27.81 25.68h-1.446a.402.402 0 0 0-.402.401v8.985c0 .221.18.4.402.4h1.446a.401.401 0 0 0 .402-.4v-8.985a.402.402 0 0 0-.402-.401zm9.956 0H36.32a.402.402 0 0 0-.402.401v5.338L31.8 25.858a.39.39 0 0 0-.031-.04l-.002-.003-.024-.025-.008-.007a.313.313 0 0 0-.032-.026.255.255 0 0 1-.021-.014l-.012-.007-.021-.012-.013-.006-.023-.01-.013-.005-.024-.008-.014-.003-.023-.005-.017-.002-.021-.003-.021-.002h-1.46a.402.402 0 0 0-.402.401v8.985c0 .221.18.4.402.4h1.446a.401.401 0 0 0 .402-.4v-5.337l4.123 5.568c.028.04.063.072.101.099l.004.003a.236.236 0 0 0 .025.015l.012.006.019.01a.154.154 0 0 1 .019.008l.012.004.028.01.005.001a.442.442 0 0 0 .104.013h1.446a.4.4 0 0 0 .401-.4v-8.985a.402.402 0 0 0-.401-.401zm-13.442 7.537h-3.93v-7.136a.401.401 0 0 0-.401-.401h-1.447a.4.4 0 0 0-.401.401v8.984a.392.392 0 0 0 .123.29c.072.068.17.111.278.111h5.778a.4.4 0 0 0 .401-.401v-1.447a.401.401 0 0 0-.401-.401zm21.429-5.287c.222 0 .401-.18.401-.402v-1.446a.401.401 0 0 0-.401-.402h-5.778a.398.398 0 0 0-.279.113l-.005.004-.006.008a.397.397 0 0 0-.111.276v8.984c0 .108.043.206.112.278l.005.006a.401.401 0 0 0 .284.117h5.778a.4.4 0 0 0 .401-.401v-1.447a.401.401 0 0 0-.401-.401h-3.93v-1.519h3.93c.222 0 .401-.18.401-.402V29.85a.401.401 0 0 0-.401-.402h-3.93V27.93h3.93z"
                            fill="white"></path>
                    </svg>
                    <span class="mt-2 fs-14 lh-24">Line</span>
                </button>
                <button aria-label="linkedin" class="btn-icon-share">
                    <svg viewBox="0 0 64 64" width="36" height="36">
                        <rect width="64" height="64" rx="10" ry="10" fill="#007fb1"></rect>
                        <path
                            d="M20.4,44h5.4V26.6h-5.4V44z M23.1,18c-1.7,0-3.1,1.4-3.1,3.1c0,1.7,1.4,3.1,3.1,3.1 c1.7,0,3.1-1.4,3.1-3.1C26.2,19.4,24.8,18,23.1,18z M39.5,26.2c-2.6,0-4.4,1.4-5.1,2.8h-0.1v-2.4h-5.2V44h5.4v-8.6 c0-2.3,0.4-4.5,3.2-4.5c2.8,0,2.8,2.6,2.8,4.6V44H46v-9.5C46,29.8,45,26.2,39.5,26.2z"
                            fill="white"></path>
                    </svg>
                    <span class="mt-2 fs-14 lh-24">LinkedIn</span>
                </button>
                <button aria-label="email" class="btn-icon-share">
                    <svg viewBox="0 0 64 64" width="36" height="36">
                        <rect width="64" height="64" rx="10" ry="10" fill="#7f7f7f"></rect>
                        <path d="M17,22v20h30V22H17z M41.1,25L32,32.1L22.9,25H41.1z M20,39V26.6l12,9.3l12-9.3V39H20z"
                            fill="white"></path>
                    </svg>
                    <span class="mt-2 fs-14 lh-24">Email</span>
                </button>
                <button title="" type="button" class="btn-icon-share"
                    data-clipboard-text="{{ url('/') . '/' . $program->slug }}">
                    <div class="icon-copy-url" style="width: 36px; height: 36px; border-radius: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                    </div>
                    <span class="mt-2 fs-14 lh-24">Salin URL</span>
                </button>
            </div>

        </div>
    </div>
    <!-- pwa install app popup start -->

    <div class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true" id="copyUrlToast">
        <div class="d-flex">
            <div class="toast-body">
                <strong>URL</strong> berhasil disalin ke clipboard
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
@endsection

@section('js_inline')
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    // jQuery dependent scripts
    if (window.jQuery) {
        $("img.lazyload").lazyload();

        $(".share-btn").on("click", function() {
            var myOffcanvas = document.getElementById("offcanvas");
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
            bsOffcanvas.show();
        });

        // action link share
        $(".btn-icon-share").on("click", function() {
            let name = $(this).attr('aria-label');
            let uri = "{{ url('/') . '/' . $program->slug }}";
            let txt =
                'Jangan%20biarakan%20mereka%20merasa%20sendirian!%0AYuk%20berinfaq%20untuk%20memuliakan%20dan%20membahagiakan%20adik-adik%20yatim%20yang%20membutuhkan%20bantuan..%20Bantu%20Donasi%20dengan%20klik';
            let txt2 =
                'Jangan biarkan mereka merasa sendirian! Yuk bantu bersama yang membutuhkan bantuan, dengan klik';
            let utm = 'utm_source=';
            let utm2 =
                'utm_source%3Dsocialsharing_donor_web_null%26utm_medium%3Dshare_campaign_whatsapp%26utm_campaign%3Dshare_detail_campaign';
            if (name == 'facebook') {
                var url = encodeURI('https://www.facebook.com/sharer/sharer.php?u=' + uri + '?' + utm +
                    'fb&quote%3D' + txt2);
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'twitter') {
                let url = encodeURI('https://twitter.com/intent/tweet?url=' + uri + '?' + utm + 'tw&text=' + txt2);
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'whatsapp') {
                let url = encodeURI('https://api.whatsapp.com/send?phone=&text=' + txt2 + ' ' + uri + '?' + utm +
                    'wa');
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'telegram') {
                let url = encodeURI('https://telegram.me/share/url?url=' + uri + '&text={{ $program->title }}');
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'line') {
                let url = encodeURI('https://social-plugins.line.me/lineit/share?url=' + uri + '?' + utm +
                    'line&text=' + txt2);
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'linkedin') {
                let url = encodeURI('https://www.linkedin.com/shareArticle?url=' + uri +
                    '&mini=true&title={{ $program->title }}&summary={{ $program->short_desc }}&source={{ url('/') }}'
                    );
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'email') {
                let url = encodeURI(
                    'mailto:Bantubersama.com<contact@bantubersama.com>?subject={{ $program->title }}&body=' +
                    txt2 + ' ' + uri);
                window.open(url);
            } else {
                let link_share = $(this).attr('data-clipboard-text');
                navigator.clipboard.writeText(link_share);
                $('#copyUrlToast').toast({
                    animation: false,
                    delay: 3000
                });
                $('#copyUrlToast').toast('show');
            }
        });
    }

    // Infinite scroll
    let page = 1;
    let lastPage = {{ $donors->lastPage() }};
    let isLoading = false;
    const donorList = document.getElementById('donor-list');
    const loadingIndicator = document.getElementById('loading');
    const sortOrder = '{{ $sort ?? 'terbaru' }}';

    function loadMoreData() {
        if (isLoading || page >= lastPage) {
            if (page >= lastPage) {
                if(loadingIndicator) loadingIndicator.style.display = 'none';
            }
            return;
        }

        isLoading = true;
        page++;
        if(loadingIndicator) loadingIndicator.style.display = 'block';

        fetch(`{{ url()->current() }}?sort=${sortOrder}&page=${page}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(data => {
            if(loadingIndicator) loadingIndicator.style.display = 'none';
            if (data.html && data.html.trim().length > 0) {
                donorList.insertAdjacentHTML('beforeend', data.html);
                lastPage = data.last_page;
            } else {
                lastPage = page;
            }
            isLoading = false;
        })
        .catch(error => {
            console.error('Error loading more data:', error);
            if(loadingIndicator) loadingIndicator.style.display = 'none';
            isLoading = false;
        });
    }

    window.addEventListener('scroll', () => {
        const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
        if (scrollTop + clientHeight >= scrollHeight - 150) {
            loadMoreData();
        }
    });
});
</script>
@endsection