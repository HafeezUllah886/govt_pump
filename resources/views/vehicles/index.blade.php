@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Vehicles</h3>
                    <a data-bs-toggle="modal" data-bs-target="#new" class="btn btn-primary">Create New</a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Department</span>
                                    <select name="department" id="department" class="form-control">
                                        <option value="All">All</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ $dept->id == $department ? 'selected' : '' }}>
                                                {{ $dept->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                           
                            <div class="col-md-2">
                                <input type="submit" value="Filter" class="btn btn-success w-100">
                            </div>
                        </div>
                    </form>
                    <table class="table" id="buttons-datatables">
                        <thead>
                            <th>#</th>
                            <th>Registration</th>
                            <th>Department</th>
                            <th>Type</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($vehicles as $key => $vehicle)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $vehicle->r_no }}</td>
                                    <td>{{ $vehicle->department->title }}</td>
                                    <td>{{ $vehicle->type }}</td>

                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#edit_{{ $vehicle->id }}">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                        Edit
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="edit_{{ $vehicle->id }}" tabindex="-1"
                                    aria-labelledby="edit_{{ $vehicle->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="edit_{{ $vehicle->id }}Label">Edit Vehicle
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('vehicles.update', $vehicle->id) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="r_no">Registraion</label>
                                                        <input type="text" name="r_no" id="r_no"
                                                            class="form-control" value="{{ $vehicle->r_no }}">
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="type">Type</label>
                                                        <input type="text" name="type" id="type"
                                                            class="form-control" value="{{ $vehicle->type }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Default Modals -->
    <div class="modal fade" id="new" tabindex="-1" aria-labelledby="newLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newLabel">Create Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('vehicles.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="r_no">Registraion</label>
                            <input type="text" name="r_no" id="r_no" class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="type">Type</label>
                            <input type="text" name="type" id="type" class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="department_id">Department</label>
                            <select name="department_id" id="department_id" required class="form-control">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Default Modals -->
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/datatable.bootstrap5.min.css') }}" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/responsive.bootstrap.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/buttons.dataTables.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/jszip.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endsection
