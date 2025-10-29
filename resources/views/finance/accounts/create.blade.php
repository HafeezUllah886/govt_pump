@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Create Account</h3>
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
                    <form action="{{ route('account.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="title">Department</label>
                                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="focal_person">Focal Person</label>
                                    <input type="text" name="focal_person" id="focal_person" value="{{ old('focal_person') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 mt-2 customer vendor factory transport">
                                <div class="form-group">
                                    <label for="contact">Contact #</label>
                                    <input type="text" name="contact" id="contact" value="{{ old('contact') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-secondary w-100">Create</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Default Modals -->
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
@endsection
