    @extends('layouts.admin', [
        'second_title' => 'Logs',
        'header_title' => 'Logs',
        'sidebar_menu' => 'logs',
        'sidebar_submenu' => 'logs',
    ])

    @section('content')
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 pb-0">
                                <li class="breadcrumb-item"><a href="{{ route('adm.index') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Logs</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="divider"></div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-select" id="logFileSelect">
                                <option value="">Select a log file...</option>
                                @foreach ($logFiles as $file)
                                    <option value="{{ $file }}" {{ $file === $defaultLog ? 'selected' : '' }}>
                                        {{ $file }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary" type="button" id="viewLogBtn">
                                <i class="fa fa-eye"></i> View
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Log Content</h5>
                                <small id="fileInfo" class="text-muted"></small>
                            </div>
                            <div class="card-body p-0">
                                <div id="logAlert" class="alert alert-danger m-3 d-none"></div>
                                <div id="loadingIndicator" class="text-center py-4 d-none">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="mt-2 mb-0">Loading log file...</p>
                                </div>
                                <pre id="logContent"
                                    style="display: none; height: 60vh; overflow-y: auto; margin: 0; border-radius: 0; padding: 15px; background: #f8f9fa; white-space: pre-wrap; font-family: 'Courier New', monospace;"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('js_plugins')
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    @endsection


    @section('js_inline')
        <script>
            $(document).ready(function() {
                const $logSelect = $('#logFileSelect');
                const $viewBtn = $('#viewLogBtn');
                const $logContent = $('#logContent');
                const $loading = $('#loadingIndicator');
                const $alert = $('#logAlert');
                const $fileInfo = $('#fileInfo');

                // Fungsi untuk memuat log
                function loadLogFile(filename) {
                    if (!filename) {
                        showAlert('Please select a log file first');
                        return;
                    }

                    resetUI();
                    $loading.removeClass('d-none');
                    $fileInfo.text('Loading: ' + filename);

                    // Gunakan fetch API untuk response streaming
                    fetch('/adm/logs/' + encodeURIComponent(filename))
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText);
                            }

                            console.log(response);
                            return response.text();
                        })
                        .then(data => {
                            $logContent.text(data).show();
                            $fileInfo.text('Showing: ' + filename + ' | ' + formatSize(data.length));
                        })
                        .catch(error => {
                            showAlert('Error loading log: ' + error.message);
                            $fileInfo.text('Error loading: ' + filename);
                        })
                        .finally(() => {
                            $loading.addClass('d-none');
                        });
                }

                // Event handler untuk tombol view
                $viewBtn.on('click', function() {
                    console.log($logSelect.val())
                    loadLogFile($logSelect.val());
                });

                // Fungsi bantuan
                function resetUI() {
                    $alert.addClass('d-none');
                    $logContent.hide();
                }

                function showAlert(message) {
                    $alert.removeClass('d-none').text(message);
                }

                function formatSize(bytes) {
                    if (typeof bytes !== 'number') bytes = 0;
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }

                // Auto load default file
                @if (!empty($defaultLog))
                    loadLogFile('{{ $defaultLog }}');
                @endif
            });
        </script>
    @endsection
