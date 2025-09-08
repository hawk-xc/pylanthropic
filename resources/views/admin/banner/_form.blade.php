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
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="duration" class="form-label">Durasi (hari)</label>
            <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $banner->duration ?? 7) }}" required>
            @error('duration')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="is_publish" class="form-label">Status {!! printRequired() !!}</label>
            <select class="form-control @error('is_publish') is-invalid @enderror" id="is_publish" name="is_publish" required>
                <option value="1" {{ old('is_publish', $banner->is_publish ?? '') == 1 ? 'selected' : '' }}>Publikasi</option>
                <option value="0" {{ old('is_publish', $banner->is_publish ?? '') == 0 ? 'selected' : '' }}>Draft</option>
            </select>
            @error('is_publish')
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