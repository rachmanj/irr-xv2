@extends('layout.main')

@section('title_page')
    USERS MANAGEMENT
@endsection

@section('breadcrumb_title')
    users
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User List</h3>
                    <button href="#" class="btn btn-sm btn-primary float-right" data-toggle="modal"
                        data-target="#addUserModal"><i class="fas fa-plus"></i> User</button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="users_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Project</th>
                                <th>Dept</th>
                                <th>LocationCode</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                        </div>

                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input type="text" name="nik" class="form-control" value="{{ old('nik') }}">
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username"
                                class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}">
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for='project'>Project</label>
                            <select name="project" id="project"
                                class="form-control select2bs4 @error('project') is-invalid @enderror">
                                <option value="">-- Select Project --</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->code }}"
                                        {{ old('project') == $project->code ? 'selected' : '' }}>{{ $project->code }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for='department_id'>Department</label>
                            <select name="department_id" id="department_id"
                                class="form-control select2bs4 @error('department_id') is-invalid @enderror">
                                <option value="">-- Select Department --</option>
                            </select>
                            @error('department_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="modal-footer float-left">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
@endsection

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/datatables/css/datatables.min.css') }}" />
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables/datatables.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#users_table").DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.users.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'username'
                    },
                    {
                        data: 'project'
                    },
                    {
                        data: 'department'
                    },
                    {
                        data: 'location_code'
                    },
                    // {
                    //     data: 'is_active'
                    // },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                fixedHeader: true,
            });

            // Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            // Handle project change and department loading
            $('#project').change(function() {
                var project = $(this).val();
                if (project) {
                    $.ajax({
                        url: '{{ route('admin.get-departments-by-project') }}',
                        type: 'GET',
                        data: {
                            project: project
                        },
                        success: function(data) {
                            $('#department_id').empty();
                            $('#department_id').append(
                                '<option value="">-- Select Department --</option>');
                            $.each(data, function(key, value) {
                                $('#department_id').append('<option value="' + value
                                    .id + '">' +
                                    value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#department_id').empty();
                    $('#department_id').append('<option value="">-- Select Department --</option>');
                }
            });

            // If there are form errors, show the modal
            @if ($errors->any())
                $('#addUserModal').modal('show');
            @endif
        });
    </script>
@endsection
