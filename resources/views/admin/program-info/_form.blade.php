@csrf
<div class="form-group">
    <label for="program_id" class="form-label fw-semibold required">Program</label>
    <select name="program_id" id="program-select2" class="form-control" required>
        @isset($programInfo)
            <option value="{{ $programInfo->program_id }}" selected>{{ $programInfo->program->title }}</option>
        @endisset
    </select>
</div>
<div class="form-group">
    <label for="title" class="form-label fw-semibold required">Judul</label>
    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $programInfo->title ?? '') }}" placeholder="Contoh: Penyaluran Bantuan Tahap 1" required>
</div>
<div class="form-group">
    <label for="content" class="form-label fw-semibold required">Konten</label>
    <textarea name="content" class="form-control" rows="5" placeholder="Jelaskan kabar terbaru mengenai program ini." required>{{ old('content', $programInfo->content ?? '') }}</textarea>
</div>
<div class="form-group">
    <label for="is_publish" class="form-label fw-semibold required">Status</label>
    <select name="is_publish" id="is_publish" class="form-control" required>
        <option value="1" {{ isset($programInfo) && $programInfo->is_publish == 1 ? 'selected' : '' }}>Publish</option>
        <option value="0" {{ isset($programInfo) && $programInfo->is_publish == 0 ? 'selected' : '' }}>Draft</option>
    </select>
</div>
<div class="col-12 mt-3 text-end">
    <input type="reset" class="btn btn-danger" value="Reset">
    <input type="submit" class="btn btn-info" value="Submit">
</div>