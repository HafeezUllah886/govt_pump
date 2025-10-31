@extends('layout.app')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>View Summary</h3>
                </div>
                <form action="{{route('summary.details')}}" method="get">
                <div class="card-body">
                    <div class="form-group mt-2">
                        <label for="department">Department</label>
                        <select name="department" id="department" class="form-control">
                            @foreach ($departments as $department)
                                <option value="{{$department->id}}">{{$department->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label for="month">Month</label>
                        <input type="month" name="month" id="month" class="form-control">
                    </div>

                    <div class="form-group mt-2">
                        <button class="btn btn-success w-100" id="viewBtn">View Report</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection
@section('page-js')
<script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize").selectize({
        plugins: ['remove_button'],
        maxItems: null,
        create: false,
        placeholder: 'Select Vendors...'
    });
        
    </script>
    
@endsection
