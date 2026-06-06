@extends('Admin.layouts.main')
@push('title')
    Dashboard Overview
@endpush
@section('main')
    <div class="main_content_box mt-5">
        <div class="d-flex justify-content-end mb-3">
            <div class="d-flex justify-content-end mb-3">
                <a class="btn btn_custom me-2" href="{{ route('admin.dr.journey.request.csv') }}">Download Csv</a>
            </div>
        </div>
        <input id="searchInput" class="form-control mb-3" type="text" placeholder="Search For Name...">

        <div class="table-responsive">
            <table class="table_user table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Doctor Name​</th>
                        <th>Specialty</th>
                        <th>Dr. Mobile</th>
                        <th>Created At</th>
                        <th>ME Code</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>

        </div>
        <div id="pagination" class="d-flex justify-content-center align-items-center mt-4 gap-2"></div>

    </div>
@endsection

@push('script')
    <script>
        let currentPage = 1;

        function loadData(page = 1) {
            currentPage = page;

            $.get("{{ route('admin.dr.journey.request.data') }}", {
                page: page,
                search: $('#searchInput').val()
            }, function(res) {

                let rows = '';
                if (res.data.length > 0) {
                    res.data.forEach(r => {
                        rows += `
                        <tr>
                            <td>
                                ${r.raw_id??'-'}
                            </td>
                            <td>
                                ${r.name??'-'}
                            </td>
                            <td>
                                ${r.specialty??'-'}
                            </td>
                            <td>
                                ${r.mobile_number??'-'}
                            </td>
                            <td>
                                ${r.created_at??'-'}
                            </td>
                            <td>
                                ${r.me_code??'-'}
                            </td>
                            <td>
                                <button class="btn btn-sm btn_action"
                                    onclick="deleteRecord('${r.id}', '{{ route('admin.dr.journey.request.delete', ['id' => ':id']) }}')">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `;
                    });
                } else {

                    rows = `
                        <tr>
                            <td colspan="6" class="text-center py-3">
                                <span class="fw-medium  text-center">No Records Found</span>
                            </td>
                        </tr>
                    `;
                }

                $('#tableBody').html(rows);

                let pagination = '';

                pagination += `
                    <button class="btn btn_page" ${res.current_page === 1 ? 'disabled' : ''} onclick="loadData(${res.current_page - 1})">Prev</button>
                `;

                let start = Math.max(1, res.current_page - 1);
                let end = Math.min(res.total_pages, start + 3);

                for (let i = start; i <= end; i++) {
                    pagination += `
                        <button class="btn btn_page ${i === res.current_page ? 'active-page' : ''}" onclick="loadData(${i})">${i}</button>
                    `;
                }

                pagination += `
                    <button class="btn btn_page" ${res.current_page === res.total_pages ? 'disabled' : ''} onclick="loadData(${res.current_page + 1})">Next</button>
                `;

                $('#pagination').html(pagination);
            });
        }

        let debounceTimer;

        $('#searchInput').on('keyup change', function() {
            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(() => {
                loadData(1);
            }, 300);
        })

        $(document).ready(function() {
            loadData();
        });

        function deleteRecord(id, url) {

            Swal.fire({
                title: 'Are you sure?',
                text: 'This record will be permanently deleted',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        url: url.replace(':id', id),
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function() {
                            Swal.fire(
                                'Deleted!',
                                'Record has been deleted.',
                                'success'
                            );

                            loadData(currentPage);
                        }
                    });
                }
            });
        }
    </script>
@endpush
