@csrf
<div class="row">
    <!-- Image Input and Preview -->
    <div class="col-12">
        <div class="form-group mb-3">
            <label for="image" class="form-label">Gambar</label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" onchange="previewImage()">
            <small class="form-text text-muted">{{ isset($banner) ? 'Kosongkan jika tidak ingin mengubah gambar.' : '' }} Ukuran maks 2MB.</small>
            @error('image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3 text-center">
            <img id="image-preview" class="img-fluid rounded border shadow-sm"
                 src="{{ isset($banner) && $banner->image ? asset($banner->image) : '' }}"
                 style="max-height: 350px; {{ isset($banner) && $banner->image ? '' : 'display: none;' }}">
        </div>
    </div>

    <!-- Other Fields -->
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="title" class="form-label">Judul {!! printRequired() !!}</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $banner->title ?? '') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="url" class="form-label">URL</label>
            <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $banner->url ?? '') }}" placeholder="https://bantubersama.com/sedekahmasjid">
            @error('url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="alt" class="form-label">Alt Gambar (opsional)</label>
            <input type="alt" class="form-control @error('alt') is-invalid @enderror" id="alt" name="alt" value="{{ old('alt', $banner->alt ?? '') }}" placeholder="sedekah-masjid">
            <small class="form-text text-muted">Form ini bersifat opsional. Gunakan "-" untuk memisahkan kata.</small>
            @error('alt')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
    <div class="form-group mb-3">
        <label for="expire_date" class="form-label fw-semibold">Tanggal Kedaluwarsa</label>
        <div class="input-group input-group">
            <span class="input-group-text d-flex align-items-center">
                <input class="form-check-input me-2" type="checkbox" 
                    value="1" name="is_forever" id="is_forever"
                    {{ old('is_forever', $banner->is_forever ?? '') ? 'checked' : '' }}>
                <label for="is_forever" class="mb-0">Selamanya</label>
            </span>
            <input type="date" 
                class="form-control form-control @error('expire_date') is-invalid @enderror"
                id="expire_date" name="expire_date"
                value="{{ old('expire_date', isset($banner->expire_date) ? \Carbon\Carbon::parse($banner->expire_date)->format('Y-m-d') : '') }}">
        </div>
        @error('expire_date')
            <div class="text-danger small mt-1"><i class="ri-error-warning-line"></i> {{ $message }}</div>
        @enderror
    </div>
</div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="is_publish" class="form-label">Status {!! printRequired() !!}</label>
            <select class="form-select @error('is_publish') is-invalid @enderror" id="is_publish" name="is_publish" required>
                <option value="1" {{ old('is_publish', $banner->is_publish ?? '') == 1 ? 'selected' : '' }}>Publikasi</option>
                <option value="0" {{ old('is_publish', $banner->is_publish ?? '') == 0 ? 'selected' : '' }}>Draft</option>
            </select>
            @error('is_publish')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="type" class="form-label">Tipe {!! printRequired() !!}</label>
            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                <option value="banner" {{ old('type', $banner->type ?? 'banner') == 'banner' ? 'selected' : '' }}>Banner</option>
                <option value="popup" {{ old('type', $banner->type ?? '') == 'popup' ? 'selected' : '' }}>Popup</option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $banner->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="divider"></div>

<div class="text-end">
    <a href="{{ route('adm.banner.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">{{ isset($banner) ? 'Perbarui' : 'Simpan' }}</button>
</div>


@section('js_plugins')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('js_inline')
<script>
function previewImage() {
    const image = document.querySelector('#image');
    const imgPreview = document.querySelector('#image-preview');

    imgPreview.style.display = 'block';

    const oFReader = new FileReader();
    oFReader.readAsDataURL(image.files[0]);

    oFReader.onload = function(oFREvent) {
        imgPreview.src = oFREvent.target.result;
    }
}

$(document).ready(function() {
    function toggleExpireDate() {
        if ($('#is_forever').is(':checked')) {
            $('#expire_date').prop('disabled', true).val('');
        } else {
            $('#expire_date').prop('disabled', false);
        }
    }

    toggleExpireDate();

    $('#is_forever').on('change', function() {
        toggleExpireDate();
    });
});
</script>

<script>
        @if (session('message'))
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: '{{ session('message')['type'] }}',
                title: '{{ session('message')['text'] }}',
                showConfirmButton: false,
                timer: 15000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded shadow-sm px-3 py-2 border-0 d-flex flex-row align-middle-center justify-content-center'
                },
                background: '{{ session('message')['type'] === 'success' ? '#d1fae5' : '#fee2e2' }}',
                color: '{{ session('message')['type'] === 'success' ? '#065f46' : '#b91c1c' }}',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
@endsection