@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Bills</h3>
                    <button mode="modal" data-bs-toggle="modal" data-bs-target="#new" class="btn btn-primary">Generate Bill</button>

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
                            <th>Bill #</th>
                            <th>Month</th>
                            <th>Department</th>
                            <th>Vehicle</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($bills as $key => $bill)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $bill->bill_no}}</td>
                                    <td>{{ $bill->month }}</td>
                                    <td>{{ $bill->department->title }}</td>
                                    <td>{{ $bill->vehicle->r_no }}</td>
                                    <td>{{ number_format($bill->total) }}</td>
                                 
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button class="dropdown-item" onclick="newWindow('{{route('bills.show', $bill->id)}}')"
                                                        onclick=""><i
                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                        View
                                                    </button>
                                                </li>
                                              
                                               {{--  <li>
                                                    <a class="dropdown-item text-danger" href="{{route('sale.delete', $bill->id)}}">
                                                        <i class="ri-delete-bin-2-fill align-bottom me-2 text-danger"></i>
                                                        Delete
                                                    </a>
                                                </li> --}} 
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
    <div class="modal fade" id="new" tabindex="-1" aria-labelledby="newLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newLabel">Salect Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('bills.create') }}" method="get">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Department</label>
                            <select name="department_id" id="department_id" onchange="getVehicle()" required class="form-control">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="name">Vechile</label>
                            <select name="vehicle_id" id="vehicle_id" required class="selectize">
                                <option value="">Select Vechile</option>
                               
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="name">Month</label>
                            <input type="month" name="month" id="month" required class="form-control">
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Continue</button>
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
<link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.print.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/vfs_fonts.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/pdfmake.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/jszip.min.js')}}"></script>

    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
      <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>

        $('#vehicle_id').selectize();

        function getVehicle()
        {
            var departmentID = $('#department_id').find(':selected').val();
            var vehicleSelectize = $('#vehicle_id')[0].selectize; // Access the selectize instance
            if (departmentID) {
        // Make an AJAX call to fetch sectors for selected town
        $.ajax({
            url: '/bills/getvehicles/' + departmentID,
            type: 'GET',
            success: function (data) {
                
                // Clear previous options 
                vehicleSelectize.clearOptions();

                // Add new options
                vehicleSelectize.addOption(data); // data should be an array of {value: '', text: ''}
                vehicleSelectize.refreshOptions(false);
            }
        });
    }
    else
    {
        vehicleSelectize.clearOptions();
    }
}


   </script>
@endsection

