@extends('layouts.public', [
    'second_title' => 'Penyaluran Dana',
])

@section('content')
    <header class="section-t-space pt-0">
        <div class="header-panel bg-me header-title">
            <a href="{{ url('/' . $program->slug) }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
            <h2 class="fs-16">Informasi Penyaluran</h2>
        </div>
    </header>
    <section class="section-t-space pt-3 mt-1 pb-4">
        <div class="custom-container">
            <div class="row">
                <div class="col-12">
                    <img class="img-fluid  img rounded w-100"
                        src="{{ asset('public/images/program') . '/' . $program->thumbnail }}"
                        alt="{{ ucwords($program->title) }}"
                        onerror="this.src='{{ asset('not-found.png') }}';" />
                    <h5 class="mt-3">{{ ucwords($program->title) }}</h5>
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Dana Dicairkan</h6>
                            <p class="card-text">Rp {{ number_format($total_disbursed, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Total Penyaluran</h6>
                            <p class="card-text">{{ $total_payouts }} Kali</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-3 pt-3">
                <div class="col-12">
                    <h3 class="fw-semibold">Riwayat Penyaluran Dana</h3>
                </div>

                @forelse($payouts as $payout)
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('public/images/organization/' . $program->programOrganization->logo) }}" alt="..." class="avatar-sm rounded-circle">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $program->programOrganization->name }}</h6>
                                <small>{{ \Carbon\Carbon::parse($payout->paid_at)->format('d F Y') }}</small>
                            </div>
                        </div>
                        <p class="mt-2">{{ $payout->desc_request }}</p>
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