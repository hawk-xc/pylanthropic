@forelse($donors as $donor)
    <div class="col-12">
        <div class="mb-3">
            <div class="d-flex">
                <div class="me-3 rounded-full bg-coal relative" style="width: 50px; height: 50px;">
                    <img alt="Orang Baik"
                        src="{{ asset('public/images/icons/user-anonim.png') }}"
                        width="50" height="50" decoding="async" data-nimg="1"
                        class="w-full h-full rounded-full object-cover object-center"
                        loading="lazy" style="color: transparent;" />
                </div>
                <div class="w-100">
                    <div class="content-donatur">
                        <div class="">
                            <div class="fs-14 fw-semibold">
                                @if($donor->is_show_name == 1)
                                    {{ $donor->name }}
                                @else
                                    Orang Baik
                                @endif
                            </div>
                            <div class="fs-13">{{ $donor->date_string }}</div>
                        </div>
                        <div class="fs-14 fw-semibold">Rp
                            {{ str_replace(',', '.', number_format($donor->nominal_final)) }}</div>
                    </div>
                    @if($donor->message)
                    <div class="fs-14 lh-18 text-grey-dark mt-2">{{ strip_tags($donor->message) }}</div>
                    @endif
                </div>
            </div>
        </div>
        <hr class="mt-0 mb-3 line-donatur">
    </div>
@empty
    @if($donors->currentPage() == 1)
    <div class="col-12 text-center fs-14 lh-20 text-muted">
        <p>Jadilah orang pertama yang berdonasi di program ini.</p>
    </div>
    @endif
@endforelse