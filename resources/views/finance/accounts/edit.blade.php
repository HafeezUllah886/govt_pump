@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Edit Account</h3>
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
                    <form action="{{ route('account.update', $account->id) }}" method="post">
                        @csrf
                        @method('put')
                        <input type="hidden" name="department_id" value="{{$account->id}}">
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="title">Department</label>
                                    <input type="text" name="title" id="title" value="{{ $account->title }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="focal_person">Focal Person</label>
                                    <input type="text" name="focal_person" id="focal_person" value="{{ $account->focal_person }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 mt-2 customer s supplier">
                                <div class="form-group">
                                    <label for="contact">Contact #</label>
                                    <input type="text" name="contact" id="contact" value="{{ $account->contact }}"
                                        class="form-control">
                                </div>
                            </div>
                          
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-secondary w-100">Update</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Default Modals -->


@endsection
