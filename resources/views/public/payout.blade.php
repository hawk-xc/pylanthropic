@extends('layouts.public', [
    'second_title' => 'Penyaluran Dana',
])

@section('page_title', 'Penyaluran Dana')

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
            <div class="row pt-3 gx-1">
                <div class="col-6">
                    <div class="card w-full">
                        <div class="card-body">
                            <h6 class="card-title">Dana Dicairkan</h6>
                            <p class="card-text">Rp {{ number_format($total_disbursed, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card w-100">
                        <div class="card-body">
                            <h6 class="card-title">Total Penyaluran</h6>
                            <p class="card-text">{{ $count_payout }} Kali</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-3 pt-4">
                <div class="col-12">
                    <h3 class="fw-semibold">Riwayat Penyaluran Dana</h3>
                </div>

                @forelse($payouts as $payout)
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('public/images/fundraiser/' . $program->programOrganization->logo) }}" alt="..." class="avatar-sm rounded-circle">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">{{ $program->programOrganization->name }}</h3>
                                <small>{{ \Carbon\Carbon::parse($payout->paid_at)->locale('id')->translatedFormat('d F Y') }}</small>
                            </div>
                        </div>
                        <h2 class="mt-4" style="font-size: 1.2rem;">Pencairan Dana Rp {{ number_format($payout->nominal_approved, 0, ',', '.') }}
                            <span class="badge @switch($payout->status)
                                @case('request') bg-warning text-dark @break
                                @case('process') bg-info text-dark @break
                                @case('paid') bg-success @break
                                @case('cancel') bg-secondary @break
                                @case('reject') bg-danger @break
                                @default bg-light text-dark
                            @endswitch">{{ ucfirst($payout->status) }}</span>
                        </h2>
                        <p class="mt-2">{!! $payout->desc_request !!}</p>
                        <hr>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>Belum ada data penyaluran.</p>
                    </div>
                @endforelse
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
                    d="M17.5 10A3.5 3.5 0 1 0 14 6.5c0 .43-.203.86-.595 1.037L10.034 9.07c-.427.194-.924.052-1.283-.25a3.5 3.5 0 1 0-.2 5.517c.38-.275.885-.381 1.297-.156l3.585 1.955c.412.225.597.707.572 1.176a3.5 3.5 0 1 0 1.445-2.649c-.38.275-.886.381-1.298.156l-3.585-1.955c-.412-.225-.597-.707-.572-1.176.003-.062.005-.125.005-.188 0-.43.203-.86.595-1.037l3.371-1.533c.428-.194.924-.052 1.283.25.609.512 1.394.82 2.251.82Z"
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
@endsection
