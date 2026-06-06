@extends('Admin.layouts.main')
@push('title')
    Dashboard Overview
@endpush
@section('main')
    <div class="main_content_box mt-5">

        <div class="d-flex justify-content-end mb-3">
            <a href="javascript:void(0)" class="btn btn_custom" id="deleteAllBtn">Delete All Employee</a>
            <a href="javascript:void(0)" class="btn btn_custom mx-2" data-bs-toggle="modal" data-bs-target="#importUserModal">Import Employee</a>
            <a href="{{ route('admin.user.csv') }}" class="btn btn_custom me-2">Download Csv</a>
            <a href="{{ route('admin.user.add') }}" class="btn btn_custom">Add New Employee</a>
        </div>

        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search For Name...">

        <table class="table table_user">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Employee Name</th>
                    <th>Employee Code</th>
                    <th>Parent Employee Code</th>
                    <th>Region</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>

        <div id="pagination" class="d-flex justify-content-center align-items-center gap-2 mt-4"></div>

    </div>

    <div class="modal fade" id="importUserModal" tabindex="-1" aria-labelledby="importUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="importUserForm" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.user.import') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="importUserModalLabel">Import Users CSV</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_csv" class="form-label">Choose CSV File</label>
                            <input type="file" name="user_csv" id="user_csv" class="form-control" accept=".csv"
                                required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-import">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let currentPage = 1;

        function loadData(page = 1) {
            currentPage = page;

            $.get("{{ route('admin.users.data') }}", {
                page: page,
                search: $('#searchInput').val()
            }, function(res) {

                let rows = '';
                res.data.forEach(r => {
                    rows += `
                <tr>
                    <td>${r.id}</td>
                    <td>${r.name}</td>
                    <td>${r.employee_code}</td>
                    <td>${r.parent_employee_code}</td>
                    <td>${r.region}</td>
                    <td>
                        <a href="{{ url('admin/users-edit') }}/${r.id}" class="btn btn-sm btn-admin">Edit</a>
                        <button class="btn btn-sm btn-admin" onclick="deleteUser(${r.id})">Delete</button>
                    </td>
                </tr>
            `;
                });
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

        function deleteUser(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This record will be permanently deleted',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/users/delete') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function() {
                            Swal.fire('Deleted!', 'Record deleted successfully', 'success');
                            loadData(currentPage);
                        }
                    });
                }
            });
        }

        $('#searchInput').keyup(function() {
            loadData(1);
        });

        $(document).ready(function() {
            loadData();
        });

        $(document).on('click', '#deleteAllBtn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete all users permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete all!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.user.deleteall') }}",
                        type: "GET",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire("Deleted!", response.message, "success");
                                setTimeout(function() {
                                    location.reload();
                                }, 2000);
                            } else {
                                Swal.fire("Error!", response.message, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error!", "Something went wrong!", "error");
                        }
                    });
                }
            });
        });
    </script>
@endpush
