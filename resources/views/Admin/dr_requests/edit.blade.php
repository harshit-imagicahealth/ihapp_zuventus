@extends('Admin.layouts.main')

@push('title')
    Edit Request
@endpush

@section('main')
    <div class="main_content_box mt-5">

        <form id="userForm" action="{{ route('admin.request.edit.update', ['id' => encrypt($data->id)]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <input type="hidden" id="existingPoster" value="{{ $data->poster ? 1 : 0 }}">
            <input type="hidden" id="existingVideo" value="{{ $data->video ? 1 : 0 }}">

            <div class="card-body">

                <div class="mb-4">
                    <label class="form-label">Upload Poster (Image only)</label>
                    <div class="drop-zone" id="posterDrop">
                        <span>Drag & drop image here or click</span>
                        <input type="file" id="poster" name="poster" accept="image/*" hidden>
                    </div>
                    <img id="posterPreview" class="preview-img {{ $data->poster ? '' : 'd-none' }}"
                        src="{{ $data->poster ? Storage::disk('spaces')->url($data->poster) : '' }}">
                </div>

                <div class="mb-4">
                    <label class="form-label">Upload Video (Video only)</label>
                    <div class="drop-zone" id="videoDrop">
                        <span>Drag & drop video here or click</span>
                        <input type="file" id="video" name="video" accept="video/*" hidden>
                    </div>
                    <video id="videoPreview" class="preview-video {{ $data->video ? '' : 'd-none' }}" controls>
                        @if ($data->video)
                            <source src="{{ Storage::disk('spaces')->url($data->video) }}">
                        @endif
                    </video>
                </div>

                <div class="mb-4">
                    <label class="custom_label mb-3">Select Status</label>
                    <div class="radio-group square-radio">
                        <label class="square-radio-option">
                            <input type="radio" name="status" value="1" {{ $data->status == 1 ? 'checked' : '' }}>
                            <span class="square-box"><i class="fa-solid fa-check"></i></span>
                            <span class="square-label">Accept</span>
                        </label>
                        <label class="square-radio-option">
                            <input type="radio" name="status" value="2" {{ $data->status == 2 ? 'checked' : '' }}>
                            <span class="square-box"><i class="fa-solid fa-check"></i></span>
                            <span class="square-label">Reject</span>
                        </label>
                    </div>
                </div>

            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn_custom px-4">Submit</button>
            </div>

        </form>
    </div>
@endsection

@push('script')
    <script>
        function initDropZone(dropZoneId, inputId, previewId, type) {
            const dropZone = document.getElementById(dropZoneId);
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            dropZone.addEventListener('click', () => input.click());

            dropZone.addEventListener('dragover', e => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('dragover');
            });

            dropZone.addEventListener('drop', e => {
                e.preventDefault();
                dropZone.classList.remove('dragover');

                const file = e.dataTransfer.files[0];
                if (!file) return;

                if (type === 'image' && !file.type.startsWith('image/')) return;

                if (type === 'video') {
                    if (!file.type.startsWith('video/')) return;

                    if (file.size > 5 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'Video size must be 5MB or less'
                        });
                        return;
                    }
                }

                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;

                showPreview(file, preview, type);
            });

            input.addEventListener('change', () => {
                if (input.files[0]) {
                    const file = input.files[0];

                    if (type === 'video' && file.size > 5 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'Video size must be 5MB or less'
                        });
                        input.value = '';
                        return;
                    }

                    showPreview(file, preview, type);
                }
            });
        }

        function showPreview(file, preview, type) {
            const url = URL.createObjectURL(file);
            preview.classList.remove('d-none');
            preview.src = url;
            if (type === 'video') preview.load();
        }

        initDropZone('posterDrop', 'poster', 'posterPreview', 'image');
        initDropZone('videoDrop', 'video', 'videoPreview', 'video');

        document.getElementById('userForm').addEventListener('submit', function(e) {

            const status = document.querySelector('input[name="status"]:checked');

            const posterInput = document.getElementById('poster');
            const videoInput = document.getElementById('video');

            const newPoster = posterInput.files.length;
            const newVideo = videoInput.files.length;

            const existingPoster = document.getElementById('existingPoster').value == 1;
            const existingVideo = document.getElementById('existingVideo').value == 1;

            if (!status) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select status'
                });
                return;
            }

            if (status.value === '1') {

                if ((!newPoster && !existingPoster) || (!newVideo && !existingVideo)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Poster and Video are required when status is Accept'
                    });
                    return;
                }

                if (newVideo && videoInput.files[0].size > 5 * 1024 * 1024) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Video size must be 5MB or less'
                    });
                    return;
                }
            }
        });
    </script>
@endpush
