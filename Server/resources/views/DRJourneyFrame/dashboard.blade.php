@extends('DRJourneyFrame.layouts.main')
@section('main')
    <link rel="stylesheet" href="{{ asset('public/css/drstyle.css') }}">
    <h3 class="fw-medium title_section mt-5 text-center">Doctor Frame Enrollment</h3>

    <div class="container-wrapper">
        <div class="main_content_box mt-5">
            @if (session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            @if (Auth::user()->employee_pos == 0)
                <div class="d-flex justify-content-center">
                    <a class="btn btn_custom w-25 fw-bold ms-2" href="{{ route('dr.journey.create') }}">Start</a>
                </div>

                <div class="mt-4 d-flex justify-content-center align-items-center gap-4">
                    <p class="text-muted"><span><strong class="form-label text-primary">Total Requests:</strong></span>
                        {{ $requestCounts->total_requests ?? 0 }}/25</p>
                    <p class="text-muted"><span><strong class="form-label text-primary">Physician:</strong></span>
                        {{ $requestCounts->physicians_count ?? 0 }}/13</p>
                    <p class="text-muted"><span><strong class="form-label text-primary">Gyn:</strong></span>
                        {{ $requestCounts->gyn_count ?? 0 }}/12</p>
                </div>
            @endif

            <div class="table_main">
                <table class="table-responsive table-hover mt-4 table">
                    <thead class="table-header">
                        <tr class="table-row-header text-center">
                            <th>#</th>
                            <th>Employee Code</th>
                            <th>Dr Name</th>
                            <th>Specialty</th>
                            <th>Approval Status</th>
                            <th>Action</th>
                        </tr>

                        <!-- STATIC FILTER ROW -->
                        <tr class="filter-row text-center">
                            <th>
                                <button class="btn btn-sm btn_primary" onclick="applyFilters()">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </th>
                            <th></th>
                            <th><input name="dr_name" class="form-control" type="text" placeholder="Search Doctor Name">
                            </th>
                            <th>
                                <select name="specialty" class="form-control text-muted">
                                    <option value="">Select Specialty</option>
                                    <option value="physician">Physician</option>
                                    <option value="gyn">Gyn</option>
                                </select>
                            </th>
                            <th>
                                <select name="status" class="form-control text-muted">
                                    <option value="">Select Status</option>
                                    <option value="1">Approved</option>
                                    <option value="0">Pending</option>
                                    <option value="3">Rejected</option>
                                </select>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>

            <div id="pagination" class="d-flex justify-content-center mt-4"></div>

        </div>
    </div>

    <div id="photoModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Photo Rejected - Upload New</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">

                    <p id="photoRejectReason" class="text-danger fw-bold"></p>

                    <input id="newPhotoInput" class="form-control mb-3" type="file" accept="image/*">

                    <div style="width:350px;height:350px;margin:auto;">
                        <img id="cropperImage" style="width:100%;">
                    </div>

                </div>
                <div class="modal-footer">
                    <button id="sub_b" class="btn btn-success" onclick="uploadNewPhoto()">Upload New Photo</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let currentPage = 1;

        function loadMeetings(page = 1) {
            currentPage = page;
            let user = @json(Auth::user());
            $.get("{{ route('dr.journey.dashboard.data') }}", {
                page: page,
                search: $('#searchInput').val(),
                filters: getFilters()
            }, function(res) {
                // console.log(res.data);
                var $filters = getFilters();
                let rows = '';
                if (res.data.length > 0) {

                    // $('.filter-row').show();

                    res.data.forEach(r => {
                        rows += `
                            <tr class="text-center">
                                <td data-label="ID">${r.original_id}</td>
                                <td data-label="ME Code">${r.employee_code}</td> 
                                <td data-label="Doctor Name">Dr. ${r.name}</td>
                                <td data-label="Specialty">${r.specialty}</td>
                                <td data-label="Photo Status">${r.status}</td>
                                <td data-label="Action" >
                                    <div class="d-flex justify-content-center gap-2 align-items-center">
                                        <!-- <a href="${r.edit_url}" class="btn btn-sm btn_action">
                                            Edit
                                        </a> -->
                                        <button class="btn btn-sm btn_action"
                                            onclick="deleteRecord('${r.delete_url}')">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;

                    });


                } else {
                    rows = `
                        <tr>
                            <td colspan="100%" class="text-center py-3">
                                <span class="fw-medium  text-center">No Records Found</span>
                            </td>
                        </tr>
                    `;
                }

                $('#tableBody').html(rows);

                if (res.data.length > 0) {
                    let pagination = `<button class="btn btn_page" ${res.current_page === 1 ? 'disabled' : ''}
                        onclick="loadMeetings(${res.current_page - 1})">Prev</button>`;

                    let start = Math.max(1, res.current_page - 1);
                    let end = Math.min(res.total_pages, start + 3);

                    for (let i = start; i <= end; i++) {
                        pagination += `<button class="btn btn_page ${i === res.current_page ? 'active-page' : ''}"
                            onclick="loadMeetings(${i})">${i}</button>`;
                    }

                    pagination += `<button class="btn btn_page" ${res.current_page === res.total_pages ? 'disabled' : ''}
                        onclick="loadMeetings(${res.current_page + 1})">Next</button>`;

                    $('#pagination').html(pagination);
                }
            });
        }

        function getFilters() {
            let filters = {};

            $('.filter-row input, .filter-row select').each(function() {
                let name = $(this).attr('name');
                let value = $(this).val();

                if (name && value) {
                    filters[name] = value;
                }
            });
            console.log(filters);
            return filters;
        }

        function applyFilters() {
            loadMeetings(1); // always reset to page 1 on search
        }

        // $('#searchInput').keyup(function() {
        //     loadMeetings(1);
        // });

        $(document).ready(function() {
            loadMeetings();
        });
    </script>
    <script>
        function deleteRecord(url) {

            Swal.fire({
                title: 'Are you sure?',
                text: 'This Record Will Be Permanently Deleted',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

                if (result.isConfirmed) {
                    $('.main_loader').show();
                    let deleteUrl = url;
                    $.ajax({
                        url: deleteUrl,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function() {
                            location.reload();
                        },
                        error: function() {
                            $('.main_loader').hide();
                            Swal.fire("Error", "Something went wrong please try again!",
                                "error");
                        }
                    });

                }
            });
        }

        let cropperInstance = null;
        let currentPhotoId = null;

        $(document).on('click', '.review-photo', function() {

            currentPhotoId = $(this).data('id');
            let reason = $(this).data('reason');
            let oldPhoto = $(this).data('photo');

            $('#photoRejectReason').text("Reject Reason: " + reason);

            resetCropperCompletely();

            let img = document.getElementById('cropperImage');
            img.src = oldPhoto + '?v=' + new Date().getTime();
            img.style.display = 'block';

            img.onload = function() {

                if (cropperInstance) {
                    cropperInstance.destroy();
                    cropperInstance = null;
                }

                cropperInstance = new Cropper(img, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    restore: false,
                    guides: false,
                    center: false,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false
                });
            };

            $('#photoModal').modal('show');
        });

        function resetCropperCompletely() {

            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }

            let img = document.getElementById('cropperImage');
            img.src = '';
            img.style.display = 'none';

            //$('#newPhotoInput').val('');
        }

        $('#newPhotoInput').on('change', function() {

            if (!this.files || !this.files.length) return;

            let file = this.files[0];
            let img = document.getElementById('cropperImage');

            resetCropperCompletely();

            let objectUrl = URL.createObjectURL(file);

            img.src = objectUrl;
            img.style.display = 'block';

            $('#photoModal').off('shown.bs.modal').on('shown.bs.modal', function() {

                if (cropperInstance) {
                    cropperInstance.destroy();
                    cropperInstance = null;
                }

                cropperInstance = new Cropper(img, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    restore: false,
                    guides: false,
                    center: false,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false
                });

                URL.revokeObjectURL(objectUrl);
            });
        });

        function uploadNewPhoto() {

            const fileInput = document.getElementById('newPhotoInput');

            console.log(fileInput.files);

            if (!fileInput.files || fileInput.files.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'New Photo Required',
                    text: 'Please upload a new image before submitting'
                });
                return;
            }

            cropperInstance.getCroppedCanvas({
                width: 900,
                height: 900,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            }).toBlob(function(blob) {

                if (!blob) {
                    Swal.fire('Error', 'Failed to crop image', 'error');
                    return;
                }

                $("#sub_b").text('Uploading...').attr('disabled', true);

                let fd = new FormData();
                fd.append('photo', blob, 'photo.png');
                fd.append('id', currentPhotoId);
                fd.append('_token', "{{ csrf_token() }}");

                $.ajax({
                    url: "{{ route('dr.journey.photo.reupload') }}",
                    type: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function() {

                        $('#photoModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Uploaded Successfully'
                        }).then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire('Error', 'Upload failed', 'error');
                    }
                });

            }, 'image/png', 1);
        }

        $('#photoModal').on('hidden.bs.modal', function() {
            resetCropperCompletely();
        });
    </script>
    {{-- // // ─── OPEN AUDIO MODAL ───────────────────────────────────────────────
        // $(document).on('click', '.review-audio', function() {
        //     let audio = $(this).data('audio');
        //     currentAudioId = $(this).data('id');

        //     $('#oldAudioPlayer').attr('src', audio);
        //     $('#newAudioInput').val('');

        //     $('#audioModal').modal('show');
        // });

        // // ─── UPLOAD NEW AUDIO (ALL EXTENSIONS ALLOWED) ──────────────────────
        // function uploadNewAudio() {

        //     let input = document.getElementById('newAudioInput');
        //     let file = input.files[0];

        //     if (!file) {
        //         Swal.fire({
        //             icon: 'warning',
        //             title: 'Audio Required',
        //             text: 'Please upload a new audio file before submitting'
        //         });
        //         return;
        //     }

        //     $("#sub_b_au").text('Uploading...').attr('disabled', true);

        //     let fd = new FormData();
        //     fd.append('audio', file);
        //     fd.append('id', currentAudioId);
        //     fd.append('_token', "{{ csrf_token() }}");

        //     $.ajax({
        //         url: "{{ route('dr.journey.audio.reupload') }}",
        //         type: "POST",
        //         data: fd,
        //         contentType: false,
        //         processData: false,
        //         success: function() {
        //             Swal.fire('Uploaded!', 'Audio updated successfully', 'success')
        //                 .then(() => location.reload());
        //         },
        //         error: function() {
        //             Swal.fire('Error!', 'Something went wrong.', 'error');
        //         }
        //     });
        // } --}}
@endpush
