@extends('layouts.public', [
    'second_title' => '',
])


@section('css_plugins')
    <!-- swiper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/css/vendors/swiper-bundle.min.css" />

    <!-- Meta Pixel Code -->
    {{-- disable meta pixel code --}}
    {{-- <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq.disablePushState = true;
        fbq('init', '1278491429470122');
        fbq('track', 'PageView');
        window.loadedPixel = []
    </script> --}}
    <!-- End Meta Pixel Code -->
@endsection


@section('css_inline')
    <style type="text/css">
        input:focus::-webkit-input-placeholder {
            -webkit-transform: translateY(-125%);
            /*   font-size: 75%; */
            opacity: 0.05
        }

        input.imitatefocus::-webkit-input-placeholder {
            -webkit-transform: translateY(-125%);
            opacity: 0.05
        }
    </style>
@endsection


@section('content')
    <!-- header start -->
    <header class="section-t-space pt-0">
        <!-- <div class="custom-container"> -->
        <div class="header-panel bg-me header-title">
            <!-- <div class="header-title"> -->
            <a href="{{ url('/') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
            <h2 class="fs-16">Pusat Bantuan</h2>
            <!-- </div> -->
        </div>
        <!-- </div> -->
    </header>
    <!-- header end -->

    <!-- Help Center Content Section -->
    <section class="help-content py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <!-- Contact Box -->
                    <div class="card shadow-sm mb-5">
                        <div class="card-body text-center p-4">
                            <h2 class="card-title mb-4">Butuh Bantuan Lebih Lanjut?</h2>
                            <p class="text-muted mb-4">Tim kami siap membantu Anda melalui kontak berikut:</p>

                            <div class="d-flex flex-column gap-3">
                                <!-- WhatsApp -->
                                <div class="d-flex align-items-center p-3 border rounded-3 bg-light w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" class="me-3"
                                        viewBox="0 0 32 32" fill="none">
                                        <path fill="#25D366"
                                            d="M16 .002C7.164.002 0 7.165 0 16c0 2.82.738 5.517 2.14 7.909L.05 31.313l7.588-2.036A15.923 15.923 0 0 0 16 32c8.836 0 16-7.164 16-16C32 7.165 24.836.002 16 .002z" />
                                        <path fill="#FFF"
                                            d="M24.095 20.693c-.367-.184-2.17-1.072-2.506-1.195-.336-.123-.582-.184-.828.184-.245.367-.949 1.194-1.164 1.44-.214.245-.429.276-.796.092-.367-.184-1.548-.571-2.948-1.82-1.09-.973-1.828-2.176-2.043-2.543-.214-.367-.023-.566.161-.75.165-.164.367-.428.551-.643.184-.215.245-.368.367-.613.122-.245.061-.46-.03-.643-.092-.184-.827-2.005-1.134-2.75-.298-.716-.602-.616-.827-.627l-.704-.013c-.245 0-.643.092-.979.46-.336.367-1.286 1.257-1.286 3.064 0 1.807 1.316 3.555 1.5 3.8.184.245 2.593 3.96 6.288 5.552.88.38 1.567.606 2.102.777.882.28 1.684.24 2.32.146.707-.105 2.17-.886 2.479-1.744.306-.859.306-1.594.215-1.744-.092-.15-.337-.245-.704-.429z" />
                                    </svg>

                                    <div class="text-start">
                                        <h6 class="mb-1">WhatsApp</h6>
                                        <a href="https://wa.me/628155555849" class="text-decoration-none"
                                            target="_blank">+6281-55555-849</a>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="d-flex align-items-center p-3 border rounded-3 bg-light w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#0d6efd"
                                        class="me-3" viewBox="0 0 16 16">
                                        <path
                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v.217l-8 4.8-8-4.8V4zm0 1.383v6.634l5.803-3.482L0 5.383zm6.761 3.975L0 13.01V14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.99l-6.761-3.652L8 10.972l-1.239-.614zM16 5.383l-5.803 3.152L16 12.017V5.383z" />
                                    </svg>
                                    <div class="text-start">
                                        <h6 class="mb-1">Email</h6>
                                        <a href="mailto:help@bantubersama.com" class="text-decoration-none"
                                            target="_blank">bantuberamalbersama@gmail.com</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- FAQ Section -->
                    <h2 class="mb-4">Pertanyaan yang Sering Diajukan</h2>

                    <div class="accordion" id="faqAccordion">

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading1">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false"
                                    aria-controls="collapse1">
                                    Apa itu platform penggalangan dana?
                                </button>
                            </h3>
                            <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Platform penggalangan dana adalah situs atau aplikasi yang memungkinkan individu atau
                                    organisasi untuk mengumpulkan dana untuk tujuan tertentu, seperti amal, proyek, atau
                                    kebutuhan sosial.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading2">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false"
                                    aria-controls="collapse2">
                                    Bagaimana cara berdonasi di Bantu Bersama?
                                </button>
                            </h3>
                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Anda dapat berdonasi dengan mengunjungi halaman kampanye, memilih jumlah yang ingin
                                    didonasikan, dan mengikuti langkah-langkah yang tertera untuk menyelesaikan transaksi.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading3">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false"
                                    aria-controls="collapse3">
                                    Apakah saya harus membuat akun untuk berdonasi?
                                </button>
                            </h3>
                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Tidak, Anda dapat berdonasi tanpa membuat akun. Namun, membuat akun akan mempermudah
                                    Anda untuk melacak donasi dan menerima pembaruan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading4">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false"
                                    aria-controls="collapse4">
                                    Apakah donasi saya akan dikenakan biaya tambahan?
                                </button>
                            </h3>
                            <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Tidak ada biaya tambahan untuk donasi Anda. Jumlah yang Anda sumbangkan akan diterima
                                    sepenuhnya oleh penerima manfaat.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading5">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false"
                                    aria-controls="collapse5">
                                    Dapatkah saya memilih program yang saya dukung?
                                </button>
                            </h3>
                            <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Ya, Anda dapat memilih proyek atau kampanye yang ingin Anda dukung sesuai dengan minat
                                    dan kepedulian Anda.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading6">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false"
                                    aria-controls="collapse6">
                                    Bagaimana saya tahu bahwa dana yang saya donasikan akan digunakan dengan benar?
                                </button>
                            </h3>
                            <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading6"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Kami berkomitmen untuk transparansi. Kami memberikan laporan dan pembaruan tentang
                                    penggunaan dana dan dampaknya kepada penerima manfaat.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading7">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false"
                                    aria-controls="collapse7">
                                    Berapa minimal jumlah donasi yang dapat saya berikan?
                                </button>
                            </h3>
                            <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="heading7"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Tidak ada jumlah minimum untuk berdonasi. Anda dapat memberikan sesuai dengan kemampuan
                                    dan niat baik Anda.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading8">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false"
                                    aria-controls="collapse8">
                                    Dapatkah saya memberikan donasi anonim?
                                </button>
                            </h3>
                            <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="heading8"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Ya, Anda dapat memilih untuk memberikan donasi secara anonim saat melakukan transaksi.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading9">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="false"
                                    aria-controls="collapse9">
                                    Bagaimana saya bisa mendapatkan pembaruan tentang program yang saya dukung?
                                </button>
                            </h3>
                            <div id="collapse9" class="accordion-collapse collapse" aria-labelledby="heading9"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Kami akan mengirimkan pembaruan melalui email mengenai perkembangan program dan dampak
                                    dari donasi Anda.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading10">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="false"
                                    aria-controls="collapse10">
                                    Apa yang harus saya lakukan jika saya tidak menerima konfirmasi setelah berdonasi?
                                </button>
                            </h3>
                            <div id="collapse10" class="accordion-collapse collapse" aria-labelledby="heading10"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Jika Anda tidak menerima konfirmasi, silakan periksa folder spam di email Anda atau
                                    hubungi kami di bagian kontak untuk bantuan lebih lanjut.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading11">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse11" aria-expanded="false"
                                    aria-controls="collapse11">
                                    Apakah ada batas waktu untuk mendukung program?
                                </button>
                            </h3>
                            <div id="collapse11" class="accordion-collapse collapse" aria-labelledby="heading11"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Setiap program memiliki batas waktu masing-masing. Anda dapat melihat informasi tersebut
                                    di halaman program.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading12">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse12" aria-expanded="false"
                                    aria-controls="collapse12">
                                    Apakah saya bisa mendukung lebih dari satu program sekaligus?
                                </button>
                            </h3>
                            <div id="collapse12" class="accordion-collapse collapse" aria-labelledby="heading12"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Tentu! Anda dapat mendukung sebanyak mungkin program yang Anda inginkan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading13">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse13" aria-expanded="false"
                                    aria-controls="collapse13">
                                    Bagaimana jika program yang saya dukung tidak mencapai target?
                                </button>
                            </h3>
                            <div id="collapse13" class="accordion-collapse collapse" aria-labelledby="heading13"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Jika program tidak mencapai target, dana yang terkumpul tetap akan digunakan sesuai
                                    dengan tujuan yang telah ditetapkan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading14">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse14" aria-expanded="false"
                                    aria-controls="collapse14">
                                    Apakah ada risiko yang terkait dengan donasi online?
                                </button>
                            </h3>
                            <div id="collapse14" class="accordion-collapse collapse" aria-labelledby="heading14"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Kami menggunakan teknologi enkripsi untuk melindungi informasi keuangan Anda, sehingga
                                    risiko sangat minim. Namun, tetap penting untuk berhati-hati saat bertransaksi online.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading15">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse15" aria-expanded="false"
                                    aria-controls="collapse15">
                                    Apakah saya bisa mendapatkan tanda terima untuk donasi saya?
                                </button>
                            </h3>
                            <div id="collapse15" class="accordion-collapse collapse" aria-labelledby="heading15"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Ya, setelah donasi Anda diproses, Anda akan menerima tanda terima melalui email yang
                                    dapat digunakan untuk keperluan administrasi atau pajak.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading16">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse16" aria-expanded="false"
                                    aria-controls="collapse16">
                                    Bagaimana cara menghubungi tim dukungan jika ada pertanyaan lebih lanjut?
                                </button>
                            </h3>
                            <div id="collapse16" class="accordion-collapse collapse" aria-labelledby="heading16"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Anda dapat menghubungi kami melalui formulir kontak di bagian atas pertanyaan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading17">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse17" aria-expanded="false"
                                    aria-controls="collapse17">
                                    Apa yang harus saya lakukan jika saya mengalami masalah saat melakukan donasi?
                                </button>
                            </h3>
                            <div id="collapse17" class="accordion-collapse collapse" aria-labelledby="heading17"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Jika Anda mengalami masalah, silakan hubungi tim dukungan kami. Kami siap membantu
                                    menyelesaikan masalah yang Anda hadapi.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading18">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse18" aria-expanded="false"
                                    aria-controls="collapse18">
                                    Dapatkah saya memilih penerima manfaat spesifik untuk donasi saya?
                                </button>
                            </h3>
                            <div id="collapse18" class="accordion-collapse collapse" aria-labelledby="heading18"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Donasi akan dialokasikan sesuai dengan produk yang Anda pilih.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading19">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse19" aria-expanded="false"
                                    aria-controls="collapse19">
                                    Apakah saya bisa memberikan donasi sebagai hadiah untuk orang lain?
                                </button>
                            </h3>
                            <div id="collapse19" class="accordion-collapse collapse" aria-labelledby="heading19"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Tentu! Anda dapat memberikan donasi sebagai hadiah, dan kami dapat mengirimkan
                                    konfirmasi donasi kepada penerima atas nama Anda.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading20">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse20" aria-expanded="false"
                                    aria-controls="collapse20">
                                    Bagaimana cara mengetahui bahwa program telah selesai?
                                </button>
                            </h3>
                            <div id="collapse20" class="accordion-collapse collapse" aria-labelledby="heading20"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Kami akan memberi tahu semua donatur melalui email dan memperbarui status program di
                                    situs kami ketika program telah selesai.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading21">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse21" aria-expanded="false"
                                    aria-controls="collapse21">
                                    Apakah donasi saya akan digunakan untuk kegiatan lain selain yang dijelaskan dalam
                                    progeam?
                                </button>
                            </h3>
                            <div id="collapse21" class="accordion-collapse collapse" aria-labelledby="heading21"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Tidak, dana Anda hanya akan digunakan untuk tujuan yang telah dijelaskan dalam program
                                    yang Anda dukung.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading22">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse22" aria-expanded="false"
                                    aria-controls="collapse22">
                                    Apakah saya bisa melakukan donasi secara internasional?
                                </button>
                            </h3>
                            <div id="collapse22" class="accordion-collapse collapse" aria-labelledby="heading22"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Ya, kami menerima donasi dari donatur internasional. Pastikan untuk memeriksa metode
                                    pembayaran yang tersedia.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading23">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse23" aria-expanded="false"
                                    aria-controls="collapse23">
                                    Bagaimana jika saya tidak menemukan program yang saya cari?
                                </button>
                            </h3>
                            <div id="collapse23" class="accordion-collapse collapse" aria-labelledby="heading23"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Jika Anda tidak menemukan program yang Anda cari, silakan hubungi kami di bagian kontak
                                    dan kami akan membantu Anda mencarikan informasi yang dibutuhkan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading24">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse24" aria-expanded="false"
                                    aria-controls="collapse24">
                                    Dapatkah saya mengubah informasi donasi saya setelah dikirim?
                                </button>
                            </h3>
                            <div id="collapse24" class="accordion-collapse collapse" aria-labelledby="heading24"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Setelah donasi dikirim, informasi tidak dapat diubah. Namun, jika ada kesalahan, segera
                                    hubungi kami untuk bantuan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading25">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse25" aria-expanded="false"
                                    aria-controls="collapse25">
                                    Bagaimana cara menarik minat lebih banyak donatur untuk program ini?
                                </button>
                            </h3>
                            <div id="collapse25" class="accordion-collapse collapse" aria-labelledby="heading25"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Anda dapat membantu menarik minat lebih banyak donatur dengan membagikan cerita program
                                    di media sosial dan meminta teman-teman untuk ikut berdonasi.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading26">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse26" aria-expanded="false"
                                    aria-controls="collapse26">
                                    Apakah ada batasan usia untuk menjadi donatur?
                                </button>
                            </h3>
                            <div id="collapse26" class="accordion-collapse collapse" aria-labelledby="heading26"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Tidak ada batasan usia untuk menjadi donatur. Namun, kami menyarankan agar orang tua
                                    mendampingi anak-anak yang ingin berdonasi.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading27">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse27" aria-expanded="false"
                                    aria-controls="collapse27">
                                    Apakah ada biaya administrasi untuk penggalangan dana?
                                </button>
                            </h3>
                            <div id="collapse27" class="accordion-collapse collapse" aria-labelledby="heading27"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Kami tidak membebankan biaya administrasi kepada donatur. Semua dana yang terkumpul akan
                                    dialokasikan sesuai tujuan program.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading28">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse28" aria-expanded="false"
                                    aria-controls="collapse28">
                                    Bagaimana cara melaporkan program yang mencurigakan?
                                </button>
                            </h3>
                            <div id="collapse28" class="accordion-collapse collapse" aria-labelledby="heading28"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Jika Anda mencurigai adanya program yang tidak valid, silakan laporkan kepada kami
                                    melalui formulir kontak di atas.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading29">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse29" aria-expanded="false"
                                    aria-controls="collapse29">
                                    Apakah saya bisa memilih metode pembayaran untuk donasi?
                                </button>
                            </h3>
                            <div id="collapse29" class="accordion-collapse collapse" aria-labelledby="heading29"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Ya, kami menyediakan beberapa metode pembayaran, termasuk kartu kredit, transfer bank,
                                    dan dompet digital.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading30">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse30" aria-expanded="false"
                                    aria-controls="collapse30">
                                    Apakah saya bisa melakukan donasi tanpa menggunakan internet?
                                </button>
                            </h3>
                            <div id="collapse30" class="accordion-collapse collapse" aria-labelledby="heading30"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Saat ini, kami hanya menerima donasi melalui platform online. Namun, Anda dapat meminta
                                    bantuan dari teman atau keluarga untuk membantu Anda berdonasi secara online.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading31">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse31" aria-expanded="false"
                                    aria-controls="collapse31">
                                    Apakah saya dapat menggunakan nama samaran saat berdonasi?
                                </button>
                            </h3>
                            <div id="collapse31" class="accordion-collapse collapse" aria-labelledby="heading31"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Ya, Anda dapat menggunakan nama samaran jika Anda memilih untuk memberikan donasi secara
                                    anonim.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading32">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse32" aria-expanded="false"
                                    aria-controls="collapse32">
                                    Dapatkah saya berdonasi untuk lebih dari satu program sekaligus?
                                </button>
                            </h3>
                            <div id="collapse32" class="accordion-collapse collapse" aria-labelledby="heading32"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Tentu! Anda dapat mendonasikan untuk beberapa program dalam satu transaksi.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="heading33">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse33" aria-expanded="false"
                                    aria-controls="collapse33">
                                    Apakah ada cara untuk mengukur dampak dari donasi saya?
                                </button>
                            </h3>
                            <div id="collapse33" class="accordion-collapse collapse" aria-labelledby="heading33"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Kami akan memberikan laporan dan pembaruan mengenai dampak donasi Anda melalui email dan
                                    di halaman program.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer section start -->
    <section class="empty-section section-t-space section-b-space pb-0">
        <div class="custom-container footer pb-3 pt-3">
            <div class="fw-medium text-grey pt-2 fs-14">
                <a class="text-grey" href="{{ route('aboutus') }}">Tentang Kami</a> |
                <a class="text-grey" href="{{ route('termsandcondition') }}">Syarat & Ketentuan</a> |
                <a class="text-grey" href="{{ route('questionscenter') }}">Pusat Bantuan</a>
            </div>
            <div class="mt-3 text-grey fs-14">
                Temukan kami di <br>
                <div class="socmed mb-3 mt-1">
                    <a rel="noreferrer" href="https://www.facebook.com/profile.php?id=100091563649667" target="_blank"
                        class="me-2 socmed-item rounded-circle">
                        <svg class="mx-auto" width="12" height="12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9 .002L7.443 0C5.695 0 4.565 1.16 4.565 2.953v1.362H3.001a.245.245 0 00-.245.245v1.973c0 .135.11.244.245.244h1.564v4.978c0 .135.11.245.245.245h2.041c.136 0 .245-.11.245-.245V6.777h1.83c.135 0 .244-.11.244-.244V4.56a.245.245 0 00-.244-.245h-1.83V3.16c0-.555.132-.837.855-.837h1.048c.135 0 .245-.11.245-.245V.247A.245.245 0 009 .002z"
                                fill="currentColor"></path>
                        </svg>
                        <span class="screen-reader-text">Facebook</span>
                    </a>
                    <a rel="noreferrer" href="https://www.instagram.com/bantubersamacom/" target="_blank"
                        class="me-2 socmed-item rounded-circle">
                        <svg class="mx-auto" width="12" height="12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.688 0H3.311A3.315 3.315 0 000 3.312v5.376A3.315 3.315 0 003.311 12h5.377A3.315 3.315 0 0012 8.688V3.312A3.315 3.315 0 008.688 0zm2.247 8.688a2.25 2.25 0 01-2.247 2.247H3.311a2.25 2.25 0 01-2.246-2.247V3.312A2.25 2.25 0 013.31 1.065h5.377a2.25 2.25 0 012.247 2.247v5.376z"
                                fill="currentColor"></path>
                            <path
                                d="M6 2.906a3.096 3.096 0 00-3.092 3.092A3.095 3.095 0 006 9.09a3.095 3.095 0 003.092-3.092A3.096 3.096 0 006 2.906zm0 5.12a2.03 2.03 0 01-2.028-2.028A2.03 2.03 0 016 3.971a2.03 2.03 0 012.027 2.027A2.03 2.03 0 016 8.025zM9.222 2.004a.784.784 0 00-.781.78.787.787 0 00.78.78.788.788 0 00.553-.227.784.784 0 00-.552-1.333z"
                                fill="currentColor"></path>
                        </svg>
                        <span class="screen-reader-text">Instagram</span>
                    </a>
                    <a rel="noreferrer" href="https://twitter.com/bantubersamacom" target="_blank"
                        class="me-2 socmed-item rounded-circle">
                        <svg class="mx-auto" width="12" height="12" fill="none"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                            <g>
                                <path
                                    d="M14.356 4.742A6.547 6.547 0 0016 3.039a6.85 6.85 0 01-1.89.518 3.263 3.263 0 001.443-1.813 6.563 6.563 0 01-2.08.794A3.28 3.28 0 007.8 4.781c0 .26.022.51.076.748a9.287 9.287 0 01-6.761-3.432 3.308 3.308 0 00-.45 1.658c0 1.136.585 2.143 1.458 2.726A3.242 3.242 0 01.64 6.077v.036a3.296 3.296 0 002.628 3.224 3.262 3.262 0 01-.86.108c-.21 0-.422-.012-.62-.056a3.312 3.312 0 003.064 2.285 6.593 6.593 0 01-4.067 1.399c-.269 0-.527-.012-.785-.045A9.237 9.237 0 005.032 14.5c5.789 0 9.561-4.83 9.324-9.758z"
                                    fill="currentColor"></path>
                            </g>
                        </svg>
                        <span class="screen-reader-text">Twitter</span>
                    </a>
                    <a rel="noreferrer" href="https://www.youtube.com/@Bantubersama-de2vi" target="_blank"
                        class="socmed-item rounded-circle">
                        <svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                            viewBox="0 0 24 24">
                            <path
                                d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"
                                fill="currentColor"></path>
                        </svg>
                        <span class="screen-reader-text">Youtube</span>
                    </a>
                </div>
            </div>
            <div class="fs-14 fw-normal text-grey mt-3">
                Copyright © 2023 Yayasan Bantu Beramal Bersama
            </div>
        </div>
    </section>
    <!-- footer section end -->

    <style>
        .hover-bg-lightblue {
            width: 50px;
            /* Set the width */
            height: 50px;
            /* Set the height to be the same as width */
        }

        .hover-bg-lightblue:hover {
            background-color: #d0e3ff !important;
            /* Warna biru muda */
            transition: background-color 0.2s ease-in-out;
        }

        .text-slate-500 {
            color: #4d5756;
        }
    </style>
@endsection


@section('js_plugins')
    <!-- JQuery -->
    <script src="{{ asset('public/js/jquery-3.6.4.min.js') }}"></script>
    <!-- bootstrap js -->
    <script src="{{ asset('public') }}/js/bootstrap.bundle.min.js"></script>

    <!-- swiper js -->
    <script src="{{ asset('public') }}/js/swiper-bundle.min.js"></script>
    <script src="{{ asset('public') }}/js/custom-swiper.js"></script>
    <!-- lazy load -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-lazyload@1.9.7/jquery.lazyload.min.js"></script>
@endsection
